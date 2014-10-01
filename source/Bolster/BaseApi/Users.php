<?php

namespace Bolster\BaseApi;

/**
 * Users APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class Users extends Client
{
	/**
	 * ユーザー情報を取得
	 * 
	 * GET /1/users/me
	 * @see https://gist.github.com/baseinc/9759577
	 * 
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function me(array $params = array()) {
		return $this->request('get', '/1/users/me', $params);
	}
}
