<?php

namespace Bolster\BaseApi;

/**
 * OAuth APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class OAuth extends Client
{
	/**
	 * 認可コードを取得
	 * 
	 * GET /1/oauth/authorize
	 * @see https://gist.github.com/baseinc/9777239
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param XXXXX response_type code (必須)
	 *   @param XXXXX client_id クライアントID (必須)
	 *   @param XXXXX redirect_uri 登録したコールバックURL (必須)
	 *   @param XXXXX scope スコープをスペース区切りで指定 (任意 デフォルト: read_users)
	 *   @param XXXXX state リダイレクト先URLにそのまま返すパラメーター (任意)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function authorize(array $params = array()) {
		return $this->request('get', '/1/oauth/authorize', $params);
	}

	/**
	 * 認可コードからアクセストークンを取得
	 * 
	 * POST /1/oauth/token
	 * @see https://gist.github.com/baseinc/9777762
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param XXXXX grant_type authorization_code (必須)
	 *   @param XXXXX client_id クライアントID (必須)
	 *   @param XXXXX client_secret クライアントシークレット (必須)
	 *   @param XXXXX code 認可コード (必須)
	 *   @param XXXXX redirect_uri 登録したコールバックURL (必須)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function token(array $params = array()) {
		return $this->request('post', '/1/oauth/token', $params);
	}

	/**
	 * リフレッシュトークンからアクセストークンを取得
	 * 
	 * POST /1/oauth/token
	 * @see https://gist.github.com/baseinc/9778140
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param XXXXX grant_type refresh_token (必須)
	 *   @param XXXXX client_id クライアントID (必須)
	 *   @param XXXXX client_secret クライアントシークレット (必須)
	 *   @param XXXXX refresh_token リフレッシュトークン (必須)
	 *   @param XXXXX redirect_uri 登録したコールバックURL (必須)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function token(array $params = array()) {
		return $this->request('post', '/1/oauth/token', $params);
	}
}
