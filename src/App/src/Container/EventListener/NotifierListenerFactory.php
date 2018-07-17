<?php declare(strict_types=1);

namespace App\Container\EventListener;

use Psr\Container\ContainerInterface;
use App\EventListener\NotifierListener;

class NotifierListenerFactory
{
    public function __invoke(ContainerInterface $container)
    {  
        $pushoverClient = $container->get('PushoverClient');
        
        return new NotifierListener($pushoverClient);
    }
    
}
