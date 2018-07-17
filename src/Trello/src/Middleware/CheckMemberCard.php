<?php declare(strict_types=1);

namespace FTC\Trello\Middleware;

use FTC\Trello\Model\CardRepository;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use FTC\Discord\Model\ValueObject\Snowflake\UserId;
use FTC\Discord\Model\Aggregate\GuildMemberRepository;
use FTC\Trello\Model\MemberCard;
use Zend\Expressive\Template\TemplateRendererInterface;

class CheckMemberCard implements MiddlewareInterface
{
    
    /**
     * @var TemplateRendererInterface
     */
    private $templateRenderer;
    
    /**
     * @var GuildMemberRepository
     */
    private $guildMemberRepository;
    
    /**
     * @var CardRepository
     */
    private $cardRepository;
    
    public function __construct(
        CardRepository $cardRepository,
        GuildMemberRepository $guildMemberRepository,
        TemplateRendererInterface $templateRenderer
        ) {
        $this->cardRepository = $cardRepository;
        $this->guildMemberRepository = $guildMemberRepository;
        $this->templateRenderer = $templateRenderer;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        
        if ($memberId = (int) $request->getAttribute('memberId')) {
            $memberId = UserId::create($memberId);
            $memberCard = $this->cardRepository->getMemberCard($memberId);
            
            if (!$memberCard) {
                $member = $this->guildMemberRepository->getById($memberId);
                $memberCard = $this->cardRepository->createMemberCard($member);
                $memberCard->setName((string) $member->getNickname());
                $this->cardRepository->save($memberCard);
            }
//                 var_dump($memberCard->getUrl());
            
            $this->templateRenderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'memberCard', $memberCard);
        }
        
        return $handler->handle($request);
    }
    
}
