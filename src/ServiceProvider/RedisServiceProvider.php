<?php
/**
 * @author xiaoyongbiao
 * @email 781028081@qq.com
 * Date: 2017/8/30
 */

namespace ServiceProvider;

use Service\RedisService;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;

class RedisServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $container->add('redis', new RedisService($container->get('config')->get('redis')));
    }
}
