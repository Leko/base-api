<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

class MockHttpClient extends \Bolster\Http implements \Bolster\BaseApi\HttpRequestable {}

class OAuth extends Common {
	private $client;

	public function setUp()
	{
		$this->client = new \Bolster\BaseApi\Client([
			'client_id' => CLIENT_ID,
			'client_secret' => CLIENT_SECRET,
			'redirect_uri' => REDIRECT_URI,
		]);
		$this->client->setHttpClient(new MockHttpClient());
	}

	function test_authorize_戻り値のURLのプロトコルはhttps() {
		$url = $this->client->oauth()->authorize();
		$parsed = parse_url($url);

		$this->assertEquals('https', $parsed['scheme']);
	}
	function test_authorize_戻り値のURLのホストはapi_thebase_in() {
		$url = $this->client->oauth()->authorize();
		$parsed = parse_url($url);

		$this->assertEquals('api.thebase.in', $parsed['host']);
	}
	function test_authorize_戻り値のURLにresponse_typeが含まれている() {
		$url = $this->client->oauth()->authorize();
		$parsed = parse_url($url);
		parse_str($parsed['query'], $query);

		$this->assertArrayHasKey('response_type', $query);
	}
	function test_authorize_戻り値のURLにclient_idが含まれている() {
		$url = $this->client->oauth()->authorize();
		$parsed = parse_url($url);
		parse_str($parsed['query'], $query);

		$this->assertArrayHasKey('client_id', $query);
	}
	function test_authorize_戻り値のURLにredirect_uriが含まれている() {
		$url = $this->client->oauth()->authorize();
		$parsed = parse_url($url);
		parse_str($parsed['query'], $query);

		$this->assertArrayHasKey('redirect_uri', $query);
	}
}
