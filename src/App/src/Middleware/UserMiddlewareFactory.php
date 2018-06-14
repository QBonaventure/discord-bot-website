<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class UserMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : UserMiddleware
    {
        $template = $container->get(TemplateRendererInterface::class);

        return new UserMiddleware($template);
    }
}
