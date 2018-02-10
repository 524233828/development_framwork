<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/9
 * Time: 19:05
 */

namespace Controller;

use FastD\Http\ServerRequest;
use Logic\DemoLogic;

class DemoController extends BaseController
{

    /**
     * @name demo
     * @apiParam uid|int|用户ID|true
     * @returnParam name|string|用户名称
     * @returnParam sex|int|性别
     * @returnParam phone|string|手机号码
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function demo(ServerRequest $request)
    {
        $uid = $request->getParam("uid");
        return $this->response(DemoLogic::getInstance()->getDemo($uid), true);
    }
}
