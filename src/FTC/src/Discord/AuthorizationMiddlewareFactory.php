<?php

declare(strict_types=1);

namespace FTC\Discord;

use Psr\Container\ContainerInterface;
use Zend\Permissions\Rbac\Rbac;
use FTC\Discord\Model\Aggregate\GuildWebsitePermissionRepository;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;

class AuthorizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : AuthorizationMiddleware
    {
        $permissionRepository = $container->get(GuildWebsitePermissionRepository::class);
        $rolesrepository = $container->get(GuildRoleRepository::class);
        $template = $container->get(TemplateRendererInterface::class);

        $rbac = new Rbac();
        $rbac->setCreateMissingRoles(true);
        
        return new AuthorizationMiddleware($rbac, $permissionRepository, $rolesrepository, $template);
    }
}
