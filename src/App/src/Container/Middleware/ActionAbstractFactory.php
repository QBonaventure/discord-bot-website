<?php declare(strict_types=1);

namespace App\Container\Middleware;

use Psr\Container\ContainerInterface;
use Zend\Stratigility\MiddlewarePipe;
use FTC\WebAdmin\Container\Handler;
use App\Middleware\EventDispatcherMiddleware;
use App\Middleware\CommandDispatcherMiddleware;

class ActionAbstractFactory 
{
    public function __invoke(ContainerInterface $container, string $actionName)
    {        
        $config = $container->get('config');
        $predispatchMiddlewares = $config->offsetGet('predispatch-pipe');
        $pipeline = new MiddlewarePipe();
        
        $middlewaresToPipe = array_filter($predispatchMiddlewares, function($middlewareName, $targetAction) use ($actionName) {
            return $actionName == $targetAction;
        }, ARRAY_FILTER_USE_BOTH);
        
        $middlewaresToPipe = array_pop($middlewaresToPipe);
        
        if ($middlewaresToPipe) {
            array_walk($middlewaresToPipe, function($middlewareName) use ($container, $pipeline)
            {
                $middleware = $container->get($middlewareName);
                $pipeline->pipe($middleware);
            });
        }
        
        $fqdnArray = explode('\\', $actionName.'Factory');
        $name = 'FTC\\WebAdmin\\Container\\Handler\\'.end($fqdnArray);
        
        $pipeline->pipe($container->get(CommandDispatcherMiddleware::class));
        
        $factory = new $name();
        $middleware = $factory($container);
        $pipeline->pipe($middleware);
        
        return $pipeline;
    }
    
    
}
