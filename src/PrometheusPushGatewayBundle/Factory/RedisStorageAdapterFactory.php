<?php

namespace Comsave\PrometheusPushGatewayBundle\Factory;

use Prometheus\Storage\Redis;

class RedisStorageAdapterFactory
{
    public static function build(string $redisUrl): Redis
    {
        list($redisHost, $redisPort) = explode(':', $redisUrl);

        return new Redis(
            [
                'host' => $redisHost,
                'port' => (int)$redisPort,
            ]
        );
    }
}