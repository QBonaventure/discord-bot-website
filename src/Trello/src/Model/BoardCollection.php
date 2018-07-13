<?php declare(strict_types=1);

namespace FTC\Trello\Model;

use FTC\Trello\Model\Board;

class BoardCollection
{
    
    private $boards;
    
    public function add(Board $board)
    {
        $this->boards[$board->getId()] = $board;
    }
    
    public function first()
    {
        return reset($this->boards);
    }
    
    public function toArray()
    {
        return $this->boards;
    }
    
    public static function fromApi(array $data)
    {
        $coll = new self();
        $data = array_map([Board::class, 'fromAPI'], $data);
        array_walk($data, [$coll, 'add']);

        return $coll;
    }
    
}
