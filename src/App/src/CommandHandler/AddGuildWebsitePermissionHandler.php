<?php declare(strict_types=1);

namespace App\CommandHandler;

use FTC\Discord\Model\Aggregate\GuildWebsitePermissionRepository;
use Zend\EventManager\EventManagerInterface;
use App\Event\GuildWebsitePermissionAdded;
use App\Command\AddGuildWebsitePermission;

class AddGuildWebsitePermissionHandler
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
    
    public function __invoke(AddGuildWebsitePermission $cmd)
    {
        $permission = $cmd->getPermission();
        if ($this->permissionsRepository->save($permission)) {
            $this->eventManager->trigger(GuildWebsitePermissionAdded::class, $this,  ['command' => $cmd]);
        }
    }
    
}
