<?php declare(strict_types=1);

namespace App\Command;

use FTC\Discord\Model\Aggregate\GuildWebsitePermission;

class AddGuildWebsitePermission extends AbstractCommand
{
    
    /*
     * @var GuildWebsitePersmission
     */
    private $permission;
    
    private function __construct(GuildWebsitePermission $permission)
    {
        $this->permission = $permission;
    }
    
    public function getPermission()
    {
        return $this->permission;
    }
    
    public static function create(GuildWebsitePermission $permission) : AbstractCommand
    {
        return new self($permission);
    }
    
}
