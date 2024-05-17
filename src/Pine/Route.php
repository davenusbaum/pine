<?php

namespace Pine;

class Route {
	
	/** @var array The invocation stack for this route */
	public $stack;

	/** @var array The parameters pass to the route. */
	public $params;
	
	/** @var string The path that matches this route */
	public $path = '';

    /**
     * Invoke the route handlers using a generator
     * @param Request $req
     * @param Response $res
     */
    public function __invoke($req,$res) {
        if(count($this->stack)) {
            $next = array_shift($this->stack);
            if (is_array($next)) {
                $next[0] = new $next[0]();
            } else if (is_string($next)) {
                $next = new $next();
            }
            if($next) {
                foreach($next($req,$res) as $v) {
                    $this($req,$res);
                }
            }
        }
        return;
    }
}