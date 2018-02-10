<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/10
 * Time: 9:59
 */

namespace Testing;

use FastD\TestCase;

class DemoTest extends TestCase
{
    public function testDemo()
    {
        $request = $this->request('GET', '/demo');
        $request->withQueryParams(["uid"=>1]);
        $response = $this->app->handleRequest($request);
        $result = json_decode((string)$response->getBody(), true);
        $this->equalsJsonResponseHasKey($response, 'data');
        $this->equalsJsonResponseHasKey($response, 'msg');
        $this->equalsJsonResponseHasKey($response, 'code');
        $this->assertEquals(1, $result['code']);
    }
}
