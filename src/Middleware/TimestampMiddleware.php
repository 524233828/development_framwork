<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/14
 * Time: 16:23
 */

namespace Middleware;


use Exception\BaseException;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ServerRequestInterface;

class TimestampMiddleware extends Middleware
{

    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        // TODO: Implement handle() method.

        if ('GET' === $request->getMethod()) {
            $params = $request->getQueryParams();
        } else {
            $params = $request->getParsedBody();
        }

        $timestamp = isset($params['timestamp'])?$params['timestamp']:0;

        //后续把此处做成配置项
        if((time()-$timestamp) > 30)
        {
            BaseException::RequestOvertime();
        }

        return $next($request);
    }
}