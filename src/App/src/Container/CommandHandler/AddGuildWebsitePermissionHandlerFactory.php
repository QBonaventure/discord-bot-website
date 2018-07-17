<?php declare(strict_types=1);

namespace App\Container\CommandHandler;

use FTC\Discord\Model\Aggregate\GuildWebsitePermissionRepository;
use Psr\Container\ContainerInterface;
use App\CommandHandler\RemoveGuildWebsitePermissionHandler;
use Zend\EventManager\EventManager;
use App\Command\RemoveGuildWebsitePermission;
use Zend\EventManager\LazyListener;
use App\EventListener\GuildWebsitePermissionRemoved;
use App\EventListener\NotifierListener;
use App\CommandHandler\AddGuildWebsitePermissionHandler;

class AddGuildWebsitePermissionHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {  
        $permissionsRepository = $container->get(GuildWebsitePermissionRepository::class);
        
        $events = new EventManager();
        $events->setIdentifiers([
            RemoveGuildWebsitePermission::class,
        ]);
        
        $lazyListener = new LazyListener([
                'listener' => NotifierListener::class,
                'method' => 'onGuildWebsitePermissionAdded',
            ],
            $container
        );
        $events->attach(GuildWebsitePermissionAdded::class, $lazyListener);
        
        return new AddGuildWebsitePermissionHandler($permissionsRepository, $events);
    }
    
}
