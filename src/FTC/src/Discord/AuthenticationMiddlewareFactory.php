<?php

declare(strict_types=1);

namespace FTC\Discord;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : AuthenticationMiddleware
    {
        $template = $container->get(TemplateRendererInterface::class);
        $sessionConfig = $container->get('config')->offsetGet('session');
        $botConfig = $container->get('config')->offsetGet('bot');
        $config = array_merge($sessionConfig, $botConfig);
        $oauth = $container->get('discord_oauth');
        $database = $container->get('database');
        
        return new AuthenticationMiddleware($template, $oauth, $database, $config);
    }
}
