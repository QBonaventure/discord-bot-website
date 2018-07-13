<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use FTC\Discord\Model\Aggregate\GuildRepository;
use FTC\Discord\Model\ValueObject\DomainName;
use FTC\Discord\Model\Aggregate\Guild;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;


class GuildSetupMiddleware implements MiddlewareInterface
{
    
    /**
     * @var GuildRepository $guildRepository
     */
    private $guildRepository;
    
    /**
     * @var GuildRoleRepository
     */
    private $guildRoleRepository;
    
    public function __construct(GuildRepository $guildRepository, GuildRoleRepository $guildRoleRepository) {
        $this->guildRepository = $guildRepository;
        $this->guildRoleRepository = $guildRoleRepository;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $domainName = DomainName::create($request->getHeader('host')[0]);
        $guild = $this->guildRepository->findByDomainName($domainName);
        $everyoneRole = $this->guildRoleRepository->getEveryoneRole($guild->getId());
        
        $request = $request->withAttribute(Guild::class, $guild);
        $request = $request->withAttribute('@everyone', $everyoneRole);

        return  $handler->handle($request);
    }
    
}
