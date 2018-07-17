<?php declare(strict_types=1);

namespace FTC\Trello\Model;

use FTC\Discord\Model\ValueObject\Snowflake\UserId;
use FTC\Trello\Client;
use FTC\Discord\Model\Aggregate\GuildMember;

class CardRepository
{
    
    const SELECT_MEMBER_CARD_ID = "SELECT card_id FROM trello_members_cards_ids WHERE user_id = :user_id";
    const INSERT_MEMBER_CARD_ID = "INSERT INTO trello_members_cards_ids VALUES (:user_id, :card_id) ON CONFLICT (user_id) DO NOTHING";
    
    /**
     * @var \PDO
     */
    private $persistence;
    
    /**
     * @var Client
     */
    private $client;
    
    public function __construct(
        \PDO $persistence,
        Client $client
    ){
        $this->persistence = $persistence;
        $this->client = $client;
        
    }
    
    public function save(MemberCard $card) : void
    {
        $this->client->saveCard($card);
    }
    
    public function getMemberCard(UserId $userId) : ?MemberCard
    {
        $stmt = $this->persistence->prepare(self::SELECT_MEMBER_CARD_ID);
        $stmt->bindValue('user_id', (string) $userId, \PDO::PARAM_STR);
        $stmt->execute();
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return new MemberCard($row['card_id'], $userId);
    }
   
    
    public function createMemberCard(GuildMember $member) : ?MemberCard
    {
        $memberCard = $this->client->createMemberCard($member);
        $stmt = $this->persistence->prepare(self::INSERT_MEMBER_CARD_ID);
        $stmt->bindValue('user_id', (int) (string) $member->getId(), \PDO::PARAM_INT);
        $stmt->bindValue('card_id', (string) $memberCard->getId(), \PDO::PARAM_STR);
        $stmt->execute();
        
        return $memberCard;
    }
    
    
}
