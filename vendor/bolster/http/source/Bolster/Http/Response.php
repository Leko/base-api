<?php

namespace Bolster\Http;

class Response
{
    protected $_parser;

    /**
     * コンストラクタ
     * 
     * @param string                 $response_text レスポンステキスト
     * @param Parser\ParserInterface $parser        レスポンスのパーザー。省略するとPlainParser(そのままのテキストを文字列で返す)が適用されます
     */
    public function __construct(Parser\ParserInterface $parser = null)
    {
        if(is_null($parser)) {
            $parser = new Parser\PlainParser();
        }

        $this->setParser($parser);
    }

    /**
     * レスポンスのパースするパーザをセットする
     * 
     * @param Parser\ParserInterface $parser パーザのインスタンス
     * @return void
     */
    public function setParser(Parser\ParserInterface $parser)
    {
        $this->_parser = $parser;
    }

    /**
     * レスポンスをパースする
     * 
     * @param string $response_text レスポンステキスト
     * @return mixed パーザのパースメソッドの戻り値
     */
    public function parse($response_text)
    {
        $parsed = $this->_parser->parse($response_text);
        return $parsed;
    }
}
