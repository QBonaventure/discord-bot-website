<?php

declare(strict_types=1);

namespace FTC\Database;

use Psr\Container\ContainerInterface;

class ClientFactory {
    
    public function __invoke(ContainerInterface $container) : Client
    {
        $config = $container->get('config')->offsetGet('core-db');
        $pdo = new PDO
        
        return new Client($pdo, $config);
    }
    
}
