<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Command\AbstractCommand;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;

class CommandDispatcherMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        if ($command = $request->getAttribute(AbstractCommand::class)) {
            $cmdHandlerName= $command->getHandlerClassName();
            ($this->container->get($cmdHandlerName))($command);
        }
        return  $handler->handle($request);
    }
    
}
