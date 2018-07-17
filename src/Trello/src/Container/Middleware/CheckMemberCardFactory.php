<?php declare(strict_types=1);

namespace FTC\Trello\Container\Middleware;

use Psr\Container\ContainerInterface;
use FTC\Trello\Model\CardRepository;
use FTC\Trello\Middleware\CheckMemberCard;
use FTC\Discord\Model\Aggregate\GuildMemberRepository;
use Zend\Expressive\Template\TemplateRendererInterface;

class CheckMemberCardFactory
{
    
    public function __invoke(ContainerInterface $container)
    {
        $cardRepository = $container->get(CardRepository::class);
        $guildMemberRepository = $container->get(GuildMemberRepository::class);
        $templateRenderer = $container->get(TemplateRendererInterface::class);
        
        return new CheckMemberCard($cardRepository, $guildMemberRepository, $templateRenderer);
    }
    
}
