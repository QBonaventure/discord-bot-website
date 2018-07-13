<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Discord\Model\Aggregate\GuildMemberRepository;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;

class MembersManagementFactory
{
    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $template = $container->get(TemplateRendererInterface::class);
        $guildMemberRepo = $container->get(GuildMemberRepository::class);
        $guildRolesRepo = $container->get(GuildRoleRepository::class);

        return new MembersManagement($template, $guildMemberRepo, $guildRolesRepo);
    }
}
