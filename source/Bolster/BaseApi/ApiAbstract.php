<?php

namespace Bolster\BaseApi;

/**
 * BASE APIのラッパーとなるクラスの基底クラス
 * 
 * インスタンス化する必要がないのでabstractにしている
 * 
 * @author Leko <leko.noor@gmail.com>
 */
class ApiAbstract
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
