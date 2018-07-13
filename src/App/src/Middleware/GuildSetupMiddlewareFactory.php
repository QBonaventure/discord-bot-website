<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use FTC\Discord\Model\Aggregate\GuildRepository;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;

class GuildSetupMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : GuildSetupMiddleware
    {
        $guildRepository = $container->get(GuildRepository::class);
        $guildRoleRepository = $container->get(GuildRoleRepository::class);
        
        return new GuildSetupMiddleware($guildRepository, $guildRoleRepository);
    }
}
