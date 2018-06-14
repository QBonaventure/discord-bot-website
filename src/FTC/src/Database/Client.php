<?php
namespace FTC\Database;

class Client
{
    
    private $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
}
