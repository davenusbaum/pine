<?php

namespace Pine\Unit;

use PHPUnit\Framework\TestCase;
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
        $route->stack = [function($req, $res){
            echo "Hello World";
        }];
        $req = new Request([],[],[],[]);
        $res = new Response($req);
        $this->expectOutputString('Hello World');
        $route($req, $res);
    }

    public function testRouteClass() {
        $route = new Route();
        $route->stack = [HelloWorldController::class];
        $req = new Request([],[],[],[]);
        $res = new Response($req);
        $this->expectOutputString('Hello World!');
        $route($req, $res);
    }

    public function testRouteClassMethod() {
        $route = new Route();
        $route->stack = [
            [HelloWorldController::class, 'hello']
        ];
        $req = new Request([],[],[],[]);
        $res = new Response($req);
        $this->expectOutputString('Hello World!!');
        $route($req, $res);
    }
}