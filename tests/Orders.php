<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

class Orders extends Common {
	const DUMMY_UNIQUE_KEY = '864B7FC9E71CE416';

	function test_all()
	{
		$response = $this->client->orders()->all();
		$this->assertTrue(is_array($response), 'APIを実行できる');
	}

	function test_detail()
	{
		$response = $this->client->orders()->detail(self::DUMMY_UNIQUE_KEY);
		$this->assertTrue(is_array($response), 'APIを実行できる');
	}

	// NOTE: このテストを作動させるたびに受注を作らなければならないためテストがしにくい、よって手動でテストをを行う
	// function test_edit_status() {}
}
