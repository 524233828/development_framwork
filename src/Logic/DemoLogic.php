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

class DemoLogic extends BaseLogic
{

    public function getDemo($uid)
    {
        $demo = MyDemoModel::getDemo($uid);

        if(empty($demo)){
            DemoException::DemoNotFound();
        }

        return $demo;
    }
}