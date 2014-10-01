<?php

namespace Bolster\BaseApi;

/**
 * APIクライアントの受け口となるクラス
 * @author Leko <leko.noor@gmail.com>
 */
class Client
{
	/**
	 * アクセストークンとセットでヘッダに付与するトークンの形式
	 * @var string
	 */
	const TOKEN_TYPE = 'Bearer';

	/**
	 * アクセストークンの期限切れ時のエラーメッセージ
	 * 
	 * NOTE: エラーコードに統一性がないのでエラーメッセージで比較を行う
	 * FIXME: 些細な仕様変更に弱い
	 * 
	 * @var string
	 */
	const ERROR_EXPIRED_ACCESS_TOKEN = 'アクセストークンが無効です。';

	/**
	 * API回数制限と超えた時のエラーメッセージ
	 * 
	 * NOTE: エラーコードに統一性がないのでエラーメッセージで比較を行う
	 * FIXME: 些細な仕様変更に弱い
	 * 
	 * @var string
	 */
	const ERROR_RATE_LIMIT_EXCEED = '1日のAPIの利用上限を超えました。日付が変わってからもう一度アクセスしてください。';

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
	 * 内部処理で使用するHTTPクライアント
	 * @var HttpRequestable
	 */
	protected $http;

	/**
	 * BASE APIのエンドポイント
	 * NOTE: ステージング環境用のホストに書き換えたり、上書きできる方が柔軟性が高いためconstではなくプロパティ
	 * 
	 * @var string
	 */
	protected $host = 'https://api.thebase.in';

	/**
	 * BASE APIで利用するクライアントID
	 * @var string
	 */
	protected $client_id;

	/**
	 * BASE APIで利用するクライアントシークレット
	 * @var 
	 */
	protected $client_secret;

	/**
	 * BASE APIで使用するリダイレクトURI
	 * @var string
	 */
	protected $redirect_uri;

	/**
	 * アプリが求める権限の一覧
	 * @var string[]
	 */
	protected $scopes = [];

	/**
	 * アクセストークン
	 * @var string
	 */
	protected $access_token = null;

	/**
	 * リフレッシュトークン
	 * @var string
	 */
	protected $refresh_token = null;

	/**
	 * 設定を上書き可能なプロパティの一覧
	 * @var string
	 */
	protected $editable_properties = [
		'client_id',
		'client_secret',
		'redirect_uri',
		'scopes',
		'access_token',
		'refresh_token',
	];

	/**
	 * コンストラクタ
	 * 設定値を連想配列で渡すことができ、設定値が渡された場合はその設定を使用する
	 * 
	 * @param array $config 設定値
	 */
	public function __construct(array $config = array())
	{
		$this->setConfig($config);
	}

	/**
	 * 設定値をセットする
	 * 
	 * @param string|array $property 値を指定するプロパティ。連想配列を一括で渡すことも可能
	 * @param mixed        $value    $propertyに設定する値。連想配列が渡された場合は無視
	 * @return void
	 * @throws \OutOfBoundsException editable_propertiesに無いプロパティが指定された
	 */
	public function setConfig($property, $value = null)
	{
		if(is_array($property)) {
			foreach($property as $prop => $val) {
				$this->setConfig($prop, $val);
			}

		} else {
			// 編集可能なプロパティ一覧にないプロパティが与えられたら例外をスロー
			if(!in_array($property, $this->editable_properties)) {
				throw new \OutOfBoundsException($property);
			}

			$this->{$property} = $value;
		}
	}

	/**
	 * 設定値を取得する
	 * editable_propertiesに定義されたプロパティのみ取得可能
	 * 
	 * @param string $property 取得するプロパティ名。省略されると全件取得
	 * @return mixed プロパティに格納された値
	 * @throws \OutOfBoundsException editable_propertiesに無いプロパティが指定された
	 */
	public function getConfig($property = null)
	{
		// プロパティ名が省略されたら全件取得
		if(is_null($property)) {
			$configs = [];

			foreach($this->editable_properties as $prop) {
				$configs[$prop] = $this->{$prop};
			}

			return $configs;

		// プロパティ名が指定されたらその設定値だけ取得する
		} else {
			if(!in_array($property, $this->editable_properties)) {
				throw new \OutOfBoundsException($property);
			}

			return $this->{$property};
		}
	}

	/**
	 * 通信を行うためのHTTPクライアントクラスのインスタンスをセットする
	 * 
	 * NOTE: 渡されるHTTPクライアントはHttpRequestableインタフェースを実装している必要がある。
	 * @param HttpRequestable $http HTTPクライアントクラス
	 * @return void
	 */
	public function setHttpClient(HttpRequestable $http) {
		$this->http = $http;
	}

	/**
	 * BASE APIへリクエストを行う
	 * 
	 * @param string $method get|post|put|deleteのいずれか
	 * @param string $path   叩くBASE APIのパス。エンドポイント以下のパスを指定
	 * @param array  $params APIへ送信するパラメータ。連想配列で指定、省略すると空配列
	 * @return array レスポンスをJSON形式でパースした結果の連想配列
	 * @throws BaseApiException 詳細はerrorHandleメソッドを参照
	 */
	protected function request($method, $path, array $params = array())
	{
		$lower_method = strtolower($method);
		$url          = $this->host.$path;

		// アクセストークンをヘッダにセットする
		$this->http->setHeaders('Authorization', self::TOKEN_TYPE.' '.$value);

		$response = $this->http->{$lower_method}($url, $params);

		if($response['error']) {
			$this->errorHandle($response['error'], $response['error_description']);
		} else {
			return $response;
		}
	}

	/**
	 * BASE APIへリクエストを送信しエラーのレスポンスが帰ってきた際に呼び出されるフックポイント
	 * 
	 * デフォルトではBaseApiExceptionをスローする。
	 * ただしアクセストークンの有効期限が切れた場合はExpiredAccessTokenExceptionを、
	 * APIの使用回数制限を超えた場合はRateLimitExceedExceptionをスローする。
	 * NOTE: どちらもBaseApiExceptionのサブクラス
	 * 
	 * @param string $error             使用するHTTPメソッド
	 * @param string $error_description リクエストを行うURL
	 * @return void
	 * @throws BaseApiException 例外クラスかそのサブクラス
	 */
	protected function errorHandle($error, $error_description) {
		if($error_description === self::ERROR_EXPIRED_ACCESS_TOKEN) {
			$exception_class = 'ExpiredAccessTokenException';
		} elseif($error_description === self::ERROR_RATE_LIMIT_EXCEED) {
			$exception_class = 'RateLimitExceedException';
		} else {
			$exception_class = 'BaseApiException';
		}

		throw new $exception_class($error_description, $error);
	}

	/**
	 * OAuthのインスタンスを生成する
	 * @return OAuth このインスタンスの設定を引き継いたOAuthクラスのインスタンス
	 */
	public function oauth()
	{
		return new OAuth($this->getConfig());
	}

	/**
	 * Usersのインスタンスを生成する
	 * @return Users このインスタンスの設定を引き継いたUsersクラスのインスタンス
	 */
	public function users()
	{
		return new Users($this->getConfig());
	}

	/**
	 * Itemsのインスタンスを生成する
	 * @return Items このインスタンスの設定を引き継いたItemsクラスのインスタンス
	 */
	public function items()
	{
		return new Items($this->getConfig());
	}

	/**
	 * Categoriesのインスタンスを生成する
	 * @return Categories このインスタンスの設定を引き継いたCategoriesクラスのインスタンス
	 */
	public function categories()
	{
		return new Categories($this->getConfig());
	}

	/**
	 * Itemcategoriesのインスタンスを生成する
	 * @return Itemcategories このインスタンスの設定を引き継いたItemcategoriesクラスのインスタンス
	 */
	public function itemcategories()
	{
		return new ItemCategories($this->getConfig());
	}

	/**
	 * Ordersのインスタンスを生成する
	 * @return Orders このインスタンスの設定を引き継いたOrdersクラスのインスタンス
	 */
	public function orders()
	{
		return new Orders($this->getConfig());
	}

	/**
	 * Savingsのインスタンスを生成する
	 * @return Savings このインスタンスの設定を引き継いたSavingsクラスのインスタンス
	 */
	public function savings()
	{
		return new Savings($this->getConfig());
	}
}
