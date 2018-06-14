<?php

declare(strict_types=1);

namespace FTC\Discord;

use Psr\Container\ContainerInterface;
use Discord\OAuth\Discord;

class OAuthFactory
{
    public function __invoke(ContainerInterface $container) : Discord
    {
        $config = $container->get('config')->offsetGet('session')['discord_oauth'];
        
        return new Discord($config);
    }
}
