<?php

require_once __DIR__.'/../Common.php';

class Test_Http_Response extends Test_Common
{
    function test_construct_パーサを省略するとPlainParserのインスタンスがセットされる()
    {
        $parser = $this->getProperty('Bolster\Http\Response', '_parser');
        $this->assertInstanceOf('Bolster\Http\Parser\PlainParser', $parser->getValue(new Bolster\Http\Response()));
    }

    function test_setParser_パーサを指定するとそのパーサインスタンスがセットされる()
    {
        $response = new Bolster\Http\Response();
        $response->setParser(new Bolster\Http\Parser\JsonParser());

        $parser = $this->getProperty('Bolster\Http\Response', '_parser');
        $this->assertInstanceOf('Bolster\Http\Parser\JsonParser', $parser->getValue($response));        
    }

    function test_setParser_ParserInterfaceを実装していないパーサを指定するとエラーが発生する()
    {
        $invalid_parser = new StdClass();

        $this->setExpectedException('PHPUnit_Framework_Error');
        $response = new Bolster\Http\Response();
        $response->setParser($invalid_parser);
    }

    // FIXME: タイプヒンティングを使っており、ParserInterfaceを継承してないのでモックオブジェクトを渡せない
    // function test_parse_渡したパーサのparseメソッドが呼ばれる()
    // {
    //     $mock = $this->getMock('Bolster\Http\Response', array('parse'));
    //     $mock->expects($this->once())
    //         ->method('parse');
            

    //     $response = new Bolster\Http\Response();
    //     $response->setParser($mock);
    //     $response->parse();
    // }
}
