<?php declare(strict_types=1);

namespace FTC\Trello\Container;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Trello\Client;

class ClientFactory
{
    public function __invoke(ContainerInterface $container) : Client
    {
        $httpClient = new \GuzzleHttp\Client();
        $config = $container->get('config')->offsetGet('trello'); 
        
        return new Client($httpClient, $config);
    }
}
