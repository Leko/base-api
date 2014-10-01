<?php

require_once __DIR__.'/../../Common.php';

class Test_Http_Parser_JsonParser extends Test_Common
{
    public function test_implements_ParserInterfaceを実装している()
    {
        $ref = new ReflectionClass('Bolster\Http\Parser\JsonParser');
        $this->assertArrayHasKey('Bolster\Http\Parser\ParserInterface', $ref->getInterfaces());
    }

    public function test_parse_渡したjson文字列をパースして連想配列で返却する()
    {
        $parser = new Bolster\Http\Parser\JsonParser();
        $json = '{"a":1, "b":2, "c":3}';
        $parsed = array('a' => 1, 'b' => 2, 'c' => 3);

        $this->assertEquals($parsed, $parser->parse($json));
    }

    public function test_parse_不正なjson文字列を渡すとnullが返る()
    {
        $parser = new Bolster\Http\Parser\JsonParser();
        $invalid_json = '{"a"::[123}';

        $this->assertNull($parser->parse($invalid_json));
    }
}
