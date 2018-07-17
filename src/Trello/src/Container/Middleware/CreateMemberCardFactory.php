<?php declare(strict_types=1);

namespace FTC\Trello\Container\Middleware;

use Psr\Container\ContainerInterface;
use FTC\Trello\Model\CardRepository;
use FTC\Trello\Middleware\CreateMemberCard;

class CreateMemberCardFactory
{
    
    public function __invoke(ContainerInterface $container)
    {
        $cardRepository = $container->get(CardRepository::class);
        
        return new CreateMemberCard($cardRepository);
    }
    
}
