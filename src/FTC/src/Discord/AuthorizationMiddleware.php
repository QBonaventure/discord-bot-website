<?php declare(strict_types=1);

declare(strict_types=1);

namespace FTC\Discord;

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

class AuthorizationMiddleware implements MiddlewareInterface
{
    
    
    /**
     * @var Rbac $rbac
     */
    private $rbac;
    
    
    /**
     * @var GuildWebsitePermissionRepository 
     */
    private $permissionRepository;
    
    
    /**
     * @var TemplateRendererInterface
     */
    private $templateRenderer;
    
    
    public function __construct(Rbac $rbac, GuildWebsitePermissionRepository $permissionRepository, TemplateRendererInterface $template)
    {
        $this->rbac = $rbac;
        $this->permissionRepository = $permissionRepository;
        $this->templateRenderer = $template;
    }
   
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $guild = $request->getAttribute(Guild::class);
//         var_dump($guild->getOwnerId());
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $routeName = $request->getAttribute(RouteResult::class)->getMatchedRouteName();
        $everyoneRole = $request->getAttribute('@everyone');
        
        $permissions = $this->permissionRepository->getGuildPermissions($guild->getId());
        $this->addRbacRoles($guild->getRoles());
        $this->addRbacPermissions($permissions);
        
        $user = $session->get('user');
        
        $this->templateRenderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'rbac', $this->rbac);
        $this->templateRenderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'user', $user);

        if ($this->rbac->isGranted((string) $everyoneRole->getId(), $routeName)) {
            return $handler->handle($request);
        }
        if ($user && ($guild->getOwnerId()->get() == $user['user_id'] OR $this->isGranted($user, $routeName))) {
            return $handler->handle($request);
        }
        
        
        $previousUrl = $request->getHeaders()['referer'][0];
        $response = new HtmlResponse($this->templateRenderer->render("error::403", ['previousUrl' => $previousUrl]));
        return $response->withStatus(403);
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
    
}
