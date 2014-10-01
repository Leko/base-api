
# Bolster PHP http module

## What is Bolster
Bolsterは、長枕・支持材のような意味があります。  
開発者を支えて休ませる、そんなライブラリ郡を狙った名前です

> [http://ejje.weblio.jp/content/bolster](http://ejje.weblio.jp/content/bolster)

## class structure
```
Bolster\
├── Http\
│   ├── Parser\
│   │   ├── JsonParser.php
│   │   ├── ParserInterface.php
│   │   └── PlainParser.php
│   ├── Request.php
│   └── Response.php
└── Http.php
```

## usage

### basic
```php
<?php
$http = new Bolster\Http();
$response = $http->get('https://qiita.com/api/v1/users/L_e_k_o');

echo $response;
```

stdout:

```json
{"id":3338,"url_name":"L_e_k_o","profile_image_url":"https://pbs.twimg.com/profile_images/453306597716930561/fcy5Qh53_normal.jpeg",...
```

### with http header and response parser
```php
<?php
$http = new Bolster\Http();

$parser = new Bolster\Http\Parser\JsonParser();
$http->setParser($parser);

$http->setHeaders('Accept',     'application/vnd.github.v3+json');
$http->setHeaders('User-Agent', 'Bolster-Http-Module');

$json = $http->get('https://api.github.com/users/Leko');

var_dump($json);
```

stdout:

```
array(30) {
  'login' =>
  string(4) "Leko"
  'id' =>
  int(1424963)
  'avatar_url' =>
  string(51) "https://avatars.githubusercontent.com/u/1424963?v=2"
  'gravatar_id' =>
  string(32) "13fbd31a4503c352369aab017e3edef7"
  'url' =>
  string(33) "https://api.github.com/users/Leko"
  ...
```

### with context option
```php
<?php

$http = new Bolster\Http();

$http->setHttpContextOptions('ignore_errors', false);
$http->get('http://hogehoge.com/404');
```

throw errors:

```
PHP Warning:  file_get_contents(http://hogehoge.com/404?): failed to open stream: HTTP request failed! HTTP/1.1 404 Not Found
 in /PATH/TO/source/Bolster/Http/Request.php on line 101
PHP Stack trace: ...
```
