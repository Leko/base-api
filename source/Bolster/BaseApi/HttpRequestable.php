<?php

namespace Bolster\BaseApi;

/**
 * HTTP通信を行うためのクライアントのメソッド定義を保証するためのインタフェース
 */
interface HttpRequestable
{
	/**
	 * 与えられたkey-valueのHTTPヘッダをセットする
	 * 
	 * $keyと$valueで分割して渡されるが、送信時には"$key: $value"という文字列としてヘッダを送信する
	 * NOTE: ここでセットされたヘッダは送信時に必ず送信されなければならない
	 * 
	 * @param string $key   ヘッダ名
	 * @param string $value ヘッダ名に対応する値
	 * @return void
	 */
	public function setHeaders($key, $value);

	/**
	 * GET通信を行う
	 * 
	 * @param string $url    リクエストを行うURL
	 * @param array  $params URLへ渡すパラメータ
	 */
	public function get($url, array $params = array());

	/**
	 * POST通信を行う
	 * 
	 * @param string $url    リクエストを行うURL
	 * @param array  $params URLへ渡すパラメータ
	 */
	public function post($url, array $params = array());

	/**
	 * PUT通信を行う
	 * 
	 * @param string $url    リクエストを行うURL
	 * @param array  $params URLへ渡すパラメータ
	 */
	public function put($url, array $params = array());

	/**
	 * DELETE通信を行う
	 * 
	 * @param string $url    リクエストを行うURL
	 * @param array  $params URLへ渡すパラメータ
	 */
	public function delete($url, array $params = array());
}
