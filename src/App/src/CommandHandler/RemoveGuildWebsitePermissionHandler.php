<?php declare(strict_types=1);

namespace App\CommandHandler;

use App\Command\RemoveGuildWebsitePermission;
use FTC\Discord\Model\Aggregate\GuildWebsitePermissionRepository;
use Zend\EventManager\EventManagerInterface;
use App\EventListener\GuildWebsitePermissionRemoved;

class RemoveGuildWebsitePermissionHandler
{
    /**
     * @var EventManagerInterface
     */
    private $eventManager;
    
    /**
     * @var GuildWebsitePermissionRepository
     */
    private $permissionsRepository;
    
    
    public function __construct(GuildWebsitePermissionRepository $permissionsRepository, EventManagerInterface $eventManager)
    {
        $this->permissionsRepository = $permissionsRepository;
        $this->eventManager = $eventManager;
    }
    
    public function __invoke(RemoveGuildWebsitePermission $cmd)
    {
        $permission = $cmd->getPermission();
        if ($this->permissionsRepository->remove($permission)) {
            $this->eventManager->trigger(GuildWebsitePermissionRemoved::class, $this,  ['command' => $cmd]);
            return true;
        }
        
        return false;
    }
    
}
