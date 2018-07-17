<?php
declare(strict_types=1);

namespace FTC\Trello;

use FTC\Trello\Model\Board;
use FTC\Trello\Model\BoardCollection;
use Psr\Http\Message\ResponseInterface;
use FTC\Discord\Model\ValueObject\Snowflake\UserId;
use FTC\Discord\Model\Aggregate\GuildMember;
use FTC\Trello\Model\MemberCard;

class Client
{
    
    const BASE_URL = 'https://trello.com/';
    
    const BOARDS = 'https://api.trello.com/1/members/me/boards';
    
    const BOARD_LISTS = 'https://api.trello.com/1/boards/%s/lists';
    
    const BOARDS_CARDS = 'https://api.trello.com/1/boards/%s/cards';
    
    const ADD_CARD = 'https://api.trello.com/1/cards';
    
    const INSERT_BLANK_CARD = 'https://api.trello.com/1/cards';
    
    const UPDATE_CARD = 'https://api.trello.com/1/cards/%s';
    
    private $httpClient;
    
    private $config;

    private $mandatoryQueryParams = [];
    
    public function __construct(
        $client,
        $config)
    {
        $this->httpClient = $client;
        $this->config = $config;
        $this->mandatoryQueryParams = [
            'key' => $config['api-key'],
            'token' => $config['token'],
        ];
    }
    
    public function saveCard($card)
    {
        $query = $this->mandatoryQueryParams;
        $query = $query + [
            'name' => $card->getName(),
            
        ];
        $response = $this->httpClient->put(sprintf(self::UPDATE_CARD, $card->getId()), [
            'query' => $query
        ]);
        
        $data = $this->parseResponse($response);
    }
    
    public function getBoards() : BoardCollection
    {
        $response = $this->httpClient->get(self::BOARDS, [
            'query' => [
                'key' => $this->config['api-key'],
                'token' => $this->config['token'],
            ],
        ]);
        
        $data = $this->parseResponse($response);
        $boards = BoardCollection::fromApi($data);
        
//         $response = $this->httpClient->post(self::ADD_CARD, [
//             'query' => [
//                 'name' => 'Raziel',
//                 'idList' => '5b48adbc2990b844be226152',
//                 'key' => $this->config['api-key'],
//                 'token' => $this->config['token'],
//             ],
//         ]);
//         var_dump($this->parseResponse($response));
        
        return $boards;
    }
    
    public function getBoardCards(Board $board)
    {
        $url = sprintf(self::BOARDS_CARDS, $board->getId());
        $response= $this->httpClient->get($url, [
            'query' => [
                'key' => $this->config['api-key'],
                'token' => $this->config['token'],
            ],
        ]);
        $data = $this->parseResponse($response);
        var_dump($data);
    }
    
    public function createMemberCard(GuildMember $guildMember) : MemberCard
    {
        $url = sprintf(self::INSERT_BLANK_CARD);
        $response= $this->httpClient->post($url, [
            'query' => [
                'idList' => '5b48adbc2990b844be226152',
                'key' => $this->config['api-key'],
                'token' => $this->config['token'],
            ],
        ]);
        $data = $this->parseResponse($response);
        
       return new MemberCard($data['id'], $guildMember->getId());
    }
    
    
    public function getMemberCard(UserId $userId)
    {
        $url = sprintf(self::BOARDS_CARDS, $board->getId());
        $response= $this->httpClient->get($url, [
            'query' => [
                'key' => $this->config['api-key'],
                'token' => $this->config['token'],
            ],
        ]);
        $data = $this->parseResponse($response);
        var_dump($data);
    }
    
    private function parseResponse(ResponseInterface $response) : array
    {
        $responseText = $response->getBody()->getContents();
        $response = json_decode($responseText, true);
        
        return $response;
    }
}
