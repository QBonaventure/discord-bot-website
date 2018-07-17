<?php declare(strict_types=1);

namespace App\Event;

use Psr\Container\ContainerInterface;

class GuildWebsitePermissionAdded
{
    
    public function __invoke(ContainerInterface $container)
    {
        return new self();
    }
    
}
