<?php

namespace Bolster\BaseApi;

/**
 * DeliveryCompanies APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class DeliveryCompanies extends ApiAbstract
{
    /**
     * 配送業者情報の一覧を取得
     *
     * NOTE: API名とメソッド名が違う。indexではわかりにくいのでallにした
     *
     * GET /1/delivery_companies
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_delivery_companies.md
     *
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function all()
    {
        return $this->client->request('get', '/1/delivery_companies');
    }
}
