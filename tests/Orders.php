<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

class Orders extends Common
{
    function test_all()
    {
        $response = $this->client->orders()->all();
        $this->assertTrue(is_array($response), 'APIを実行できる');
    }

    function test_detail()
    {
        $orders = $this->client->orders()->all();
        $response = $this->client->orders()->detail($orders['orders'][0]['unique_key']);
        $this->assertTrue(is_array($response), 'APIを実行できる');
    }

    // NOTE: このテストを作動させるたびに受注を作らなければならないためテストがしにくい、よって手動でテストをを行う
    // function test_edit_status() {}
}
