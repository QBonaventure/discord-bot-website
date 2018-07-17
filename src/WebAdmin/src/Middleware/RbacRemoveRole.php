<?php declare(strict_types=1);

namespace FTC\WebAdmin\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use FTC\Discord\Model\Aggregate\GuildWebsitePermissionRepository;
use FTC\Discord\Model\ValueObject\Snowflake\RoleId;
use App\Command\RemoveGuildWebsitePermission;
use Zend\Expressive\Router\RouteResult;
use League\Tactician\CommandBus;

class RbacRemoveRole implements MiddlewareInterface
{
    
    /**
     * @var GuildWebsitePermissionRepository
     */
    private $permissionsRepository;
    
    /**
     * @var CommandBus
     */
    private $commandBus;
    
    public function __construct(
        GuildWebsitePermissionRepository $permissionRepository,
        $commandBus
    ) {
        $this->permissionsRepository = $permissionRepository;
        $this->commandBus = $commandBus;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $action = $request->getAttribute(RouteResult::class)->getMatchedRouteName();
        if ($action== 'admin.rbac.remove' && $roleId = $request->getAttribute('roleId') && $routeName = $request->getAttribute('routeName')) {
            $roleId = RoleId::create((int) $request->getAttribute('roleId'));
            $permission = $this->permissionsRepository->getPermission($roleId, $routeName);
            
            if ($permission) {
                $this->commandBus->handle(RemoveGuildWebsitePermission::create($permission));
            }
        }
        
        return $handler->handle($request);
    }
    
}