<?php

namespace Pine\Unit;

class HelloWorldController
{
    public function __invoke($req, $res, $next) {
        echo "Hello World!";
    }

    public function hello($req, $res, $next) {
        echo "Hello World!!";
    }
}