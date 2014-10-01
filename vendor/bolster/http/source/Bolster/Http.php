<?php

namespace Bolster;

class Http
{
    protected $_request;
    protected $_response;

    public function __construct()
    {
        $this->_request = new Http\Request();
        $this->_response = new Http\Response();
    }

    /**
     * レスポンスのパーザをセットする
     * 
     * @param Http\Parser\ParserInterface $parser パーザのインスタンス
     * @return void
     */
    public function setParser(Http\Parser\ParserInterface $parser)
    {
        $this->_response->setParser($parser);
    }

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
        $this->_request->setHeaders($key, $value);
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
        $this->_request->setHttpContextOptions($key, $value);
    }

    /**
     * メソッドでHTTP通信を行う
     * 
     * @param string $url             送信するURL
     * @param array  $params          送信するパラメータ。省略すると空配列
     * @return mixed Http\Response#parseの戻り値
     */
    public function get($url, array $params = array())
    {
        $response_text = $this->_request->get($url, $params);
        $response      = $this->_response->parse($response_text);

        return $response;
    }

    /**
     * メソッドでHTTP通信を行う
     * 
     * @param string $url             送信するURL
     * @param array  $params          送信するパラメータ。省略すると空配列
     * @return mixed Http\Response#parseの戻り値
     */
    public function post($url, array $params = array())
    {
        $response_text = $this->_request->post($url, $params);
        $response      = $this->_response->parse($response_text);

        return $response;
    }

    /**
     * メソッドでHTTP通信を行う
     * 
     * @param string $url             送信するURL
     * @param array  $params          送信するパラメータ。省略すると空配列
     * @return mixed Http\Response#parseの戻り値
     */
    public function put($url, array $params = array())
    {
        $response_text = $this->_request->put($url, $params);
        $response      = $this->_response->parse($response_text);

        return $response;
    }

    /**
     * メソッドでHTTP通信を行う
     * 
     * @param string $url             送信するURL
     * @param array  $params          送信するパラメータ。省略すると空配列
     * @return mixed Http\Response#parseの戻り値
     */
    public function delete($url, array $params = array())
    {
        $response_text = $this->_request->delete($url, $params);
        $response      = $this->_response->parse($response_text);

        return $response;
    }

    /**
     * メソッドでHTTP通信を行う
     * 
     * @param string $url             送信するURL
     * @param array  $params          送信するパラメータ。省略すると空配列
     * @return mixed Http\Response#parseの戻り値
     */
    public function patch($url, array $params = array())
    {
        $response_text = $this->_request->patch($url, $params);
        $response      = $this->_response->parse($response_text);

        return $response;
    }
}
