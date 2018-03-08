<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/14
 * Time: 15:36
 */

namespace Middleware;


use Exception\BaseException;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use ParagonIE\EasyRSA\EasyRSA;
use ParagonIE\EasyRSA\PublicKey;
use Psr\Http\Message\ServerRequestInterface;

class RSAVerifyMiddleware extends Middleware
{

    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        // TODO: Implement handle() method.

        if ('GET' === $request->getMethod()) {
            $params = $request->getQueryParams();
        } else {
            $params = $request->getParsedBody();
        }

        $sign = urldecode($params['sign']);

        unset($params['sign']);

        $unsign_str = http_build_query($params);

        //需要与第三方交换密钥
        $key_string = file_get_contents("/media/raid10/htdocs/development_framwork/data/rsa_key/demo.pub");

        $public_key = new PublicKey($key_string);

        if(EasyRSA::verify($unsign_str, $sign, $public_key))
        {
            $response = $next($request);

            return $response;
        }else{
            BaseException::SignError();
        }
    }

}