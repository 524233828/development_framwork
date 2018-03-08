<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/13
 * Time: 11:16
 */

namespace Middleware;

use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use ParagonIE\EasyRSA\EasyRSA;
use ParagonIE\EasyRSA\PrivateKey;
use Psr\Http\Message\ServerRequestInterface;

class RSADecryptMiddleware extends Middleware
{
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        // TODO: Implement handle() method.

        //只做解密不做校验
        $key_string = file_get_contents("/media/raid10/htdocs/development_framwork/data/rsa_key/demo");
        $private_key = new PrivateKey($key_string);
        $data = (string)$request->getBody();
        $data = EasyRSA::decrypt($data,$private_key);
        $data = json_decode($data,true);
        if ('GET' === $request->getMethod()) {
            $request->withQueryParams($data);
        } else {
            $request->withParsedBody($data);
        }
        return $next($request);

    }
}