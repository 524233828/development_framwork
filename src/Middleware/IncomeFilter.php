<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/26
 * Time: 23:23
 */

namespace Middleware;

use Exception\BaseException;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Helper\DocParser;
use Psr\Http\Message\ServerRequestInterface;

class IncomeFilter extends Middleware
{
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        // TODO: Implement handle() method.

        if ($request->getMethod()==="GET") {
            $data = $request->getQueryParams();
        } else {
            $data = $request->getParsedBody();
        }
        if ($this->requestFilter($data)) {
            return $next($request);
        } else {
            BaseException::ParamsError();
        }
    }

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     */
    private function requestFilter($data)
    {
        try {
            $route_callback = route()->getActiveRoute()->getCallback();
            list($callbackController, $callbackMethod) = explode('@', $route_callback);
            $parameters = $this->_genDoc($callbackController, $callbackMethod);
            if (!$parameters||count($parameters)<1) {
                return true;
            }

            $response_data = [];
            foreach ($parameters as $param) {
                if ($param['must']=="true") {
                    $this->filterHandler($data, $param['name'], $param['type']);
                }
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $data
     * @param $param
     * @param $type
     * @return bool
     */
    private function filterHandler($data, $param, $type)
    {
        $list = explode('.', $param);

        $param_list = array_shift($list);

        if (false===strpos($param_list, "[]")&&count($list)>0) {
            return $this->filterHandler($data[$param_list], implode(".", $list), $type);
        } elseif (false!==strpos($param_list, "[]")&&count($list)>0) {
            $key = str_replace("[]", "", $param_list);

            if ($key=="") {
                foreach ($data as $k => $v) {
                    return $this->filterHandler($data[$k], implode(".", $list), $type);
                }
            } else {
                if (isset($data[$key])) {
                    foreach ($data[$key] as $k => $v) {
                        return $this->filterHandler($data[$key][$k], implode(".", $list), $type);
                    }
                } else {
                    return BaseException::ParamsMissing();
                }
            }
        } elseif (false===strpos($param_list, "[]")&&count($list)==0) {
            if (!isset($data[$param_list])) {
                BaseException::ParamsMissing();
            }

            if (!$this->typeCheck($data[$param_list], $type)) {
                BaseException::ParamsError();
            }

            return true;
        } else {
            BaseException::SystemError();
        }
    }

    /**
     * @param $data
     * @param $type
     * @return bool|int
     */
    private function typeCheck($data, $type)
    {
        switch ($type) {
            case "int":
                return $this->intValidate($data);
                break;
            case "string":
                return $this->stringValidate($data);
                break;
            case "float":
                return $this->floatValidate($data);
                break;
            case "file":
                return true;
            default:
                return false;
        }
    }

    /**
     * @param $data
     * @return int
     */
    private function intValidate($data)
    {
        return preg_match("/^\d{1,}$/", $data);
    }

    /**
     * @param $data
     * @return bool
     */
    private function stringValidate($data)
    {
        if (empty($data)) {
            return false;
        }

        return !preg_match("/(\\|\"){1,}/", $data);
    }

    /**
     * @param $data
     * @return int
     */
    private function floatValidate($data)
    {
        return preg_match("/^\d{1,}\.{1}\d{0,2}/", $data);
    }

    /**
     * @param $callbackController
     * @param $callbackMethod
     * @return array
     */
    private function _genDoc($callbackController, $callbackMethod)
    {
        $data = [];

        $reflect = new \ReflectionClass($callbackController);
        $method = $reflect->getMethod($callbackMethod);

        $docParser = new DocParser();
        $info = $docParser->parse($method->getDocComment());

        if (!empty($info['apiParam'])) {
            $data = [];

            if (!is_array($info['apiParam'])) {
                list($name, $type, $text, $must) = explode('|', $info['apiParam']);
                $data[] = [
                    'name' => $name,
                    'type' => $type,
                    'text' => $text,
                    'must' => $must
                ];
            } else {
                foreach ($info['apiParam'] as $api) {
                    list($name, $type, $text, $must) = explode('|', $api);
                    $data[] = [
                        'name' => $name,
                        'type' => $type,
                        'text' => $text,
                        'must' => $must
                    ];
                }
            }
        }

        return $data;
    }
}
