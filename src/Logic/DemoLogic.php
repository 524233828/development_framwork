<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/9
 * Time: 19:15
 */

namespace Logic;

use Exception\DemoException;
use Model\MyDemoModel;
use Symfony\Component\Config\Definition\Exception\Exception;

class DemoLogic extends BaseLogic
{

    public function getDemo($uid)
    {
        $log = myLog('demo');
        $log->addDebug("开始获取demo");
        $log->addDebug("uid:".$uid);

        $demo = MyDemoModel::getDemo($uid);
        $log->addDebug("demo:" . json_encode($demo));

        if (empty($demo)) {
            DemoException::DemoNotFound();
        }

        return $demo;
    }
}
