
## install

add `bolster/base-api` to yout `composer.json`

指定可能なバージョンは[releases](https://packagist.org/packages/bolster/base-api)を御覧下さい。

```json
{
	"require": {
		"bolster/base-api": "dev-master"
	}
}
```

## サンプル

### インスタンス生成
指定可能なオプションは、[こちら](https://github.com/Leko/base-api/blob/master/source/BaseApi/Client.php#L145)を御覧下さい。

```
<?php

$config = [
	'client_id'     => 'YOUR_CLIENT_ID',
	'client_secret' => 'YOUR_CLIENT_SECRET',
	'redirect_uri'  => 'YOUR_REDIRECT_URI',
	'scopes'        => ['read_orders', 'read_users'],
];

$client = new \Bolster\BaseApi\Client($config);
```

---

### 各種APIのクライアントを生成する

各種APIのクライアントを生成するには`api名(小文字アンダーバーなし)`のメソッドが用意されているので、そちらをご利用下さい。  
その先のメソッドは各ソースコードを御覧下さい。

```php
$client = new \Bolster\BaseApi\Client($config);

// NOTE: 一度変数に取るとインスタンス生成のコストを削減できる
$oauth_client  = $client->oauth();
$aurhotize_url = $oauth_client->authorize();

// NOTE: 一度にチェインで書くことも可能
$aurhotize_url = $client->oauth()->authorize();

// other methods
// $client->users();
// $client->items();
// $client->categories();
// $client->itemcategories();
// $client->orders();
// $client->savings();
```

---

### 例外
BASE APIからエラーのレスポンスが返ってきたら、それを例外としてスローします。

特別にcatchするであろう例外を子クラスにして、より具体化しています。

`ExpiredAccessTokenException`も`RateLimitExceedException`も`BaseApiException`を継承しているので、  
両方ともcatchしたい場合には`BaseApiException`をキャッチすれば問題ありません。

`ExpiredAccessTokenException`は、アクセストークンがセットされていない場合は発生しません。  
アクセストークンがセットされており、なおかつ無効ですとレスポンスが返ってきた場合のみ発生します。

```php
<?php
try {

	$client->items()->delete(['item_id' => 100]);

// アクセストークンの有効期限が切れた
} catch(\Base\Api\ExpiredAccessTokenException $e) {
	// アクセストークンをリフレッシュしてリトライ
	$client->oauth()->refresh($_SESSION['refresh_token']);
	$client->items()->delete(['item_id' => 100]);

// 1日あたりのAPI使用回数制限に達した
} catch(\Base\Api\RateLimitExceedException $e) {
	// 1分待って再送信（NOTE: 日を跨ぐまで回数はリセットされないので実用例ではない）
	sleep(60);
	$client->items()->delete(['item_id' => 100]);

// その他エラー
} catch(\Base\Api\BaseApiException $e) {
	var_dump($e);
}
```

---

### リダイレクト、コールバックのサンプル

#### リダイレクト
```php
<?php

$config = [
	'client_id'     => 'YOUR_CLIENT_ID',
	'client_secret' => 'YOUR_CLIENT_SECRET',
	'redirect_uri'  => 'YOUR_REDIRECT_URI',
	'scopes'        => ['read_orders', 'read_users'],
];
$client = new \Bolster\BaseApi\Client($config);

$state  = 'hogehoge';
$client->oauth()->authorize($state);
```

#### コールバック
```php
<?php

if(isset($_GET['error'])) {
	throw new RuntimeException($_GET['error']);
}

$config = [
	'client_id'     => 'YOUR_CLIENT_ID',
	'client_secret' => 'YOUR_CLIENT_SECRET',
	'redirect_uri'  => 'YOUR_REDIRECT_URI',
	'scopes'        => ['read_orders', 'read_users'],
];
$client = new \Bolster\BaseApi\Client($config);

$credentials = $client->oauth()->getAccessToken($code);

$_SESSION['access_token'] = $credentials['access_token'];
$_SESSION['refresh_token'] = $credentials['refresh_token'];

$client->setAccessToken($credentials['access_token']);
$me = $client->users()->me();
var_dump($me);
```

---

### 取得、エラー制御
```php
<?php

// @param array $orders
// @return void
function dispatch_all(array $orders) {
	global $client;

	foreach($orders as $order) {
		$detail = $client->orders()->detail($order['unique_key']);

		foreach($detail['order_items'] as $item) {
			$client->orders()->editStatus($item['order_item_id'], 'dispatched');
		}
	}
}

if(isset($_SESSION['access_token'])) {
	throw new RuntimeException('アクセストークンがセットされていません');
}

$config = [
	'client_id'     => 'YOUR_CLIENT_ID',
	'client_secret' => 'YOUR_CLIENT_SECRET',
	'redirect_uri'  => 'YOUR_REDIRECT_URI',
	'scopes'        => ['read_orders', 'read_users'],

	'access_token'  => $_SESSION['access_token'],
];
$client = new \Bolster\BaseApi\Client($config);

// NOTE: 60 * 60 * 24 * 7 = 1 week
$orders = $client->orders()->all([
	'limit' => 100,
	'start_ordered' => date('Y-m-d H:i:s', time() - (60 * 60 * 24 * 7))
]);

var_dump($orders);

try {
	dispatch_all($orders);
} catch(\Base\Api\ExpiredAccessTokenException $e) {
	$client->oauth()->refresh($_SESSION['refresh_token']);
	dispatch_all($orders);
}
```

## BASE APIの仕様雑記
### OAuth
- `authorize`メソッドでscopeを何も設定せず(`&scope=`で終わった状態で)送信するとデフォルト権限になる

### Items
- 価格(税込み)は、**50以上** ~ **500000以下**しか登録不可能
- 在庫数は、**0以上** ~ **10000以下**

### Categories
- 商品に設定されているカテゴリも削除することが可能。削除するとそのカテゴリが設定されていた商品のカテゴリからも削除される
- 指定できるカテゴリ名は**最大30文字**、それ以上長い文字列を指定しても後ろが切り取られる
	- 半角でも全角でも30文字。バイト数ではなく文字列長で判断されている
- 指定できる`list_order`は**最大100000まで**。それ以上大きな値を指定しても100000に丸められる
- カテゴリ名が重複すると「バリデーションエラーです」と出る

### ItemCategories
- 既に登録されている`item_id`, `category_id`の組み合わせをaddしようとすると`不正なcategory_idです。`と言われる
