<?php

require_once __DIR__.'/../Common.php';

class Test_Http_Request extends Test_Common
{
    public function test_ignore_errors_がONならレスポンスが40x系でもレスポンスを取得できる()
    {
        $request = new Bolster\Http\Request();
        $response = $request->get('http://localhost:'.MOCK_SERVER_PORT.'/404.php', [1]);

        $this->assertTrue(!is_null($response));
    }

    public function test_ignore_errors_がOFFならレスポンスが40x系だとエラーが発生する()
    {
        $request = new Bolster\Http\Request();
        $request->setHttpContextOptions('ignore_errors', false);

        $this->setExpectedException('PHPUnit_Framework_Error');
        $request->get('http://localhost:'.MOCK_SERVER_PORT.'/404.php', [1]);
    }

    public function test_get_GETで送信した値を送信できている()
    {
        $request  = new Bolster\Http\Request();
        $response = $request->get('http://localhost:'.MOCK_SERVER_PORT, ['hoge' => 'get']);

        $response_json = json_decode($response, true);

        $this->assertEquals('get', $response_json['get']['hoge']);
    }

    public function test_post_POSTで送信した値を送信できている()
    {
        $request  = new Bolster\Http\Request();
        $response = $request->post('http://localhost:'.MOCK_SERVER_PORT, ['hoge' => 'post']);

        $response_json = json_decode($response, true);

        $this->assertEquals('post', $response_json['post']['hoge']);
    }

    public function test_put_PUTで送信した値を送信できている()
    {
        $request  = new Bolster\Http\Request();
        $response = $request->put('http://localhost:'.MOCK_SERVER_PORT, ['hoge' => 'put']);

        $response_json = json_decode($response, true);

        $this->assertEquals('put', $response_json['put']['hoge']);
    }

    public function test_delete_DELETEで送信した値を送信できている()
    {
        $request  = new Bolster\Http\Request();
        $response = $request->delete('http://localhost:'.MOCK_SERVER_PORT, ['hoge' => 'delete']);

        $response_json = json_decode($response, true);

        $this->assertEquals('delete', $response_json['delete']['hoge']);
    }

    public function test_patch_PATCHで送信した値を送信できている()
    {
        $request  = new Bolster\Http\Request();
        $response = $request->patch('http://localhost:'.MOCK_SERVER_PORT, ['hoge' => 'patch']);

        $response_json = json_decode($response, true);

        $this->assertEquals('patch', $response_json['patch']['hoge']);
    }
}
