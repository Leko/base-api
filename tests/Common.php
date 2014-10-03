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

abstract class Common extends \PHPUnit_Framework_TestCase {
    protected $client;

    public function setUp()
    {
        $this->client = new \Bolster\BaseApi\Client([
            'client_id' => CLIENT_ID,
            'client_secret' => CLIENT_SECRET,
            'redirect_uri' => REDIRECT_URI,
            'access_token' => ACCESS_TOKEN,
            'refresh_token' => REFRESH_TOKEN,
        ]);

        $mock = new MockHttpClient();
        $mock->setParser(new \Bolster\Http\Parser\JsonParser());

        $this->client->setHttpClient($mock);
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
