<?php

use PHPUnit\Framework\TestCase;
use Pine\Application;
use Pine\Request;
use Pine\Response;
use Pine\Route;

class RouteTest extends TestCase
{
    public function setUp(): void {
        include_once 'HelloWorldController.php';
    }

    public function testRouteFunction() {
        $route = new Route();
        $route->stack = [function($req, $res, $next){
            echo "Hello World";
        }];
        $app = new Application();
        $req = new Request($app, [], [], [], []);
        $res = new Response($req);
        $this->expectOutputString('Hello World');
        $route->run($req, $res);
    }

    public function testRouteClass() {
        $route = new Route();
        $route->stack = [HelloWorldController::class];
        $app = new Application();
        $req = new Request($app, [],[],[],[]);
        $res = new Response($req);
        $this->expectOutputString('Hello World!');
        $route->run($req, $res);
    }

    public function testRouteClassMethod() {
        $route = new Route();
        $route->stack = [
            [HelloWorldController::class, 'hello']
        ];
        $app = new Application();
        $req = new Request($app, [], [], [], []);
        $res = new Response($req);
        $this->expectOutputString('Hello World!!');
        $route->run($req, $res);
    }
}