<?php declare(strict_types=1);

namespace FTC\Trello\Model;

use FTC\Discord\Model\ValueObject\Snowflake\UserId;
use FTC\Discord\Model\Aggregate\GuildMember;
use FTC\Trello\Client;

class MemberCard
{
    
    /**
     * @var UserId
     */
    private $userId;
    
    /**
     * @var string id
     */
    private $id;
    
    /**
     * @var string
     */
    private $name;
    
    public function __construct(
        string $id,
        UserId $userId,
        $name = null
    ) {
       $this->id = $id;
       $this->userId = $userId;
       $this->name = $name;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getUrl()
    {
        return Client::BASE_URL.'c/'.$this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
}
