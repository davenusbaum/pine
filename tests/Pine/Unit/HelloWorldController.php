<?php

namespace Pine\Unit;

class HelloWorldController
{
    public function __invoke($req, $res) {
        echo "Hello World!";
    }

    public function hello($req, $res) {
        echo "Hello World!!";
    }
}