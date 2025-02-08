<?php

namespace Pine;

/**
 * A fast tree based router
 */
class Router extends \Jaunt\Router {
    /**
     * Returns the route that matches the provided method and path
     * @param string $method
     * @param string $path
     * @return Route|null
     */
    public function match($method, $path): ?Route {
        $found = parent::match($method, $path);
        if ($found) {
            $route = new Route();
            $route->path = $found['path'];
            $route->params = $found['params'];
            $route->stack = $found['stack'];
            return $route;
        }
        return null;
    }
}