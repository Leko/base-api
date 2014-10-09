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
     * @see https://gist.github.com/baseinc/b39113c649b30878c480
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
     * @see https://gist.github.com/baseinc/96314f42e4db05df2513
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
     * @see https://gist.github.com/baseinc/4d966562aeb6344f6fc4
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
