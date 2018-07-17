<?php declare(strict_types=1);

namespace FTC\WebAdmin\Container\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use FTC\Discord\Model\Aggregate\GuildWebsitePermissionRepository;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;
use FTC\WebAdmin\Middleware\RbacRemoveRole;

class RbacRemoveRoleFactory
{
    public function __invoke(ContainerInterface $container) : MiddlewareInterface
    {
        $permissionsRepository = $container->get(GuildWebsitePermissionRepository::class);
        $commandBus = $container->get('CommandBus');

        return new RbacRemoveRole($permissionsRepository, $commandBus);
    }
}
