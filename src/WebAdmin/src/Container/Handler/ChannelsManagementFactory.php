<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Container\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Discord\Model\Aggregate\GuildChannelRepository;
use FTC\WebAdmin\Handler\ChannelsManagement;
use FTC\Discord\Model\Aggregate\GuildMessageRepository;
use FTC\Discord\Model\Aggregate\GuildMemberRepository;

class ChannelsManagementFactory
{
    public function __invoke(ContainerInterface $container) : ChannelsManagement
    {
        $template = $container->get(TemplateRendererInterface::class);
        $guildChannelsRepo = $container->get(GuildChannelRepository::class);
        $guildMessagesRepository = $container->get(GuildMessageRepository::class);
        $guildMembersRepository = $container->get(GuildMemberRepository::class);

        return new ChannelsManagement($template, $guildChannelsRepo, $guildMessagesRepository, $guildMembersRepository);
    }
}
