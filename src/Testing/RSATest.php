<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/13
 * Time: 21:02
 */

namespace Testing;


use FastD\Http\Stream;
use FastD\TestCase;
use ParagonIE\EasyRSA\EasyRSA;
use ParagonIE\EasyRSA\PrivateKey;
use ParagonIE\EasyRSA\PublicKey;

class RSATest extends TestCase
{
    public function testRSA()
    {
        $request = $this->request('POST', '/test/rsa');

        $data = ["num_demo"=>123,"string_demo"=>"hello",'timestamp'=>time()];

        $key_string = file_get_contents("/media/raid10/htdocs/development_framwork/data/rsa_key/demo");
        $private_key = new PrivateKey($key_string);

        $data['sign'] = urlencode(EasyRSA::sign(md5(http_build_query($data)), $private_key));

        $data = json_encode($data);

        $public_key = file_get_contents("/media/raid10/htdocs/development_framwork/data/rsa_key/demo.pub");

        $public_key = new PublicKey($public_key);

        $data = EasyRSA::encrypt($data,$public_key);

        $stream = new Stream("data://text/plain,".urlencode($data));

        $request->withBody($stream);
        $response = $this->app->handleRequest($request);

        var_dump((string)$response->getBody());

    }
}
