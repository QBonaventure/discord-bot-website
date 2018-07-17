<?php declare(strict_types=1);

namespace FTC\WebAdmin\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use FTC\Discord\Model\Aggregate\GuildWebsitePermissionRepository;
use FTC\Discord\Model\ValueObject\Snowflake\RoleId;
use FTC\Discord\Model\Aggregate\GuildWebsitePermission;
use FTC\Discord\Model\Aggregate\Guild;
use App\Command\AddGuildWebsitePermission;
use Zend\Expressive\Router\RouteResult;

class RbacAddRole implements MiddlewareInterface
{
    
    /**
     * @var GuildWebsitePermissionRepository
     */
    private $permissionsRepository;
    
    /**
     * @var
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
        if ($action== 'admin.rbac.add' && $roleId = $request->getAttribute('roleId') && $routeName = $request->getAttribute('routeName')) {
            $guild = $request->getAttribute(Guild::class);
            $roleId = RoleId::create((int) $request->getAttribute('roleId'));
            $permission = new GuildWebsitePermission($guild->getId(), $roleId, $routeName);
            
            $this->commandBus->handle(AddGuildWebsitePermission::create($permission));
        }
        
        return $handler->handle($request);
    }
    
}