<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Container\Handler;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\WebAdmin\Handler\RbacManagement;
use Psr\Http\Server\MiddlewareInterface;
use FTC\Discord\Model\Aggregate\GuildWebsitePermissionRepository;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;

class RbacManagementFactory
{
    public function __invoke(ContainerInterface $container) : MiddlewareInterface
    {
        $template = $container->get(TemplateRendererInterface::class);
        $permissionsRepository = $container->get(GuildWebsitePermissionRepository::class);
        $rolesRepository = $container->get(GuildRoleRepository::class);

        return new RbacManagement($template, $permissionsRepository, $rolesRepository);
    }
}
