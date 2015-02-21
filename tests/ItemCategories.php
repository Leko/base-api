<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/Common.php';

class ItemCategories extends Common
{
    protected $dummyItem;
    protected $dummyItemCategory;

    public function setUp()
    {
        parent::setUp();

        // itemとcategoryを追加
        $item = $this->client->items()->add([
            'title'  => 'dummy_item01',
            'price'  => 100,
            'stock'  => 50,
        ]);
        $category = $this->client->categories()->add([
            'name' => 'dummy_category01'
        ]);

        // それらを紐付け
        $item_category = $this->createItemCategories($item['item']['item_id'], $category['categories'][0]['category_id']);
        $this->dummyItem = $item['item'];
        $this->dummyItemCategory = $item_category['item_categories'][0];
    }
    public function tearDown()
    {
        // 商品・カテゴリを削除するテストで商品が消されることがあるので例外を握りつぶし
        try {
            $this->client->items()->delete([
                'item_id' => $this->dummyItem['item_id'],
            ]);
            $this->client->categories()->delete([
                'category_id' => $this->dummyItemCategory['category_id'],
            ]);
        } catch(BaseApiException $e) {}
    }

    /**
     * アイテムとカテゴリのヒモ付を行う
     * 
     * @return array BASE APIからのレスポンス
     */
    private function createItemCategories($item_id, $category_id)
    {
        $item_category = $this->client->itemcategories()->add([
            'item_id'     => $item_id,
            'category_id' => $category_id,
        ]);

        return $item_category;
    }

    function test_detail()
    {
        $response = $this->client->itemcategories()->detail($this->dummyItemCategory['item_id']);
        $this->assertTrue(is_array($response), 'APIが実行できる');
    }

    function test_delete()
    {
        $response = $this->client->itemcategories()->delete([
            'item_category_id' => $this->dummyItemCategory['item_category_id'],
        ]);
        $this->assertTrue(is_array($response), 'APIが実行できる');
    }

}
