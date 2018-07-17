<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;
use FTC\Discord\Model\Aggregate\Guild;
use FTC\Discord\Model\ValueObject\Snowflake\RoleId;
use Psr\Http\Server\MiddlewareInterface;

class RolesManagement implements MiddlewareInterface
{
    
    /**
     * @var GuildRoleRepository $rolesRepository
     */
    private $rolesRepository;
    
    /**
     * @var TemplateRendererInterface
     */
    private $template;

    public function __construct(
        Template\TemplateRendererInterface $template = null,
        GuildRoleRepository $guildRoleRepository
    ) {
        $this->template = $template;
        $this->rolesRepository = $guildRoleRepository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $guild = $request->getAttribute(Guild::class);
        $roles = $this->rolesRepository->getAll($guild->getId())->orderByPosition();
        
        if ($selectedRoleId = (int) $request->getAttribute('roleId')) {
            $data['selectedRole'] = $roles->getById(RoleId::create($selectedRoleId));
        }
        
        $data['roles'] = $roles;

        
        return new HtmlResponse($this->template->render('admin::roles-page', $data));
    }
}
