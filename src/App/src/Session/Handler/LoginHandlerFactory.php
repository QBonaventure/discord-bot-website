<?php

declare(strict_types=1);

namespace App\Session\Handler;

use Psr\Container\ContainerInterface;
use FTC\Discord\Model\GuildMemberRepository;

class LoginHandlerFactory
{
    public function __invoke(ContainerInterface $container) : LoginHandler
    {
        $sessionConfig = $container->get('config')->offsetGet('session');
        $botConfig = $container->get('config')->offsetGet('bot');
        $config = array_merge($sessionConfig, $botConfig);
        $oauth = $container->get('discord_oauth');
        $repository = $container->get(GuildMemberRepository::class);
        
        return new LoginHandler($repository, $oauth, $config);
    }
}
