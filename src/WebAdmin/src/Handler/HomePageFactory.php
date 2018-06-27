<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Discord\Model\GuildMemberRepository;

class HomePageFactory
{
    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $router   = $container->get(RouterInterface::class);
        $template = $container->get(TemplateRendererInterface::class);
        $guildMemberRepo = $container->get(GuildMemberRepository::class);

        return new HomePage($router, $template, $guildMemberRepo);
    }
}
