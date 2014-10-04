<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

/**
 * BASE APIクライアントに渡す、HTTP通信のモッククライアント。
 * 
 * 実際にはHTTP通信を行わず、与えられたURL(文字列)によりそのまま連想配列を返却する
 */
class MockHttpRequester implements \Bolster\BaseApi\HttpRequestable
{
	/**
	 * 渡されたパラメータをそのまま返却する
	 * @var string
	 */
	const PATH_ECHO = '/echo';

	/**
	 * BASE APIから返ってくるアクセストークン切れエラーを模したレスポンスを返す
	 * @var string
	 */
	const PATH_ERROR_ACCESS_TOKEN = '/error/access_token';

	/**
	 * BASE APIから返ってくるAPI使用回数制限エラーを模したレスポンスを返す
	 * @var string
	 */
	const PATH_ERROR_RATE_LIMIT = '/error/rate_limit';

	/**
	 * BASE APIから返ってくるエラー(上記2つに当てはまらない)を模したレスポンスを返す
	 * @var string
	 */
	const PATH_ERROR = '/error';

	/**
	 * HTTP通信を行わず、BASE APIのレスポンスを模した連想配列返す
	 * 
	 * @param string $method HTTPメソッド(使用しない)
	 * @param string $url    リクエストによって振り分けるURL
	 * @param array  $param  APIに渡されるパラメータ
	 * @return array BASE APIからのレスポンスを模した連想配列
	 */
	private function request($method, $url, $params) {
		if(strpos($url, self::PATH_ECHO) !== false) {
			$response = $params;
		} elseif(strpos($url, self::PATH_ERROR_ACCESS_TOKEN) !== false) {
			$response = [
				'error' => 'invalid_request',
				'error_description' => \Bolster\BaseApi\Client::ERROR_EXPIRED_ACCESS_TOKEN,
			];
		} elseif(strpos($url, self::PATH_ERROR_RATE_LIMIT) !== false) {
			$response = [
				'error' => 'temporarily_unavailable',
				'error_description' => \Bolster\BaseApi\Client::ERROR_RATE_LIMIT_EXCEED,
			];
		} elseif(strpos($url, self::PATH_ERROR) !== false) {
			$response = [
				'error' => 'some_error',
				'error_description' => 'some error occured!!',
			];
		}

		return $response;
	}

	// NOTE: abstract
	public function get($url, array $params = array()) {
		return $this->request('get', $url, $params);
	}
	// NOTE: abstract
	public function post($url, array $params = array()) {
		return $this->request('post', $url, $params);
	}
	// NOTE: abstract
	public function put($url, array $params = array()) {
		return $this->request('put', $url, $params);
	}
	// NOTE: abstract
	public function delete($url, array $params = array()) {
		return $this->request('delete', $url, $params);
	}
	// NOTE: abstract
	public function setHeaders($key, $value) {}
}

/**
 * BaseApi\Clientクラスのテスト
 */
class Client extends Common {
	private $config = [
		'client_id'     => CLIENT_ID,
		'client_secret' => CLIENT_SECRET,
		'redirect_uri'  => REDIRECT_URI,
		'scopes'        => ['hoge', 'foo', 'bar'],
		'access_token'  => ACCESS_TOKEN,
		'refresh_token' => REFRESH_TOKEN,
	];

	function test___construct() {
		$client = new \Bolster\BaseApi\Client($this->config);

		foreach($this->config as $key => $value) {
			$this->assertEquals($client->getConfig($key), $value, "editable_propertiesに定義された{$key}がセット可能");
		}
	}

	function test_setConfig() {
		$client = new \Bolster\BaseApi\Client();

		foreach($this->config as $key => $value) {
			$client->setConfig($key, $value);
			$this->assertEquals($client->getConfig($key), $value, 'key-value形式で設定ができる');
		}

		// 許可されてないキーを指定すると例外をスローする
		$this->setExpectedException('\Exception');
		$client->setConfig('hogehoge', 'hoge-foo-bar');
	}

	function test_getConfig() {
		$client = new \Bolster\BaseApi\Client();

		$config = $client->getConfig();
		foreach($config as $key => $value) {
			$this->assertEquals($client->getConfig($key), $config[$key], '引数を省略すると全件取得');
		}

		// 許可されてないキーを指定すると例外をスローする
		$this->setExpectedException('\Exception');
		$client->getConfig('hogehoge');
	}

	function test_request_レスポンスがJSON文字列の場合() {
		$client  = new \Bolster\BaseApi\Client();
		$request = $this->getMethod('\Bolster\BaseApi\Client', 'request');

		$client->setHttpClient(new MockHttpRequester());
		$response = $request->invokeArgs($client, ['get', MockHttpRequester::PATH_ECHO]);

		$this->assertTrue(is_array($response));
	}
	function test_request_レスポンスにerrorが含まれている場合() {
		$client  = new \Bolster\BaseApi\Client();
		$request = $this->getMethod('\Bolster\BaseApi\Client', 'request');

		$client->setHttpClient(new MockHttpRequester());

		// アクセストークン切れならExpiredAccessTokenExceptionをスローする
		$client->setConfig('access_token', 'dummy');
		$this->setExpectedException('\Bolster\BaseApi\ExpiredAccessTokenException');
		$request->invokeArgs($client, ['get', MockHttpRequester::PATH_ERROR_ACCESS_TOKEN]);

		// 使用制限切れならRateLimitExceedExceptionをスローする
		// $this->setExpectedException('\Bolster\BaseApi\RateLimitExceedException');
		// $request->invokeArgs($client, ['get', MockHttpRequester::PATH_ERROR_RATE_LIMIT]);

		// それ以外ならBaseApiExceptionをスローする
		// $this->setExpectedException('\Bolster\BaseApi\BaseApiException');
		// $request->invokeArgs($client, ['get', MockHttpRequester::PATH_ERROR]);
	}
	function test_request_httpがNULLの状態でrequestをコールすると例外を発生する() {
		$client  = new \Bolster\BaseApi\Client();
		$request = $this->getMethod('\Bolster\BaseApi\Client', 'request');

		$this->setExpectedException('\RuntimeException');
		$request->invokeArgs($client, ['get', 'hoge']);
	}

	function test_oauth() {
		$instance = $this->client->oauth();
		$this->assertInstanceOf('\Bolster\BaseApi\OAuth', $instance, '戻り値はOAuthのインスタンス');

		$this->assertEquals($this->client->getConfig(), $instance->getConfig(), 'インスタンスにclientの設定が引き継がれている');
	}

	function test_users() {
		$instance = $this->client->users();
		$this->assertInstanceOf('\Bolster\BaseApi\Users', $instance, '戻り値はUsersのインスタンス');

		$this->assertEquals($this->client->getConfig(), $instance->getConfig(), 'インスタンスにclientの設定が引き継がれている');
	}

	function test_items() {
		$instance = $this->client->items();
		$this->assertInstanceOf('\Bolster\BaseApi\Items', $instance, '戻り値はItemsのインスタンス');

		$this->assertEquals($this->client->getConfig(), $instance->getConfig(), 'インスタンスにclientの設定が引き継がれている');
	}

	function test_categories() {
		$instance = $this->client->categories();
		$this->assertInstanceOf('\Bolster\BaseApi\Categories', $instance, '戻り値はCategoriesのインスタンス');

		$this->assertEquals($this->client->getConfig(), $instance->getConfig(), 'インスタンスにclientの設定が引き継がれている');
	}

	function test_itemcategories() {
		$instance = $this->client->itemcategories();
		$this->assertInstanceOf('\Bolster\BaseApi\ItemCategories', $instance, '戻り値はItemCategoriesのインスタンス');

		$this->assertEquals($this->client->getConfig(), $instance->getConfig(), 'インスタンスにclientの設定が引き継がれている');
	}

	function test_orders() {
		$instance = $this->client->orders();
		$this->assertInstanceOf('\Bolster\BaseApi\Orders', $instance, '戻り値はOrdersのインスタンス');

		$this->assertEquals($this->client->getConfig(), $instance->getConfig(), 'インスタンスにclientの設定が引き継がれている');
	}

	function test_savings() {
		$instance = $this->client->savings();
		$this->assertInstanceOf('\Bolster\BaseApi\Savings', $instance, '戻り値はSavingsのインスタンス');

		$this->assertEquals($this->client->getConfig(), $instance->getConfig(), 'インスタンスにclientの設定が引き継がれている');
	}
}
