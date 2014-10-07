<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

class Savings extends Common
{
	function test_all()
	{
		$response = $this->client->savings()->all();
		$this->assertTrue(is_array($response), 'APIが実行できる');
	}
}
