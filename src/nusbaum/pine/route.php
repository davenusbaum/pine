<?php

namespace nusbaum\pine;

class Route {
	
	/** @var string directory for controller files */
	protected static $controllerDir;
	
	/** @var string Directory for middleware files */
	protected static $middlewareDir;
	
	/** @var Request */
	protected $req;

	/** @var Response */
	protected $res;
	
	/** @var array The invocation stack for this route */
	public $stack;

	/** @var array The parameters pass to the route. */
	public $params;
	
	/** @var string The path that matches this route */
	public $path = '';
	
	/**
	 * Set the controller directory
	 * @param string $dir
	 */
	public static function setControllerDir($dir) {
		self::$controllerDir = $dir;
	}
	
	/**
	 * Set the middleware directory
	 * @param string $dir
	 */
	public static function setMiddlewareDir($dir) {
		self::$middlewareDir = $dir;
	}
	
	/**
	 * Create a Route object
	 */
	public function __construct() {
		
	}
	
	/**
	 * Invoke the route handlers in the stack
	 * @param Request $req
	 * @param Response $res
	 */
	public function __invoke() {
		if(count($this->stack)) {
			$name = array_shift($this->stack);
			if(strlen($name) > 4 && substr_compare($name, '.php',-4,4) === 0) {
				$next = @include($name);
			} else {
				$next = new $name();
			}
			if($next) {
				$next($this->req,$this->res,$this);
			} else if($next === false) {
				trigger_error('Route could not load $name');
			}
		}
		return;
	}
	
	/**
	 * Send the request to the chain of functions that match the route 
	 * @param Request $req
	 * @param Response $res
	 * @param callable $next
	 */
	public function dispatch($req,$res) {
		$this->req = $req;
		$this->res = $res;
		$this($req,$res);
	}
	
	/**
	 * Clear any
	 */
	public function clear() {
		$this->stack = array();
	}
	
	/**
	 * Stack another handler on the stack 
	 * @param string $name the name of a hander class to add to the stack
	 */
	public function stack($name) {
		if(is_array($name)) {
			foreach ($name as $just_one) {
				$this->stack($just_one);	
			}
		} else {
			$this->stack[] = $name;
		}
	}
}