<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use Zend\Expressive\Template\TemplateRendererInterface;
use Psr\Http\Server\MiddlewareInterface;


class UserMiddleware implements MiddlewareInterface
{
    
    private $template;
    
    public function __construct(TemplateRendererInterface $template) {
            $this->template = $template;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $response = $handler->handle($request);
        return $response;
    }
    
}
