<?php declare(strict_types=1);

namespace App\Container\Middleware;

use Psr\Container\ContainerInterface;
use App\Middleware\CommandDispatcherMiddleware;

class CommandDispatcherMiddlewareFactory 
{
    public function __invoke(ContainerInterface $container)
    {        
        return new CommandDispatcherMiddleware($container);
    }
    
    
}
