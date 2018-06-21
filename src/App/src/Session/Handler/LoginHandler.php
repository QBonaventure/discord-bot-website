<?php

declare(strict_types=1);

namespace App\Session\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Discord\OAuth\Discord;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use FTC\Discord\Model\GuildMemberRepository;
use Zend\Diactoros\Response\RedirectResponse;
use FTC\Discord\Model\GuildMember;

class LoginHandler implements MiddlewareInterface
{
    const REDIRECT_ROUTE = 'home';
    
    const REFERER_COOKIE_EXPIRATION = 30;
    
    const REFERER_COOKIE_NAME = 'login_referer';
    
    /**
     * @var TemplateRendererInterface
     */
    private $template;
    
    /**
     * @var GuildMemberRepository
     */
    private $userRepo;
    
    /**
     * @var Discord
     */
    private $oauthClient;
    
    /**
     * @var array
     */
    private $config;
    
    
    public function __construct(
        GuildMemberRepository $userRepo,
        Discord $oauthClient,
        array $config
        ) {
            $this->oauthClient = $oauthClient;
            $this->config = $config;
            $this->userRepo = $userRepo;
    }
    
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $params = $request->getQueryParams();
        
        $redirectRoute = $request->getCookieParams()[self::REFERER_COOKIE_NAME]
            ?? $request->getHeaders()['referer'][0]
            ?? self::REDIRECT_ROUTE;
        
        if ($params['code']) {
            $this->removeRefererCookie();
            $user = $this->getLoggedInUser($params['code']);
            $session->set('user', $user->toArray());
            
            return new RedirectResponse($redirectRoute);
        }
        
        $this->setRefererCookie($request->getRequestTarget(), $redirectRoute);

        return new RedirectResponse($this->oauthClient->getAuthorizationUrl());
    }
    
    
    private function removeRefererCookie() : void
    {
        unset($_COOKIE[self::REFERER_COOKIE_NAME]);
    }
    
    private function setRefererCookie(string $requestTarget, string $url) : bool
    {
        return setcookie(
            self::REFERER_COOKIE_NAME,
            $url,
            time()+self::REFERER_COOKIE_EXPIRATION,
            $requestTarget
            );
    }
    
    
    private function getLoggedInUser(string $code) : GuildMember
    {
        $token = $this->oauthClient->getAccessToken('authorization_code', [
            'code' => $code,
            'client_id'     => $this->config['discord_oauth']['clientId'],
        ]);
        
        $discordUser = $this->oauthClient->getResourceOwner($token);
        $user = $this->userRepo->getGuildMember((int) $this->config['guild_id'], (int) $discordUser->toArray()['id']);
        
        return $user;
    }
    
}
