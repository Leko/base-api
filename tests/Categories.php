<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

class Categories extends Common {
    private $category_id;

    public static function setUpAfterClass()
    {
        $client = static::getBaseApiClient();

        // このクラスのテストで作成されたカテゴリを全て削除
        $categories = $client->categories()->all();

        foreach ($categories['categories'] as $category) {
            $client->categories()->delete([
                'category_id' => $category['category_id'],
            ]);
        }
    }

    function test_all()
    {
        $response = $this->client->categories()->all();
        $this->assertTrue(is_array($response), '戻り値は配列');
    }

    function test_add()
    {
        $response = $this->client->categories()->add([
            'name' => '256ああああああああああああああああああああああああああああ',
            'list_order' => 1000001
        ]);

        $this->assertTrue(is_array($response), '戻り値は配列');
    }

    function test_edit()
    {
        $this->test_add();  // NOTE: カテゴリを追加

        $categories = $this->client->categories()->all();
        $response = $this->client->categories()->edit([
            'category_id' => $categories['categories'][0]['category_id'],
            'name'        => '31文字あああああああああああああああああああああああああああ',
            'list_order'  => 2,
        ]);

        $this->assertTrue(is_array($response), '戻り値は配列');
    }

    function test_delete()
    {
        $this->test_add();  // NOTE: カテゴリを追加

        $categories = $this->client->categories()->all();
        $response = $this->client->categories()->delete([
            'category_id' => $categories['categories'][0]['category_id'],
        ]);
        $this->assertTrue(is_array($response), '戻り値は配列');
    }
}
