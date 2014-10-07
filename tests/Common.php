<?php

namespace Bolster\BaseApi\Tests;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../source/BaseApi/Client.php';
require_once __DIR__.'/../source/BaseApi/OAuth.php';
require_once __DIR__.'/../source/BaseApi/Users.php';
require_once __DIR__.'/../source/BaseApi/Items.php';
require_once __DIR__.'/../source/BaseApi/Categories.php';
require_once __DIR__.'/../source/BaseApi/ItemCategories.php';
require_once __DIR__.'/../source/BaseApi/Orders.php';
require_once __DIR__.'/../source/BaseApi/Savings.php';
require_once __DIR__.'/../source/BaseApi/HttpRequestable.php';
require_once __DIR__.'/../source/BaseApi/BaseApiException.php';
require_once __DIR__.'/../source/BaseApi/ExpiredAccessTokenException.php';
require_once __DIR__.'/../source/BaseApi/RateLimitExceedException.php';

class BaseApiHttp extends \Bolster\Http implements \Bolster\BaseApi\HttpRequestable {}

abstract class Common extends \PHPUnit_Framework_TestCase {
    protected $client;

    public function setUp()
    {
        $this->client = static::getBaseApiClient();
    }

    /**
     * BASE APIのクライアントを取得する
     * 
     * @return \Bolster\BaseApi\Client BASE APIのクライアント
     */
    protected static function getBaseApiClient()
    {
        $client = new \Bolster\BaseApi\Client([
            'client_id' => CLIENT_ID,
            'client_secret' => CLIENT_SECRET,
            'redirect_uri' => REDIRECT_URI,
            'access_token' => ACCESS_TOKEN,
            'refresh_token' => REFRESH_TOKEN,
            'scopes' => [
                \Bolster\BaseApi\Client::SCOPE_READ_USERS,
                \Bolster\BaseApi\Client::SCOPE_READ_USERS_MAIL,
                \Bolster\BaseApi\Client::SCOPE_READ_ITEMS,
                \Bolster\BaseApi\Client::SCOPE_READ_ORDERS,
                \Bolster\BaseApi\Client::SCOPE_READ_SAVINGS,
                \Bolster\BaseApi\Client::SCOPE_WRITE_ITEMS,
                \Bolster\BaseApi\Client::SCOPE_WRITE_ORDERS,
            ]
        ]);

        $mock = new BaseApiHttp();
        $mock->setParser(new \Bolster\Http\Parser\JsonParser());

        $client->setHttpClient($mock);
        return $client;
    }

    /**
     * 全アイテムを削除する
     * 
     * @return void
     */
    protected function removeAllItems()
    {
        $items = $this->client->items()->all();
        foreach($items['items'] as $item) {
            $this->client->items()->delete([
                'item_id' => $item['item_id'],
            ]);
        }
    }

    /**
     * 全カテゴリを削除する
     * 
     * @return void
     */
    protected function removeAllCategories()
    {
        $categories = $this->client->categories()->all();
        foreach($categories['categories'] as $category) {
            $this->client->categories()->delete([
                'category_id' => $category['category_id'],
            ]);
        }
    }

    protected function getProperty($class, $property)
    {
        $class = new \ReflectionClass($class);

        $property = $class->getProperty($property);
        $property->setAccessible(true);

        return $property;
    }

    protected function getMethod($class, $method)
    {
        $class = new \ReflectionClass($class);

        $method = $class->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }
}
