<?php

namespace Bolster\BaseApi;

/**
 * Orders APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class Orders extends Client
{
	/**
	 * date関数で使用できるフォーマット(yyyy-mm-dd形式)
	 * @var string
	 */
	const FORMAT_DATE = 'Y-m-d';

	/**
	 * date関数で使用できるフォーマット(yyyy-mm-dd hh:mm:ss形式)
	 * @var string
	 */
	const FORMAT_DATETIME = 'Y-m-d H:i:s';

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
	 * 注文情報の一覧を取得
	 * 
	 * GET /1/orders
	 * @see https://gist.github.com/baseinc/9760824
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param string start_ordered 注文日時はじめ yyyy-mm-dd または yyyy-mm-dd hh:mm:ss (任意)
	 *   @param string end_ordered   注文日時おわり yyyy-mm-dd または yyyy-mm-dd hh:mm:ss (任意)
	 *   @param int    limit         リミット (任意 デフォルト: 20, MAX: 100)
	 *   @param int    offset        オフセット (任意 デフォルト: 0)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function orders(array $params = array()) {
		return $this->request('get', '/1/orders', $params);
	}

	/**
	 * 注文情報を取得
	 * 
	 * GET /1/orders/detail/:unique_key
	 * @see https://gist.github.com/baseinc/9930892
	 * 
	 * @param string $unique_key 注文番号
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function detail($unique_key) {
		return $this->request('get', '/1/orders/detail/'.$unique_key);
	}

	/**
	 * 注文情報のステータスを更新
	 * 
	 * POST /1/orders/edit_status
	 * @see https://gist.github.com/baseinc/9952182
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param int    order_item_id 購入商品ID (必須)
	 *   @param string status        dispatched か cancelled のいずれか (必須)
	 *   @param string add_comment   発送メールに添付する一言メッセージ
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function edit_status(array $params = array()) {
		return $this->request('post', '/1/orders/edit_status', $params);
	}
}
