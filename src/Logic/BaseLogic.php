<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/18
 * Time: 13:32
 */

namespace Logic;

/**
 * 逻辑层，做逻辑处理进行数据组装，供控制层调用，单例模式
 * Class BaseLogic
 * @package Logic
 */
class BaseLogic
{

    private static $instance;

    final private function __construct()
    {
    }

    /**
     * 获取当前对象实例
     * @return $this
     */
    public static function getInstance()
    {
        $class_name = get_called_class();
        if (!isset(self::$instance[$class_name]) || !self::$instance[$class_name] instanceof self) {
            self::$instance[$class_name] = new static;
        }

        return self::$instance[$class_name];
    }
}
