<?php declare(strict_types=1);

namespace FTC\Trello\Container\Model;

use Psr\Container\ContainerInterface;
use FTC\Trello\Model\CardRepository;
use FTC\Discord\Db\Core;
use FTC\Trello\Client;

class CardRepositoryFactory
{
    
    public function __invoke(ContainerInterface $container)
    {
        $database = $container->get(Core::class);
        $trelloClient = $container->get(Client::class);
        
        return new CardRepository($database, $trelloClient);
    }
    
}
