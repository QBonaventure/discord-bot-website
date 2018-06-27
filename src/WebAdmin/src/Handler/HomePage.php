<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use FTC\Discord\Model\GuildMemberRepository;
use Zend\Expressive\Template\TemplateRendererInterface;

class HomePage implements RequestHandlerInterface
{
    private $memberRepository;

    private $router;

    /**
     * @var TemplateRendererInterface
     */
    private $template;

    public function __construct(
        Router\RouterInterface $router,
        Template\TemplateRendererInterface $template = null,
        GuildMemberRepository $memberRepository
    ) {
        $this->router        = $router;
        $this->template      = $template;
        $this->memberRepository = $memberRepository;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $data['members'] = $this->memberRepository->getAll();
        
        return new HtmlResponse($this->template->render('admin::home-page', $data));
    }
}
