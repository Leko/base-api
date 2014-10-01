<?php

namespace Bolster\Http\Parser;

class JsonParser implements ParserInterface
{
    /**
     * レスポンスをパースする
     * 
     * @param string $response_text レスポンステキスト
     * @return array json文字列をパースした結果の連想配列{}
     */
    public function parse($response_text)
    {
        return json_decode($response_text, true);
    }
}
