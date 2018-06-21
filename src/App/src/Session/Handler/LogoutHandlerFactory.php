<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class LogoutHandlerFactory
{
    public function __invoke(ContainerInterface $container) : LoginHandler
    {
        $template = $container->get(TemplateRendererInterface::class);
        $config = $container->get('config')->offsetGet('session');
        
        return new LoginHandler($template, $config);
    }
}
