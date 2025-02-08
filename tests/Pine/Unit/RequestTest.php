<?php

use PHPUnit\Framework\TestCase;
use Pine\Application;
use Pine\Request;

class RequestTest extends TestCase
{
    public Application $app;

    public function setUp(): void {
        $this->app = new Application();
    }

    public function testPort() {
        $req = new Request(
            $this->app,
            [
                'SERVER_NAME' => 'localhost',
                'SERVER_PORT' => 80,
                'HTTP_HOST' => 'localhost',
                'HTTP_USER_AGENT' => 'Pine',
                'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
                'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                'REMOTE_ADDR' => '127.0.0.1',
                'SCRIPT_NAME' => '',
                'SCRIPT_FILENAME' => '',
                'SERVER_PROTOCOL' => 'HTTP/1.1',
                'REQUEST_TIME' => time(),
                'REQUEST_TIME_FLOAT' => microtime(true),
            ],
            [],
            [],
            []
        );
        $this->assertEquals(80, $req->port);
    }
}