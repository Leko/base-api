<?php

namespace Bolster\BaseApi;

/**
 * Savings APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class Savings extends Client
{
	/**
	 * 引き出し申請情報の一覧を取得
	 * 
	 * NOTE: API名とメソッド名が違う。indexではわかりにくいのでallにした
	 * 
	 * GET /1/savings
	 * @see https://gist.github.com/baseinc/5c338fa3a62e870f9597
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param string start_created 引き出し申請日時はじめ yyyy-mm-dd (任意)
	 *   @param string end_created   引き出し申請日時おわり yyyy-mm-dd (任意)
	 *   @param int    limit         リミット (任意 デフォルト: 20, MAX: 100)
	 *   @param int    offset        オフセット (任意 デフォルト: 0)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function all(array $params = array()) {
		return $this->request('get', '/1/savings', $params);
	}
}
