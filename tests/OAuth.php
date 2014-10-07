<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

class OAuth extends Common {
	function test_authorize() {
		$url = $this->client->oauth()->authorize();
		$parsed = parse_url($url);
		parse_str($parsed['query'], $query);

		$this->assertEquals('https', $parsed['scheme'], 'URLのプロトコルはhttps');
		$this->assertEquals('api.thebase.in', $parsed['host'], 'URLのホストはapi_thebase_in');
		$this->assertArrayHasKey('response_type', $query, 'URLにresponse_typeが含まれている');
		$this->assertArrayHasKey('client_id', $query, 'URLにclient_idが含まれている');
		$this->assertArrayHasKey('redirect_uri', $query, 'URLにredirect_uriが含まれている');
	}

	function test_refresh() {
		$oauth_client = $this->client->oauth();

		$before_access_token  = $oauth_client->getConfig('access_token');
		$before_refresh_token = $oauth_client->getConfig('refresh_token');

		$credentials = $oauth_client->refresh();

		$after_access_token  = $oauth_client->getConfig('access_token');
		$after_refresh_token = $oauth_client->getConfig('refresh_token');

		$this->assertArrayHasKey('access_token', $credentials, 'アクセストークンが返却される');
		$this->assertArrayHasKey('refresh_token', $credentials, 'リフレッシュトークンが返却される');

		$this->assertTrue($before_access_token !== $after_access_token, 'プロパティのアクセストークンが自動的に更新される');
		$this->assertEquals($credentials['access_token'], $after_access_token, '戻り値とプロパティのアクセストークンが同じ');

		$this->assertTrue($before_refresh_token !== $after_refresh_token, 'プロパティのリフレッシュトークンが自動的に更新される');
		$this->assertEquals($credentials['refresh_token'], $after_refresh_token, '戻り値とプロパティのリフレッシュトークンが同じ');
	}
}
