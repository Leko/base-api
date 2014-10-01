<?php

namespace Bolster\Http\Parser;

interface ParserInterface
{
    /**
     * レスポンスをパースする
     * 
     * @param string $response_text レスポンステキスト
     * @return mixed 各自のフォーマットによってパースされたレスポンスデータ
     */
    public function parse($response_text);
}
