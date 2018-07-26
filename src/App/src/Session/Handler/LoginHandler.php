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
use Zend\Diactoros\Response\RedirectResponse;
use FTC\Discord\Model\ValueObject\Snowflake\UserId;
use FTC\Discord\Model\ValueObject\Snowflake\GuildId;
use FTC\Discord\Model\Aggregate\GuildRepository;
use FTC\Discord\Model\Aggregate\GuildMember;
use FTC\Discord\Model\Aggregate\GuildMemberRepository;
use RedisClient\RedisClient;
use App\Cache\WebsiteCacheInterface;
use function GuzzleHttp\Psr7\parse_query;

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
    
    /**
     * @var WebsiteCacheInterface
     */
    private $cache;
    
    
    public function __construct(
        GuildMemberRepository $userRepo,
        Discord $oauthClient,
        WebsiteCacheInterface $cache,
        array $config
        ) {
            $this->oauthClient = $oauthClient;
            $this->config = $config;
            $this->userRepo = $userRepo;
            $this->cache = $cache;
    }
    
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $params = $request->getQueryParams();

        $redirectRoute = $request->getCookieParams()[self::REFERER_COOKIE_NAME]
            ?? $request->getHeaders()['referer'][0]
            ?? self::REDIRECT_ROUTE;
        
        if ($params['code'] && $redirectRoute = $this->cache->getLoginRedirectUrl($params['state'])) {
            $user = $this->getLoggedInUser($params['code']);
            $session->set('user', $user->toArray());

            return new RedirectResponse($redirectRoute);
        }
        
        $state = bin2hex(random_bytes(32));
        $url = $this->getAuthorizationUrl($state);

        $this->cache->setLoginRedirectUrl($state, $redirectRoute);
        
        return new RedirectResponse($url);
    }
    
    private function getAuthorizationUrl($state)
    {
        return $this->oauthClient->getAuthorizationUrl(['state' => $state, 'redirectUri' => "lkjkjkljkljkljlkjlkj"]);
    }
    
    
    private function getLoggedInUser(string $code) : GuildMember
    {
        $token = $this->oauthClient->getAccessToken('authorization_code', [
            'code' => $code,
            'client_id'     => $this->config['discord_oauth']['clientId'],
            'redirectUri' => "oooooooooooooooooooo",
        ]);
        
        $discordUser = $this->oauthClient->getResourceOwner($token);
        $userId = UserId::create((int) $discordUser->toArray()['id']);
        $user = $this->userRepo->getById($userId);
        
        return $user;
    }
    
}
