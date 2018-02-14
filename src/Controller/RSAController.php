<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/13
 * Time: 21:04
 */

namespace Controller;


use FastD\Http\ServerRequest;

class RSAController extends BaseController
{

    public function test(ServerRequest $request){

        $body = $request->getParsedBody();
        return $this->response($body);
    }
}