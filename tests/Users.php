<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

class Users extends Common
{
	function test_me()
    {
		// NOTE: 特にテストすべき事項がないので、実行できることだけを検証する
		$user = $this->client->users()->me();
		$this->assertTrue(is_array($user), '戻り値が連想配列');
	}
}
