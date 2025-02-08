<?php

class HelloWorldController
{
    public function __invoke($req, $res, $next): void {
        echo "Hello World!";
    }

    public function hello($req, $res, $next): void {
        echo "Hello World!!";
    }
}