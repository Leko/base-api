<?php

namespace Bolster\Http;

class Request
{
    /**
     * HTTPメソッド：GET
     * @var string
     */
    const METHOD_GET    = 'GET';

    /**
     * HTTPメソッド：POST
     * @var string
     */
    const METHOD_POST   = 'POST';

    /**
     * HTTPメソッド：PUT
     * @var string
     */
    const METHOD_PUT    = 'PUT';

    /**
     * HTTPメソッド：DELETE
     * @var string
     */
    const METHOD_DELETE = 'DELETE';

    /**
     * HTTPメソッド：PATCH
     * @var string
     */
    const METHOD_PATCH  = 'PATCH';

    // 送信オプションを指定
    // NOTE: http://www.php.net/manual/ja/context.http.php
    protected $_options = array(
        'http' => array(
            'ignore_errors' => true,    // レスポンスコードが40x系でもレスポンスを取得する
        )
    );

    protected $_headers = array(
        'Content-Type' => 'application/x-www-form-urlencoded',
    );

    /**
     * 送信するHTTPヘッダをセットする
     * 
     * 既に同じキー名のヘッダが存在する場合は上書きするので注意
     * 
     * @param string $key   ヘッダのキー名
     * @param string $value ヘッダに設定する値
     * @return void
     */
    public function setHeaders($key, $value)
    {
        $this->_headers[$key] = $value;
    }

    /**
     * 送信時に生成するHTTPストリームコンテキストの設定をセットする
     * 
     * 既に同じキー名の設定が存在する場合は上書きするので注意
     * 
     * @param string $key   ヘッダのキー名
     * @param string $value ヘッダに設定する値
     * @return void
     */
    public function setHttpContextOptions($key, $value)
    {
        $this->_options['http'][$key] = $value;
    }

    /**
     * HTTP通信を行う
     * 
     * @param string $method  使用するHTTPメソッド
     * @param string $url     送信するURL
     * @param array  $params  送信するパラメータ。省略すると空配列
     */
    public function send($method, $url, array $params = array())
    {
        // key => value形式のヘッダからkey: value形式の文字列の配列へ変換
        $header = array();
        foreach ($this->_headers as $key => $value) {
            $header[] = "{$key}: {$value}";
        }

        $context_options = array_replace_recursive(array(
            'http' => array(
                'method' => $method,
                'header' => implode("\r\n", $header),
            ),
        ), $this->_options);

        // コンテキストを生成し送信
        $context       = stream_context_create($context_options);
        $response_text = file_get_contents($url, false, $context);

        return $response_text;
    }

    /**
     * GETメソッドでHTTP通信を行う
     * 
     * @param string $url             送信するURL
     * @param array  $params          送信するパラメータ。省略すると空配列
     * @return string サーバからのレスポンステキスト
     */
    public function get($url, array $params = array())
    {
        $url .= '?'.http_build_query($params);

        $response = $this->send(self::METHOD_GET, $url, $params);
        return $response;
    }

    /**
     * POSTメソッドでHTTP通信を行う
     * 
     * @param string $url             送信するURL
     * @param array  $params          送信するパラメータ。省略すると空配列
     * @return string サーバからのレスポンステキスト
     */
    public function post($url, array $params = array())
    {
        $this->setHttpContextOptions('content', http_build_query($params));

        $response = $this->send(self::METHOD_POST, $url, $params);
        return $response;
    }

    /**
     * PUTメソッドでHTTP通信を行う
     * 
     * @param string $url             送信するURL
     * @param array  $params          送信するパラメータ。省略すると空配列
     * @return string サーバからのレスポンステキスト
     */
    public function put($url, array $params = array())
    {
        $this->setHttpContextOptions('content', http_build_query($params));

        $response = $this->send(self::METHOD_PUT, $url, $params);
        return $response;
    }

    /**
     * DELETEメソッドでHTTP通信を行う
     * 
     * @param string $url             送信するURL
     * @param array  $params          送信するパラメータ。省略すると空配列
     * @return string サーバからのレスポンステキスト
     */
    public function delete($url, array $params = array())
    {
        $this->setHttpContextOptions('content', http_build_query($params));

        $response = $this->send(self::METHOD_DELETE, $url, $params);
        return $response;
    }

    /**
     * PATCHメソッドでHTTP通信を行う
     * 
     * @param string $url             送信するURL
     * @param array  $params          送信するパラメータ。省略すると空配列
     * @return string サーバからのレスポンステキスト
     */
    public function patch($url, array $params = array())
    {
        $this->setHttpContextOptions('content', http_build_query($params));

        $response = $this->send(self::METHOD_PATCH, $url, $params);
        return $response;
    }
}
