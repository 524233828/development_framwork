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
        $response = $this->app->handleRequest($request);

        $this->equalsJsonResponseHasKey($response, 'name');
        $this->equalsJsonResponseHasKey($response, 'sex');
        $this->equalsJsonResponseHasKey($response, 'phone');
    }
}
