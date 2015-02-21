<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

use Bolster\BaseApi\BaseApiException;

class Items extends Common
{
    protected $dummyItem;

    public function setUp()
    {
        parent::setUp();

        $item = $this->client->items()->add([
            'title'  => 'dummy_item01',
            'price'  => 100,
            'stock'  => 50,
        ]);
        $this->dummyItem = $item['item'];
    }

    function test_all()
    {
        $response = $this->client->items()->all();
        $this->assertTrue(is_array($response), 'APIが実行できる');
    }

    function test_detail()
    {
        $response = $this->client->items()->detail($this->dummyItem['item_id']);
        $this->assertTrue(is_array($response), 'APIが実行できる');
    }

    function test_add()
    {
        $response = $this->client->items()->add([
            'title' => 'hogehoge',
            'price' => 1000,
            'stock' => 50,
        ]);
        $this->assertTrue(is_array($response), 'APIが実行できる');
    }

    function test_edit()
    {
        $response = $this->client->items()->edit([
            'item_id' => $this->dummyItem['item_id'],
            'title'   => 'hogehoge',
            'price'   => 1000,
            'stock'   => 50,
        ]);
        $this->assertTrue(is_array($response), 'APIが実行できる');
    }

    function test_delete()
    {
        $response = $this->client->items()->delete([
            'item_id' => $this->dummyItem['item_id'],
        ]);
        $this->assertTrue(is_array($response), 'APIが実行できる');
    }

    function test_add_image()
    {
        $response = $this->client->items()->add_image([
            'item_id'   => $this->dummyItem['item_id'],
            'image_no'  => 1,
            'image_url' => 'http://placehold.it/650x650',
        ]);
        $this->assertTrue(is_array($response), 'APIが実行できる');
    }

    function test_delete_image()
    {
        // NOTE: 画像を登録しないと削除できない
        $this->test_add_image();

        $response = $this->client->items()->delete_image([
            'item_id'  => $this->dummyItem['item_id'],
            'image_no' => 1,
        ]);
        $this->assertTrue(is_array($response), 'APIが実行できる');
    }

    function test_edit_stock()
    {
        $response = $this->client->items()->edit_stock([
            'item_id' => $this->dummyItem['item_id'],
            'stock'   => 100
        ]);
        $this->assertTrue(is_array($response), 'APIが実行できる');
    }
}
