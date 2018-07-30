<?php declare(strict_types=1);

namespace App\Container;

use Psr\Container\ContainerInterface;
use Discord\OAuth\Discord;

class OAuthFactory
{
    public function __invoke(ContainerInterface $container) : Discord
    {
        $config = $container->get('config')->offsetGet('session')['discord_oauth'];
//         $config['redirect_uri'] = 'http://'.$_SERVER['HTTP_HOST'].'/login';
//         $config['redirect_uri'] = 'http://raziel.ftcbot-dev.test/login';
        
        
        return new Discord($config);
    }
}
