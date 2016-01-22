<?php

namespace Bolster\BaseApi;

/**
 * Savings APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class Savings extends ApiAbstract
{
    /**
     * 引き出し申請情報の一覧を取得
     * 
     * NOTE: API名とメソッド名が違う。indexではわかりにくいのでallにした
     * 
     * GET /1/savings
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_savings.md
     * 
     * @param array $params 指定可能なオプションは以下を参照
     *   @param string start_created 引き出し申請日時はじめ yyyy-mm-dd (任意)
     *   @param string end_created   引き出し申請日時おわり yyyy-mm-dd (任意)
     *   @param int    limit         リミット (任意 デフォルト: 20, MAX: 100)
     *   @param int    offset        オフセット (任意 デフォルト: 0)
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function all(array $params = array())
    {
        return $this->client->request('get', '/1/savings', $params);
    }
}
