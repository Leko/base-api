<?php

require_once __DIR__.'/../../Common.php';

class Test_Http_Parser_PlainParser extends Test_Common
{
    public function test_implements_ParserInterfaceを実装している()
    {
        $ref = new ReflectionClass('Bolster\Http\Parser\PlainParser');
        $this->assertArrayHasKey('Bolster\Http\Parser\ParserInterface', $ref->getInterfaces());
    }

    public function test_parse_渡したそのままのテキストを返却する()
    {
        $parser = new Bolster\Http\Parser\PlainParser();
        $text = 'hoge';

        $this->assertEquals($text, $parser->parse($text));
    }
}
