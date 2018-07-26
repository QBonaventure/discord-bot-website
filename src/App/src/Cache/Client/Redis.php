<?php

declare(strict_types=1);

namespace App\Cache\Client;

use App\Cache\WebsiteCacheInterface;
use RedisClient\RedisClient;

class Redis implements WebsiteCacheInterface
{
    
    /**
     * @var RedisClient
     */
    private $client;
    
    public function __construct($client)
    {
        $this->client = $client;
    }
    
    
    public function setLoginRedirectUrl($state, $redirectUrl) : bool
    {
        return $this->client->set($state, $redirectUrl);
    }
    
    public function getLoginRedirectUrl($state) : string
    {
        return $this->client->get($state);
    }
    
}
