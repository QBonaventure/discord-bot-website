<?php

declare(strict_types=1);

namespace App\Session;

use Psr\Container\ContainerInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Lcobucci\Clock\SystemClock;
use Dflydev\FigCookies\SetCookie;

class SessionMiddlewareFactory
{
    
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')->offsetGet('session');
        
        return new SessionMiddleware(
            new Sha256(),
            $config['key'],
            $config['key'],
            SetCookie::create($config['cookie_name'])
                ->withSecure(false)
                ->withHttpOnly(true)
                ->withPath('/'),
            new Parser(),
            $config['expiration_time'],
            new SystemClock()
        );
    }
    
}
