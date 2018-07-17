<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Container\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;
use Psr\Http\Server\MiddlewareInterface;
use FTC\WebAdmin\Handler\RolesManagement;

class RolesManagementFactory
{
    public function __invoke(ContainerInterface $container) : MiddlewareInterface
    {
        $template = $container->get(TemplateRendererInterface::class);
        $guildRolesRepo = $container->get(GuildRoleRepository::class);

        return new RolesManagement($template, $guildRolesRepo);
    }
}
