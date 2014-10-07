<?php

namespace Bolster\BaseApi;

/**
 * Items APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class Items extends Client
{
    /**
     * 並び順の条件：指定した並び順順
     * @var string
     */
    const ORDER_LIST_ORDER = 'list_order';

    /**
     * 並び順の条件：作成日順
     * @var string
     */
    const ORDER_CREATED = 'created';

    /**
     * ソートの順序：昇順
     * @var string
     */
    const SORT_ASC = 'asc';

    /**
     * ソートの順序：降順
     * @var string
     */
    const SORT_DESC = 'desc';

    /**
     * 商品情報の一覧を取得
     * 
     * NOTE: API名とメソッド名が違う。indexではわかりにくいのでallにした
     * 
     * GET /1/items
     * @see https://gist.github.com/baseinc/9910430
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param string order  並び替え項目。list_order か created のいずれか (任意 デフォルト: list_order)
     *   @param string sort   並び順。asc か desc のいずれか (任意 デフォルト: asc)
     *   @param int    limit  リミット (任意 デフォルト: 20, MAX: 100)
     *   @param int    offset オフセット (任意 デフォルト: 0)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function all(array $params = array())
    {
        return $this->request('get', '/1/items', $params);
    }

    /**
     * 商品情報を取得
     * 
     * GET /1/items/detail/:item_id
     * @see https://gist.github.com/baseinc/9912650
     * 
     * @param int $item_id BASEの商品ID
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function detail($item_id)
    {
        return $this->request('get', '/1/items/detail/'.$item_id);
    }

    /**
     * 商品情報を登録
     * 
     * POST /1/items/add
     * @see https://gist.github.com/baseinc/10241083
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param string title                       商品名 (必須)
     *   @param string detail                      商品説明 (任意)
     *   @param int    price                       価格 (必須)
     *   @param int    stock                       在庫数 (必須)
     *   @param int    visible                     1:表示、0:非表示 (任意 デフォルト:1)
     *   @param string identifier                  商品コード (任意)
     *   @param int    list_order                  表示順 (任意 デフォルト:0)
     *   @param string variation[0] ...            バリエーションの種類 (任意)
     *   @param int    variation_stock[0] ...      バリエーションの在庫数 (任意)
     *   @param string variation_identifier[0] ... バリエーションの商品コード (任意)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function add(array $params = array())
    {
        return $this->request('post', '/1/items/add', $params);
    }

    /**
     * 商品情報を更新
     * 
     * POST /1/items/edit
     * @see https://gist.github.com/baseinc/10253466
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param int item_id                        商品ID (必須)
     *   @param string title                       商品名 (任意)
     *   @param string detail                      商品説明 (任意)
     *   @param int    price                       価格 (任意)
     *   @param int    stock                       在庫数 (任意)
     *   @param int    visible                     表示:1、非表示:0 (任意)
     *   @param int    list_order                  表示順 (任意)
     *   @param string identifier                  商品コード (任意)
     *   @param int    variation_id[0] ...         バリエーションID。バリエーションを追加したい場合は空文字列。 (任意 バリエーションを更新する場合は必須)
     *   @param string variation[0] ...            バリエーションの種類 (任意)
     *   @param int    variation_stock[0] ...      バリエーションの在庫数 (任意)
     *   @param string variation_identifier[0] ... バリエーションの商品コード (任意)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function edit(array $params = array())
    {
        return $this->request('post', '/1/items/edit', $params);
    }

    /**
     * 商品情報を削除
     * 
     * POST /1/items/delete
     * @see https://gist.github.com/baseinc/10254181
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param int item_id 商品ID (必須)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function delete(array $params = array())
    {
        return $this->request('post', '/1/items/delete', $params);
    }

    /**
     * 商品情報の画像を登録
     * 
     * POST /1/items/add_image
     * @see https://gist.github.com/baseinc/10247116
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param int    item_id   商品ID (必須)
     *   @param int    image_no  画像番号 1~5 (必須)
     *   @param string image_url 画像URL。jpgかpngかgif。容量4M以内。推奨サイズは640×640px。 (必須)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function add_image(array $params = array())
    {
        return $this->request('post', '/1/items/add_image', $params);
    }

    /**
     * 商品情報の画像を削除
     * 
     * POST /1/items/delete_image
     * @see https://gist.github.com/baseinc/10251257
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param int item_id  商品ID (必須)
     *   @param int image_no 画像番号 1~5 (必須)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function delete_image(array $params = array())
    {
        return $this->request('post', '/1/items/delete_image', $params);
    }

    /**
     * 商品情報の在庫数を更新
     * 
     * POST /1/items/edit_stock
     * @see https://gist.github.com/baseinc/10623305
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param int    item_id         商品ID (必須)
     *   @param int    stock           在庫数 (必須 バリエーションの在庫数を更新したい場合は任意)
     *   @param string variation_id    バリエーションID (任意 バリエーションの在庫数を更新したい場合は必須)
     *   @param string variation_stock バリエーションの在庫数 (任意 バリエーションの在庫数を更新したい場合は必須)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function edit_stock(array $params = array())
    {
        return $this->request('post', '/1/items/edit_stock', $params);
    }
}
