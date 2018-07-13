<?php
declare(strict_types=1);

namespace FTC\Trello;

use FTC\Trello\Model\Board;
use FTC\Trello\Model\BoardCollection;
use Psr\Http\Message\ResponseInterface;
use FTC\Discord\Model\ValueObject\Snowflake\UserId;

class Client
{
    
    const BOARDS = 'https://api.trello.com/1/members/me/boards';
    
    const BOARD_LISTS = 'https://api.trello.com/1/boards/%s/lists';
    
    const BOARDS_CARDS = 'https://api.trello.com/1/boards/%s/cards';
    
    const ADD_CARD = 'https://api.trello.com/1/cards';
    
    private $httpClient;
    
    private $config;
    
    public function __construct(
        $client,
        $config)
    {
        $this->httpClient = $client;
        $this->config = $config;
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
        
//         foreach($boards->toArray() as $board) {
//             $id = $board->getId();
//             $response = $this->httpClient->get(sprintf(self::BOARD_LISTS, $id), [
//                 'query' => [
//                     'key' => $this->config['api-key'],
//                     'token' => $this->config['token'],
//                 ],
//             ]);
//         }
        
//             var_dump($board->getName(), $board->getId());
//             var_dump($this->parseResponse($response));
        $response = $this->httpClient->post(self::ADD_CARD, [
            'query' => [
                'name' => 'Raziel',
                'idList' => '5b48adbc2990b844be226152',
                'key' => $this->config['api-key'],
                'token' => $this->config['token'],
            ],
        ]);
        var_dump($response);
//         5b48adbc2990b844be226152
        
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
