<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Diactoros\Response\HtmlResponse;

class LoginHandler implements MiddlewareInterface
{
    
    private $template;
    
    private $config;
    
    public function __construct(
        TemplateRendererInterface $template,
        array $config
        ) {
            $this->template = $template;
            $this->config = $config;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        
//         $provider = new \Discord\OAuth\Discord([
//             'clientId' => '432289557308768266',
//             'clientSecret' => '23t1-FsHwVuUy6j-BFlFQD7gyHpg292r',
//             'redirectUri'  => 'http://localhost/',
//         ]);
//         $params = $request->getQueryParams();
        
//         if (!$params['code']) {
//             $data['authUrl'] = $provider->getAuthorizationUrl();
//             return new HtmlResponse($this->template->render('app::login-page', $data));
//         } else {
//             echo $token;
//             $token = $provider->getAccessToken('authorization_code', [
//                 'code' => $_GET['code'],
//             ]);
//             // Get the user object.
//             $user = $provider->getResourceOwner($token);
            
//             // Get the guilds and connections.
//             $guilds = $user->guilds;
            
//             $connections = $user->connections;
            
//             // Accept an invite
//             $invite = $user->acceptInvite('https://discord.gg/0SBTUU1wZTUo9F8v');
            
//             // Get a refresh token
//             $refresh = $provider->getAccessToken('refresh_token', [
//                 'refresh_token' => $getOldTokenFromMemory->getRefreshToken(),
//             ]);
//             var_dump($token);
//             var_dump($user);
//             echo 'Hello '.$user->toArray()['username'];
            // Store the new token.
//         }
        
//         $response = $handler->handle($request); 
        
//         return $response;
    }
}
