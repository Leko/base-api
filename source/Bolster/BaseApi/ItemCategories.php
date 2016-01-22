<?php

namespace Bolster\BaseApi;

/**
 * ItemCategories APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class ItemCategories extends ApiAbstract
{
    /**
     * 商品のカテゴリー情報を取得
     * 
     * GET /1/item_categories/detail/:item_id
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_item_categories_detail.md
     * 
     * @param int $item_id カテゴリを取得したい商品ID
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function detail($item_id)
    {
        return $this->client->request('get', '/1/item_categories/detail/'.$item_id);
    }

    /**
     * 商品のカテゴリー情報を登録
     * 
     * POST /1/item_categories/add
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_item_categories_add.md
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param int item_id     商品ID (必須)
     *   @param int category_id カテゴリーID (任意)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function add(array $params = array())
    {
        return $this->client->request('post', '/1/item_categories/add', $params);
    }

    /**
     * 商品のカテゴリー情報を削除
     * 
     * POST /1/item_categories/delete
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_item_categories_delete.md
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param int item_category_id 商品カテゴリーID (必須)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function delete(array $params = array())
    {
        return $this->client->request('post', '/1/item_categories/delete', $params);
    }
}
