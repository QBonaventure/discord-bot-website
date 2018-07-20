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
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;


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
    
    /**
     * @var TemplateRendererInterface 
     */
    private $templateRenderer;
    
    public function __construct(GuildRepository $guildRepository, GuildRoleRepository $guildRoleRepository, TemplateRendererInterface $templateTenderer) {
        $this->guildRepository = $guildRepository;
        $this->guildRoleRepository = $guildRoleRepository;
        $this->templateRenderer = $templateTenderer;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $domainName = DomainName::create($request->getHeader('host')[0]);
        $guild = $this->guildRepository->findByDomainName($domainName);
        if (!$guild) {
            $previousUrl = $request->getHeaders()['referer'][0];
            return new HtmlResponse($this->templateRenderer->render("error::404", ['previousUrl' => $previousUrl], 404));
        }
        $everyoneRole = $this->guildRoleRepository->getEveryoneRole($guild->getId());
        
        $request = $request->withAttribute(Guild::class, $guild);
        $request = $request->withAttribute('@everyone', $everyoneRole);

        return  $handler->handle($request);
    }
    
}
