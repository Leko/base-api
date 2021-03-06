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
     * スコープ：ユーザー情報を取得 (デフォルトで付与)
     * @var string
     */
    const SCOPE_READ_USERS = 'read_users';

    /**
     * スコープ：ユーザーのメールアドレスを取得
     * @var string
     */
    const SCOPE_READ_USERS_MAIL = 'read_users_mail';

    /**
     * スコープ：商品情報を取得
     * @var string
     */
    const SCOPE_READ_ITEMS = 'read_items';

    /**
     * スコープ：注文情報を取得
     * @var string
     */
    const SCOPE_READ_ORDERS = 'read_orders';

    /**
     * スコープ：引き出し申請情報を取得
     * @var string
     */
    const SCOPE_READ_SAVINGS = 'read_savings';

    /**
     * スコープ：商品情報を更新
     * @var string
     */
    const SCOPE_WRITE_ITEMS = 'write_items';

    /**
     * スコープ：注文情報を更新
     * @var string
     */
    const SCOPE_WRITE_ORDERS = 'write_orders';

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
     * 1日のAPI回数制限と超えた時のエラーコード
     * 
     * @var string
     */
    const ERROR_RATE_LIMIT_EXCEED = 'day_api_limit';

    /**
     * 1時間のAPI回数制限と超えた時のエラーコード
     * 
     * @var string
     */
    const ERROR_HOUR_RATE_LIMIT_EXCEED = 'hour_api_limit';

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
        'host',
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
    public function setHttpClient(HttpRequestable $http)
    {
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
    public function request($method, $path, array $params = array())
    {
        if(!($this->http instanceof HttpRequestable)) {
            throw new \RuntimeException('httpクライアントが指定されていません');
        }

        $lower_method = strtolower($method);
        $url          = $this->host.$path;

        // アクセストークンをヘッダにセットする
        $this->http->setHeaders('Authorization', self::TOKEN_TYPE.' '.$this->access_token);

        $response = $this->http->request($lower_method, $url, $params);

        if(isset($response['error'])) {
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
    protected function errorHandle($error, $error_description)
    {
        // NOTE: アクセストークンが無効です。のエラーは空送信した場合も起こる。
        //       しかしアクセストークンがセットされていない場合は期限切れではないので別扱いする
        if(!is_null($this->access_token) && $error_description === self::ERROR_EXPIRED_ACCESS_TOKEN) {
            $exception_class = 'ExpiredAccessTokenException';

        } elseif($error === self::ERROR_RATE_LIMIT_EXCEED) {
            $exception_class = 'RateLimitExceedException';

        } elseif($error === self::ERROR_HOUR_RATE_LIMIT_EXCEED) {
            $exception_class = 'RateLimitExceedException';

        } else {
            $exception_class = 'BaseApiException';
        }

        $exception_class = __NAMESPACE__ . '\\' . $exception_class;

        throw new $exception_class($error_description, $error);
    }

    /**
     * 各種APIクライアントクラスのインスタンスを生成する
     * 
     * 存在しないクラスを指定したら例外をスローする
     * 
     * @param string $name 生成するクライアントクラスの名前
     * @return Client Clientを継承した各APIのクライアントクラス
     */
    protected function factory($name)
    {
        $client_class = __NAMESPACE__ . '\\' . $name;

        if(!class_exists($client_class)) {
            throw new \LogicException('存在しないクラス名が指定されています：'.$name);
        }

        // $thisを渡し各APIでのリクエスト処理を自分自身に移譲させる
        $instance = new $client_class($this);
        return $instance;
    }

    /**
     * OAuthのインスタンスを生成する
     * @return OAuth このインスタンスの設定を引き継いたOAuthクラスのインスタンス
     */
    public function oauth()
    {
        $instance = $this->factory('OAuth');
        return $instance;
    }

    /**
     * Usersのインスタンスを生成する
     * @return Users このインスタンスの設定を引き継いたUsersクラスのインスタンス
     */
    public function users()
    {
        $instance = $this->factory('Users');
        return $instance;
    }

    /**
     * Itemsのインスタンスを生成する
     * @return Items このインスタンスの設定を引き継いたItemsクラスのインスタンス
     */
    public function items()
    {
        $instance = $this->factory('Items');
        return $instance;
    }

    /**
     * Categoriesのインスタンスを生成する
     * @return Categories このインスタンスの設定を引き継いたCategoriesクラスのインスタンス
     */
    public function categories()
    {
        $instance = $this->factory('Categories');
        return $instance;
    }

    /**
     * Itemcategoriesのインスタンスを生成する
     * @return Itemcategories このインスタンスの設定を引き継いたItemcategoriesクラスのインスタンス
     */
    public function itemcategories()
    {
        $instance = $this->factory('ItemCategories');
        return $instance;
    }

    /**
     * Ordersのインスタンスを生成する
     * @return Orders このインスタンスの設定を引き継いたOrdersクラスのインスタンス
     */
    public function orders()
    {
        $instance = $this->factory('Orders');
        return $instance;
    }

    /**
     * Savingsのインスタンスを生成する
     * @return Savings このインスタンスの設定を引き継いたSavingsクラスのインスタンス
     */
    public function savings()
    {
        $instance = $this->factory('Savings');
        return $instance;
    }

    /**
     * DeliveryCompaniesのインスタンスを生成する
     * @return DeliveryCompanies このインスタンスの設定を引き継いたDeliveryCompaniesクラスのインスタンス
     */
    public function deliverycompanies()
    {
        $instance = $this->factory('DeliveryCompanies');
        return $instance;
    }
}
