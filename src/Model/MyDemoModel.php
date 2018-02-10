<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/9
 * Time: 19:16
 */

namespace Model;

class MyDemoModel extends BaseModel
{
    const DEMO_TABLE = "demo";

    /**
     * 获取用户信息
     * @param $uid
     * @return bool|mixed
     */
    public static function getDemo($uid)
    {
        return database()->get(
            self::DEMO_TABLE,
            "*",
            ['id' => $uid]
        );
    }
}
