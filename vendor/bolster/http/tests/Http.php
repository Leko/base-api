<?php

require_once __DIR__.'/Common.php';

class Test_Http extends Test_Common
{
    public function test___constructを呼ぶとrequest_responseのインスタンスを保持している()
    {
        $request  = $this->getProperty('\Bolster\Http', '_request');
        $response = $this->getProperty('\Bolster\Http', '_response');

        $http = new \Bolster\Http();
        $this->assertInstanceOf('\Bolster\Http\Request', $request->getValue($http));
        $this->assertInstanceOf('\Bolster\Http\Response', $response->getValue($http));
    }

    public function test_get_GETで値を送信できる()
    {
        $params = ['foo' => 'bar'];
        $http = new \Bolster\Http();
        $http->setParser(new \Bolster\Http\Parser\JsonParser());
        $response = $http->get('http://localhost:'.MOCK_SERVER_PORT, $params);

        $this->assertEquals($params['foo'], $response['get']['foo']);
    }

    public function test_post_POSTで値を送信できる()
    {
        $params = ['foo' => 'bar'];
        $http = new \Bolster\Http();
        $http->setParser(new \Bolster\Http\Parser\JsonParser());
        $response = $http->post('http://localhost:'.MOCK_SERVER_PORT, $params);

        $this->assertEquals($params['foo'], $response['post']['foo']);
    }

    public function test_put_PUTで値を送信できる()
    {
        $params = ['foo' => 'bar'];
        $http = new \Bolster\Http();
        $http->setParser(new \Bolster\Http\Parser\JsonParser());
        $response = $http->put('http://localhost:'.MOCK_SERVER_PORT, $params);

        $this->assertEquals($params['foo'], $response['put']['foo']);
    }

    public function test_delete_DELETEで値を送信できる()
    {
        $params = ['foo' => 'bar'];
        $http = new \Bolster\Http();
        $http->setParser(new \Bolster\Http\Parser\JsonParser());
        $response = $http->delete('http://localhost:'.MOCK_SERVER_PORT, $params);

        $this->assertEquals($params['foo'], $response['delete']['foo']);
    }

    public function test_patch_PATCHで値を送信できる()
    {
        $params = ['foo' => 'bar'];
        $http = new \Bolster\Http();
        $http->setParser(new \Bolster\Http\Parser\JsonParser());
        $response = $http->patch('http://localhost:'.MOCK_SERVER_PORT, $params);

        $this->assertEquals($params['foo'], $response['patch']['foo']);
    }
}
