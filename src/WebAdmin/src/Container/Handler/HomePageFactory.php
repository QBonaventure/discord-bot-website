<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Container\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Trello\Client;
use FTC\Discord\Model\Aggregate\GuildMemberRepository;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;
use FTC\WebAdmin\Handler\HomePage;

class HomePageFactory
{
    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $router   = $container->get(RouterInterface::class);
        $template = $container->get(TemplateRendererInterface::class);
        $guildMemberRepo = $container->get(GuildMemberRepository::class);
        $guildRolesRepo = $container->get(GuildRoleRepository::class);
        $trelloClient = $container->get(Client::class);

        return new HomePage($router, $template, $guildMemberRepo, $guildRolesRepo, $trelloClient);
    }
}
