<?php

namespace Pine;

/**
 * A route that was matched by the router
 */
class Route {
	
	/** @var array The invocation stack for this route */
	public $stack;

	/** @var array The parameters pass to the route. */
	public $params;
	
	/** @var string The path that matches this route */
	public $path = '';

    /** @var Request|null */
    protected $req;

    /** @var Response|null */
    protected $res;

    /**
     * Run a request and response through this route
     * @param Request $req
     * @param Response $res
     * @return void
     */
    public function run($req, $res) {
        $this->req = $req;
        $this->res = $res;
        $this();
    }

    /**
     * Invoke the next handler for this route
     */
    public function __invoke() {
        if(count($this->stack)) {
            $next = array_shift($this->stack);
            if (is_array($next)) {
                $next[0] = new $next[0]();
            } else if (is_string($next)) {
                $next = new $next();
            }
            call_user_func($next, $this->req, $this->res, $this);
        }
    }
}