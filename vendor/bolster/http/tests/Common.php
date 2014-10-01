<?php

require_once __DIR__.'/Autoloader.php';

define('MOCK_SERVER_PORT', 9876);

abstract class Test_Common extends PHPUnit_Framework_TestCase
{
    protected function getProperty($class, $property)
    {
        $class = new ReflectionClass($class);

        $property = $class->getProperty($property);
        $property->setAccessible(true);

        return $property;
    }

    protected function getMethod($class, $method)
    {
        $class = new ReflectionClass($class);

        $method = $class->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }
}
