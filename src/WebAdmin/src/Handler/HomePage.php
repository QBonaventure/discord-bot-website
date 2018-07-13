<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use FTC\Discord\Model\Aggregate\GuildMemberRepository;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Trello\Client;
use FTC\Discord\Model\ValueObject\Snowflake\GuildId;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;
use Zend\Expressive\Router\RouterInterface;

class HomePage implements RequestHandlerInterface
{
    /**
     * @var GuildMemberRepository $memberRepository
     */
    private $memberRepository;
    
    /**
     * @var GuildRoleRepository $rolesRepository
     */
    private $rolesRepository;
    
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TemplateRendererInterface
     */
    private $template;
    
    private $trelloClient;

    public function __construct(
        Router\RouterInterface $router,
        Template\TemplateRendererInterface $template = null,
        GuildMemberRepository $memberRepository,
        GuildRoleRepository $guildRoleRepository,
        Client $trelloClient
    ) {
        $this->router = $router;
        $this->template = $template;
        $this->memberRepository = $memberRepository;
        $this->rolesRepository = $guildRoleRepository;
        $this->trelloClient = $trelloClient;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $boards = $this->trelloClient->getBoards();
        $board = $boards->first();
        
//         array_walk($boards->toArray(), function($board) { var_dump($board->getName());});
//         var_dump($this->trelloClient->getBoardCards($board));
        
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $data['members'] = $this->memberRepository->getAll(GuildId::create(384396784807575552));
        $data['roles'] = $this->rolesRepository->getAll(GuildId::create(384396784807575552));
        
        return new HtmlResponse($this->template->render('admin::home-page', $data));
    }
}
