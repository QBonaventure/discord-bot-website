<?php

declare(strict_types=1);

namespace App\Container\Cache;

use FTCBotCore\Db\Cache\RedisDbCache as RedisDbCacheInstance;
use Psr\Container\ContainerInterface;
use RedisClient\RedisClient;
use App\Cache\Client\Redis as CacheClient;

class Redis
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')['cache'];
        $client = new RedisClient($config);
        
        return new CacheClient($client);
    }
}
