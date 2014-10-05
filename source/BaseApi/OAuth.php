<?php

namespace Bolster\BaseApi;

/**
 * OAuth APIのクライアントとなるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class OAuth extends Client
{
	/**
	 * authorizeで使用するレスポンスの形式
	 * @var string
	 */
	const RESPONSE_TYPE_CODE = 'code';

	/**
	 * tokenで使用するgrant_typeの値
	 * @var string
	 */
	const GRANT_TYPE_AUTHORIZATION_CODE = 'authorization_code';

	/**
	 * refreshで使用するgrant_typeの値
	 * @var string
	 */
	const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';

	/**
	 * 認可コードを取得するための画面へ遷移するURLを生成する
	 * 
	 * GET /1/oauth/authorize
	 * @see https://gist.github.com/baseinc/9777239
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param string state リダイレクト先URLにそのまま返すパラメーター (任意)
	 * @return void 認証画面のURLを返却する
	 */
	public function authorize(array $params = array()) {
		$params['response_type'] = self::RESPONSE_TYPE_CODE;
		$params['client_id']     = $this->client_id;
		$params['redirect_uri']  = $this->redirect_uri;
		$params['scope']         = implode(' ', $this->scopes);

		$url = $this->host.'/1/oauth/authorize?'.http_build_query($params);
		return $url;
	}

	/**
	 * 認可コードからアクセストークンを取得
	 * 
	 * NOTE: レスポンスで認証情報が得られたら自動的にaccess_token,refresh_tokenにセットする
	 *       よってこのメソッドを使用するなら明示的にアクセストークンとリフレッシュトークンを再セットする必要はない
	 * 
	 * POST /1/oauth/token
	 * @see https://gist.github.com/baseinc/9777762
	 * 
	 * @param array $params 指定可能なオプションは以下を参照
	 *   @param string code 認可コード (必須)
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function token(array $params = array()) {
		$params['grant_type']    = self::GRANT_TYPE_AUTHORIZATION_CODE;
		$params['client_id']     = $this->client_id;
		$params['client_secret'] = $this->client_secret;
		$params['redirect_uri']  = $this->redirect_uri;

		// NOTE: レスポンスを受け取ったらクライアントの認証情報もリセットする
		$credentials = $this->request('post', '/1/oauth/token', $params);
		$this->setCredential($credentials);

		return $credentials;
	}

	/**
	 * リフレッシュトークンからアクセストークンを取得
	 * 
	 * NOTE: レスポンスで認証情報が得られたら自動的にaccess_token,refresh_tokenにセットする
	 *       よってこのメソッドを使用するなら明示的にアクセストークンとリフレッシュトークンを再セットする必要はない
	 * 
	 * NOTE: メソッド名がAPI名と異なる。tokenは先に使われているので別の名前を採用。
	 * 
	 * POST /1/oauth/token
	 * @see https://gist.github.com/baseinc/9778140
	 * 
	 * @return array 連想配列。ドキュメントのサンプルレスポンスを参照
	 */
	public function refresh() {
		$params['grant_type']    = self::GRANT_TYPE_REFRESH_TOKEN;
		$params['client_id']     = $this->client_id;
		$params['client_secret'] = $this->client_secret;
		$params['redirect_uri']  = $this->redirect_uri;
		$params['refresh_token'] = $this->refresh_token;

		// NOTE: レスポンスを受け取ったらクライアントの認証情報もリセットする
		$credentials = $this->request('post', '/1/oauth/token', $params);
		$this->setCredential($credentials);

		return $credentials;
	}

	/**
	 * 取得した認証情報をプロパティにもセットする
	 */
	private function setCredential($credentials)
	{
		$this->setConfig('access_token', $credentials['access_token']);
		$this->setConfig('refresh_token', $credentials['refresh_token']);
	}
}
