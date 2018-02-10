<?php
/**
 * Created by PhpStorm.
 * User: xiaoyongbiao
 * Date: 2017/8/28
 * Time: 下午4:07
 */
namespace Service;

use FastD\Http\JsonResponse;

/**
 * 项目标准api相应
 * Class ApiResponse
 * @package Component
 */
class ApiResponse extends JsonResponse
{

    public function __construct($msg, $code, $data, $status = 200, array $headers = [])
    {
        $this->withAddedHeader("Access-Control-Allow-Origin", config()->get('front_url'));

        $result = [];

        $result['data'] = !is_array($data) && !is_null(json_decode($data)) ? json_decode($data, true) : $data;
        $result['msg'] = $msg;
        $result['code'] = $code;

        parent::__construct($result, $status, $headers);
    }
}
