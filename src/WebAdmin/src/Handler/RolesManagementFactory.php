<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;

class RolesManagementFactory
{
    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $template = $container->get(TemplateRendererInterface::class);
        $guildRolesRepo = $container->get(GuildRoleRepository::class);

        return new RolesManagement($template, $guildRolesRepo);
    }
}
