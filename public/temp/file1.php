<?php

class S
{
    private static $_instance = null;
    private $_x;

    public function getX ()
    {
        return $this->_x;
    }
    public function setX ($x)
    {
        $this->_x = $x;
    }

    public static function getInstance ()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    private function __construct ()
    {
        $this->setX(1);
    }
}

$s1 = S::getInstance();
$s2 = S::getInstance();

/*
вставьте сюда Ваш код
создавать новые классы запрещено
*/
$s2 = clone $s2;
$s2->setX(2);


echo $s1->getX(); // должно вывести 1
echo $s2->getX(); // должно вывести 2
