<?php declare(strict_types=1);

namespace FTC\WebAdmin\Container\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use FTC\Discord\Model\Aggregate\GuildWebsitePermissionRepository;
use FTC\WebAdmin\Middleware\RbacAddRole;

class RbacAddRoleFactory
{
    public function __invoke(ContainerInterface $container) : MiddlewareInterface
    {
        $permissionsRepository = $container->get(GuildWebsitePermissionRepository::class);
        $commandBus = $container->get('CommandBus');

        return new RbacAddRole($permissionsRepository, $commandBus);
    }
}
