<?php
namespace Helper;

/**
 * Redis 助手类
 * Class RedisHelper
 * @package Helper
 */
class RedisHelper
{
    /**
     * 获取，若无数据则从getter取
     * @param $key
     * @param \Closure $getter
     * @param null $timeout 存储的时间
     * @return mixed
     */
    public static function get($key, \Closure $getter = null, $timeout = null)
    {
        $redis = redis();

        if ($redis->exists($key)) {
            //return $redis->get($key);
        }

        if ($getter !== null) {
            $result = $getter();
            $redis->setex($key, $timeout === null ? 0 : $timeout, $result);
            return $result;
        }

        return null;
    }

    public static function hget($key, \Closure $getter = null)
    {
        $redis = redis();


    }

}