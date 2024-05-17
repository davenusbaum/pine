<?php

namespace Pine;

class Router extends \Jaunt\Router {
    /**
     * Returns the route that matches the provided method and path
     * @param string $method
     * @param string $path
     * @return Route|null
     */
    public function find($method, $path) {
        $found = parent::find($method, $path);
        if ($found) {
            $route = new Route();
            $route->params = $found['params'];
            $route->stack = $found['stack'];
            return $found;
        }
        return null;
    }
}