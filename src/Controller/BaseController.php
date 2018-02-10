<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/16
 * Time: 22:09
 */

namespace Controller;

use Exception\BaseException;
use Helper\DocParser;
use Service\ApiResponse;
use Constant\ErrorCode;

/**
 * 控制层，后续在此扩展对入参、出参的过滤与控制
 * 注释参数过滤规定：数组用[]表示，index[]，表示index字段是个数组
 * 示例：
 * [].index，则返回的data为数组，如[0:{index:""},1:{index:""}]
 * index[].a,则返回的data中有index数组字段，index中有a参数，如：{index:[0:{a:""},1:{a:""}]}
 * 注意，上述例子不能写成index.[].a
 * Class BaseController
 * @package Controller
 */
class BaseController
{
    /**
     * @param $data
     * @param $isFilter //是否进行参数过滤
     * @param string $msg
     * @param int $code
     * @param int $status
     * @return ApiResponse
     */
    public function response($data, $isFilter = false, $msg = '', $code = ErrorCode::OK, $status = 200)
    {
        $data = $isFilter?$this->responseFilter($data):$data;
        $msg = ErrorCode::msg($code) ? ErrorCode::msg($code) : $msg;
        $status = ErrorCode::status($code) ? ErrorCode::status($code) : $status;
        return new ApiResponse($msg, $code, $data, $status);
    }

    /**
     * 根据当前方法的注释进行参数过滤
     * @param $data
     * @return array
     */
    private function responseFilter($data)
    {
        try {
            $route_callback = route()->getActiveRoute()->getCallback();
            list($callbackController, $callbackMethod) = explode('@', $route_callback);
            $parameters = $this->_genDoc($callbackController, $callbackMethod);
            if (!$parameters||count($parameters)<1) {
                return $data;
            }

            $response_data = [];
            foreach ($parameters as $param) {
                $this->filterHandler($data, $param, $response_data);
            }

            return $response_data;
        } catch (\Exception $e) {
            BaseException::SystemError();
        }
    }

    /**
     * 参数过滤处理方法
     * @param $data
     * @param $param
     * @param array $response_data
     * @return string
     */
    private function filterHandler($data, $param, &$response_data = [])
    {
        $list = explode('.', $param);

        $param_list = array_shift($list);

        if (false===strpos($param_list, "[]")&&count($list)>0) {
            $this->filterHandler($data[$param_list], implode(".", $list), $response_data[$param_list]);
        } elseif (false!==strpos($param_list, "[]")&&count($list)>0) {
            $key = str_replace("[]", "", $param_list);

            if ($key=="") {
                foreach ($data as $k => $v) {
                    $this->filterHandler($data[$k], implode(".", $list), $response_data[$k]);
                }
            } else {
                if (isset($data[$key])) {
                    foreach ($data[$key] as $k => $v) {
                        $this->filterHandler($data[$key][$k], implode(".", $list), $response_data[$key][$k]);
                    }
                } else {
                    return $response_data = $data;
                }
            }
        } elseif (false===strpos($param_list, "[]")&&count($list)==0) {
            return $response_data[$param_list] = isset($data[$param_list])?$data[$param_list]:"";
        } else {
            BaseException::SystemError();
        }
    }

    /**
     * 获取响应参数
     * @param $callbackController
     * @param $callbackMethod
     * @return array
     */
    private function _genDoc($callbackController, $callbackMethod)
    {
        $reflect = new \ReflectionClass($callbackController);
        $method = $reflect->getMethod($callbackMethod);

        $docParser = new DocParser();
        $info = $docParser->parse($method->getDocComment());

        $data = [];

        if (!empty($info['returnParam'])) {
            if (!is_array($info['returnParam'])) {
                list($name, $type, $text) = explode('|', $info['returnParam']);
                $data[] = $name;
            } else {
                foreach ($info['returnParam'] as $return) {
                    list($name, $type, $text) = explode('|', $return);
                    $data[] = $name;
                }
            }
        }

        return $data;
    }
}
