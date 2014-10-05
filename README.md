
## サンプル

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
$client = new \Base\Api\Client($config);

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
$client = new \Base\Api\Client($config);

$credentials = $client->oauth()->getAccessToken($code);

$_SESSION['access_token'] = $credentials['access_token'];
$_SESSION['refresh_token'] = $credentials['refresh_token'];

$client->setAccessToken($credentials['access_token']);
$me = $client->users()->me();
var_dump($me);
```

### 取得、エラー制御
```php
<?php

// @param array $orders
// @return void
function dispatch_all(array $orders) {
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
$client = new \Base\Api\Client($config);

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

## 雑記
### OAuth
- `authorize`メソッドでscopeを何も設定せず(`&scope=`で終わった状態で)送信するとデフォルト権限になる

### Categories
- 指定できるカテゴリ名は**最大30文字**、それ以上長い文字列を指定しても後ろが切り取られる
	- 半角でも全角でも30文字。バイト数ではなく文字列長で判断されている
- 指定できる`list_order`は**最大100000まで**。それ以上大きな値を指定しても100000に丸められる
- カテゴリ名が重複すると「バリデーションエラーです」と出る

### ItemCategories
- 既に登録されている`item_id`, `category_id`の組み合わせをaddしようとすると`不正なcategory_idです。`と言われる
