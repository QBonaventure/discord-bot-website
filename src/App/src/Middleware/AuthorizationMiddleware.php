<?php declare(strict_types=1);

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Discord\OAuth\Discord;
use FTC\Discord\Model\Aggregate\Guild;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use Zend\Permissions\Rbac\Rbac;
use FTC\Discord\Model\Aggregate\GuildWebsitePermissionRepository;
use FTC\Discord\Model\ValueObject\Snowflake\RoleId;
use FTC\Discord\Model\Collection\GuildRoleIdCollection;
use FTC\Discord\Model\Collection\GuildWebsitePermissionCollection;
use Zend\Expressive\Router\RouteResult;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Discord\Model\Aggregate\GuildWebsitePermission;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;
use FTC\Discord\Model\ValueObject\Permission;
use FTC\Discord\Model\ValueObject\Snowflake\GuildId;

class AuthorizationMiddleware implements MiddlewareInterface
{
    
    
    /**
     * @var Rbac $rbac
     */
    private $rbac;
    
    
    /**
     * @var GuildRoleRepository
     */
    private $rolesRepository;
    
    /**
     * @var GuildWebsitePermissionRepository 
     */
    private $permissionRepository;
    
    
    /**
     * @var TemplateRendererInterface
     */
    private $templateRenderer;
    
    
    public function __construct(
        Rbac $rbac,
        GuildWebsitePermissionRepository $permissionRepository,
        GuildRoleRepository $rolesRepository,
        TemplateRendererInterface $template)
    {
        $this->rbac = $rbac;
        $this->permissionRepository = $permissionRepository;
        $this->rolesRepository = $rolesRepository;
        $this->templateRenderer = $template;
    }
   
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $guild = $request->getAttribute(Guild::class);
        
        if (!$guild) {
            return $handler->handle($request);
        }
        
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $routeName = $request->getAttribute(RouteResult::class)->getMatchedRouteName();
        $everyoneRole = $request->getAttribute('@everyone');
        
        $permissions = $this->permissionRepository->getGuildPermissions($guild->getId());
        $this->addRbacRoles($guild->getRoles());
        $this->addRbacPermissions($permissions);

        if (!$permissions->hasForRoute($routeName)) {
            $this->addAdministratorPermission($guild->getId(), $routeName);
        }
        
        $this->templateRenderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'rbac', $this->rbac);
        
        if ($this->rbac->isGranted((string) $everyoneRole->getId(), $routeName)) {
            return $handler->handle($request);
        }
        
        $user = $session->get('user');
        if ($user && ($guild->getOwnerId()->get() == $user['user_id'] OR $this->isGranted($user, $routeName))) {
            $this->templateRenderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'user', $user);
            return $handler->handle($request);
        }
        
        $previousUrl = $request->getHeaders()['referer'][0];
        
        return new HtmlResponse($this->templateRenderer->render("error::403", ['previousUrl' => $previousUrl], 403));
    }
    
    
    private function isGranted($user, $routeName)
    {
        return (!empty(array_filter($user['roles'], function($roleId) use ($routeName) {
            return $this->rbac->isGranted((string) $roleId, $routeName);
        })));
    }
    
    
    private function addRbacPermissions(GuildWebsitePermissionCollection $permissions) : void
    {
        array_walk(
            $permissions->getIterator(),
            function($permission) {
                $this->rbac->getRole($permission->getRoleName())->addPermission($permission->getRouteName());
            }
       );
    }
    
    
    private function addRbacRoles(GuildRoleIdCollection $roles) : void
    {
        array_walk(
            $roles->getIterator(),
            function($roleId) { $this->rbac->addRole((string) $roleId); }
        );
    }
    
    private function addAdministratorPermission(GuildId $guildId, string $routeName)
    {
        $adminRoles = $this->rolesRepository->findByPermission($guildId, new Permission(Permission::ADMINISTRATOR));
        
        array_walk($adminRoles->getIterator(), function ($role) use ($guildId, $routeName) {
            $newPermission = new GuildWebsitePermission($guildId, $role->getId(), $routeName);
            $this->permissionRepository->save($newPermission);
        });
    }
    
}
