<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

class Users extends Common {
	private $client;

	public function setUp()
	{
		$this->client = new \Bolster\BaseApi\Client([
			'client_id' => CLIENT_ID,
			'client_secret' => CLIENT_SECRET,
			'redirect_uri' => REDIRECT_URI,
			'access_token' => ACCESS_TOKEN,
			'refresh_token' => REFRESH_TOKEN,
		]);

		$mock = new MockHttpClient();
		$mock->setParser(new \Bolster\Http\Parser\JsonParser());

		$this->client->setHttpClient($mock);
	}

	function test_me() {
		// NOTE: 特にテストすべき事項がないので、実行できることだけを検証する
		$user = $this->client->users()->me();
		$this->assertTrue(is_array($user), '戻り値が連想配列');
	}
}
