<?php

declare(strict_types=1);

namespace App\Container\Middleware;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Middleware\AuthenticationMiddleware;

class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : AuthenticationMiddleware
    {
        $template = $container->get(TemplateRendererInterface::class);
        
        return new AuthenticationMiddleware($template);
    }
}
