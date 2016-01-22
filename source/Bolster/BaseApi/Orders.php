<?php

namespace Bolster\BaseApi;

/**
 * Orders APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class Orders extends ApiAbstract
{
    /**
     * ステータス：未発送
     * @var string
     */
    const STATUS_ORDERED = 'ordered';

    /**
     * ステータス：配送中
     * @var string
     */
    const STATUS_SHIPPING = 'shipping';

    /**
     * ステータス：着荷
     * @var string
     */
    const STATUS_ARRIVED = 'arrived';

    /**
     * ステータス：配送済み
     * @var string
     */
    const STATUS_DISPATCHED = 'dispatched';

    /**
     * ステータス：キャンセル済み
     * @var string
     */
    const STATUS_CANCELLED = 'cancelled';

    /**
     * クレジットカード決済
     * @var string
     */
    const PAYMENT_CREDITCARD = 'creditcard';

    /**
     * 銀行振込(ショップ口座)
     * @var string
     */
    const PAYMENT_BANK_TRADE = 'bt';
    
    /**
     * 代金引換
     * @var string
     */
    const PAYMENT_COD = 'cod';

    /**
     * コンビニ決済
     * @var string
     */
    const PAYMENT_CVS = 'cvs';

    /**
     * 銀行振込(BASE口座)
     * @var string
     */
    const PAYMENT_BASE_BANK_TRADE = 'base_bt';

    /**
     * 後払い決済
     * @var string
     */
    const PAYMENT_ATOBARAI = 'atobarai';

    /**
     * 注文情報の一覧を取得
     * 
     * NOTE: API名とメソッド名が違う。indexではわかりにくいのでallにした
     * 
     * GET /1/orders
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_orders.md
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param string start_ordered 注文日時はじめ yyyy-mm-dd または yyyy-mm-dd hh:mm:ss (任意)
     *   @param string end_ordered   注文日時おわり yyyy-mm-dd または yyyy-mm-dd hh:mm:ss (任意)
     *   @param int    limit         リミット (任意 デフォルト: 20, MAX: 100)
     *   @param int    offset        オフセット (任意 デフォルト: 0)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function all(array $params = array())
    {
        return $this->client->request('get', '/1/orders', $params);
    }

    /**
     * 注文情報を取得
     * 
     * GET /1/orders/detail/:unique_key
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_orders_detail.md
     * 
     * @param string $unique_key 注文番号
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function detail($unique_key)
    {
        return $this->client->request('get', '/1/orders/detail/'.$unique_key);
    }

    /**
     * 注文情報のステータスを更新
     * 
     * POST /1/orders/edit_status
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_orders_edit_status.md
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param int    order_item_id 購入商品ID (必須)
     *   @param string status        dispatched か cancelled のいずれか (必須)
     *   @param string add_comment   発送メールに添付する一言メッセージ
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function edit_status(array $params = array())
    {
        return $this->client->request('post', '/1/orders/edit_status', $params);
    }
}
