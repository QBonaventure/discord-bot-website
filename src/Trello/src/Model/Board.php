<?php declare(strict_types=1);

namespace FTC\Trello\Model;

class Board
{
    private $id;
    
    private $name;
    
    private $description;
    
    private function __construct(
        string $id,
        string $name,
        string $description
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getId() : string
    {
        return $this->id;
    }
    
    public static function fromAPI(array $data)
    {
        return new self($data['id'], $data['name'], $data['desc']);
    }
}
