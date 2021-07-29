<?php

class StaticExample
{
    static public $aNum = 0;

    public static function sayHello()
    {
        self::$aNum++;
        print "Здравствуй, (" . self::$aNum . ")!".PHP_EOL;
    }
    public function __destruct()
    {
        self::$aNum+=2;
    }
}
$a = new StaticExample();
$a::sayHello();
unset($a);
StaticExample::sayHello();
StaticExample::sayHello();
StaticExample::sayHello();

