<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Container\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Discord\Model\Aggregate\GuildChannelRepository;
use FTC\WebAdmin\Handler\ChannelsManagement;

class ChannelsManagementFactory
{
    public function __invoke(ContainerInterface $container) : ChannelsManagement
    {
        $template = $container->get(TemplateRendererInterface::class);
        $guildRolesRepo = $container->get(GuildChannelRepository::class);

        return new ChannelsManagement($template, $guildRolesRepo);
    }
}
