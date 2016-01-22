<?php

namespace Bolster\BaseApi;

/**
 * Users APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class Users extends ApiAbstract
{
    /**
     * ユーザー情報を取得
     * 
     * GET /1/users/me
     * @see https://github.com/baseinc/api-docs/blob/master/base_api_v1_users_me.md
     * 
     * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
     */
    public function me()
    {
        return $this->client->request('get', '/1/users/me');
    }
}
