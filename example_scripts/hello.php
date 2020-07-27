<?php

$name = $argv[1];

class Helloer{
    protected $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function sayHello():void{
        echo "Hello " . $this->name . PHP_EOL;
    }
}

(new Helloer($name))->sayHello();