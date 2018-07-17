<?php declare(strict_types=1);

namespace FTC\Trello\Middleware;

use FTC\Trello\Model\CardRepository;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class CreateMemberCard implements MiddlewareInterface
{
    
    /**
     * @var CardRepository
     */
    private $cardRepository;
    
    public function __construct(
        CardRepository $cardRepository
        ) {
            $this->cardRepository = $cardRepository;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        return $handler->handle($request);
    }
    
}
