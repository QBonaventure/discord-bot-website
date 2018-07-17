<?php declare(strict_types=1);

namespace App\Event;

use Psr\Container\ContainerInterface;

class GuildWebsitePermissionRemoved
{
    
    public function __invoke(ContainerInterface $container)
    {
        return new self();
    }
    
    public function onGuildWebsitePermissionRemoved()
    {
    }
    
}
