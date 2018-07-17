<?php declare(strict_types=1);

namespace App\Container;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use Interop\Container\ContainerInterface;
use App\Command\RemoveGuildWebsitePermission;
use App\CommandHandler\RemoveGuildWebsitePermissionHandler;
use App\Command\AddGuildWebsitePermission;
use App\CommandHandler\AddGuildWebsitePermissionHandler;

class CommandBusFactory
{
    
    private $commandMapping = [
        AddGuildWebsitePermission::class => AddGuildWebsitePermissionHandler::class,
        RemoveGuildWebsitePermission::class => RemoveGuildWebsitePermissionHandler::class,
    ];
    
    public function __invoke(ContainerInterface $container)
    {
        $inflector = new InvokeInflector();
        $locator = new ContainerLocator($container, $this->commandMapping);
        $nameExtractor = new ClassNameExtractor();
        
        $commandHandlerMiddleware = new CommandHandlerMiddleware(
            $nameExtractor,
            $locator,
            $inflector
            );
        
        $commandBus = new CommandBus([
            $commandHandlerMiddleware
        ]);
        
        return $commandBus;
    }
}
