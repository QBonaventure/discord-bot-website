<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Discord\OAuth\Discord;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use FTC\Discord\Model\Aggregate\Guild;

class AuthenticationMiddleware implements MiddlewareInterface
{
     /**
      * @var TemplateRendererInterface
      */
    private $template;
    
    
    public function __construct(TemplateRendererInterface $template)
    {
            $this->template = $template;
    }

    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        
        if (!$user = $session->get('user')) {
            $user = [
                'roles' => [$request->getAttribute(Guild::class)->getId()->__toString()],
            ];
        }
        
        $this->template->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'user', $user);
        
        return $handler->handle($request);
    }
    
}
