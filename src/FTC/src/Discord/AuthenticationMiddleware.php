<?php

declare(strict_types=1);

namespace FTC\Discord;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Discord\OAuth\Discord;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use FTCBotCore\Db\Core;

class AuthenticationMiddleware implements MiddlewareInterface
{
    
    const USER_QUERY = <<<'EOT'
select users.id, users.username, json_agg(guilds_roles.*) as roles
from users
LEFT join users_roles on users_roles.user_id = users.id
LEFT join guilds_roles on guilds_roles.id = users_roles.role_id AND guilds_roles.guild_id = :guild_id
WHERE users.id = :user_id
group by users.id
EOT;
    
    private $template;
    
    private $oauthClient;
    
    private $database;
    
    private $config;
    
    public function __construct(
        TemplateRendererInterface $template,
        Discord $oauthClient,
        \PDO $database,
        array $config
        ) {
            $this->template = $template;
            $this->oauthClient = $oauthClient;
            $this->database = $database;
            $this->config = $config;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if (!$session->has('user')) {
            $params = $request->getQueryParams();
            $authUrl = $this->oauthClient->getAuthorizationUrl();
            $this->template->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'authUrl', $authUrl);
            if ($params['code']) {
                $token = $this->oauthClient->getAccessToken('authorization_code', [
                    'code' => $_GET['code'],
                ]);
                $discordUser = $this->oauthClient->getResourceOwner($token);
                
                $user = $this->getUserFromDb((int) $discordUser->toArray()['id'], (int) $this->config['guild_id']);
                $session->set('user', $user);
            }
        } else {
            $this->template->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'username', $session->get('user')['username']);
        }
        
        $response = $handler->handle($request); 
        
        return $response;
    }
    
    private function getUserFromDb(int $userId, int $guildId)
    {
        $stmt = $this->database->prepare(self::USER_QUERY);
        $stmt->bindParam('user_id', $userId, \PDO::PARAM_STR);
        $stmt->bindParam('guild_id', $guildId, \PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($result) {
            $roles = json_decode($result['roles'], true);
            $result['roles'] = null;
            foreach ($roles as $key => $role) {
                if ($role) {
                    $result['roles'][$role['id']] = $role['name'];
                }
            }
        }
        
        return $result;
    }
}
