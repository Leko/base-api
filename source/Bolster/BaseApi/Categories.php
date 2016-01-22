<?php

namespace Bolster\BaseApi;

/**
 * Categories APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class Categories extends ApiAbstract
{
    /**
     * カテゴリー情報の一覧を取得
     * 
     * NOTE: API名とメソッド名が違う。indexではわかりにくいのでallにした
     * 
     * GET /1/categories
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_categories.md
     * 
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function all()
    {
        return $this->client->request('get', '/1/categories');
    }

    /**
     * カテゴリー情報を登録
     * 
     * POST /1/categories/add
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_categories_add.md
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param string name       カテゴリー名 (必須)
     *   @param int    list_order カテゴリーの並び順 (任意)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function add(array $params = array())
    {
        return $this->client->request('post', '/1/categories/add', $params);
    }

    /**
     * カテゴリー情報を更新
     * 
     * POST /1/categories/edit
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_categories_edit.md
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param int    category_id カテゴリーID (必須)
     *   @param string name        カテゴリー名 (任意)
     *   @param int    list_order  カテゴリーの並び順 (任意)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function edit(array $params = array())
    {
        return $this->client->request('post', '/1/categories/edit', $params);
    }

    /**
     * カテゴリー情報を削除
     * 
     * POST /1/categories/delete
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_categories_delete.md
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param int category_id カテゴリーID (必須)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function delete(array $params = array())
    {
        return $this->client->request('post', '/1/categories/delete', $params);
    }
}
