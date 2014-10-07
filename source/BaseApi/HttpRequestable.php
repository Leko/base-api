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
	 * 既に設定されているものと同じキー名のヘッダが指定されたら上書きを行う。ヘッダを重複させてはならない。
	 * NOTE: ここでセットされたヘッダは送信時に必ず送信されなければならない
	 * 
	 * @param string $key   ヘッダ名
	 * @param string $value ヘッダ名に対応する値
	 * @return void
	 */
	public function setHeaders($key, $value);

	/**
	 * HTTP通信を行う
	 * 
	 * 指定されたHTTPメソッドで通信を行わなければならない
	 * 指定したURLにリクエストを行わなければならない
	 * 指定されたパラメータを全て渡さなければならない
	 * 
	 * @param string $method リクエストを行うHTTPメソッド
	 * @param string $url    リクエストを行うURL
	 * @param array  $params URLへ渡すパラメータ
	 * @return mixed $urlからのレスポンス
	 */
	public function request($method, $url, array $params = array());
}
