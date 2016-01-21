<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

class DeliveryCompanies extends Common {
    function test_all()
    {
        $response = $this->client->deliverycompanies()->all()['delivery_companies'];
        $this->assertTrue(is_array($response), '戻り値は配列');
        $this->assertTrue(isset($response[0]['delivery_company_id']), '要素内にdelivery_company_idがある');
        $this->assertTrue(isset($response[0]['name']), '要素内にnameがある');
    }
}
