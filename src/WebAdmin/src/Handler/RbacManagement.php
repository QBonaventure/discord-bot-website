<?php declare(strict_types=1);

namespace FTC\WebAdmin\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Discord\Model\Aggregate\GuildWebsitePermissionRepository;
use FTC\Discord\Model\Aggregate\Guild;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;

class RbacManagement implements MiddlewareInterface
{
    
    /**
     * @var TemplateRendererInterface
     */
    private $template;
    
    /**
     * @var GuildWebsitePermissionRepository
     */
    private $permissionsRepository;
    
    /**
     * @var GuildRoleRepository $rolesRepository
     */
    private $rolesRepository;
    
    public function __construct(
        TemplateRendererInterface $template,
        GuildWebsitePermissionRepository $permissionRepository,
        GuildRoleRepository $rolesRepository
    ) {
        $this->template= $template;
        $this->permissionsRepository = $permissionRepository;
        $this->rolesRepository = $rolesRepository;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $guild = $request->getAttribute(Guild::class);
        
        $data['permissions'] = $this->permissionsRepository->getGuildPermissions($guild->getId())->groupedByRoute();
        $data['roles'] = $this->rolesRepository->getAll($guild->getId());
        
        return new HtmlResponse($this->template->render('admin::rbac-page', $data));
    }
    
}