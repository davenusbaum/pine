<?php
namespace nusbaum\pine;

class Router {
	
	/** @var string directory for controller files */
	protected static $controllerDir;
	
	/** @var string Directory for middleware files */
	protected static $middlewareDir;
	
	protected static $trace = false;
	
	/** @var array A multidimensional array defining the application routes. */
	protected $routes;
	
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
	 * Create a new router for the supplied routes
	 * @param array $routes
	 */
	public function __construct($routes) {
		$this->routes = $routes;
		// set the controller directory, if necessary
		if(!self::$controllerDir) {
			self::$controllerDir = dirname(getcwd()).'/controllers';
		}
		// set the controller directory, if necessary
		if(!self::$middlewareDir) {
			self::$middlewareDir = dirname(getcwd()).'/middleware';
		}
	}

	/**
	 * Return the route for the supplied method and path
	 * @param string $method
	 * @param string $path
	 * @return NULL|\nusbaum\pine\Route
	 */
	public function route($method,$path) {
		
		// start with the root node
		if(isset($this->routes)) {
			$node = $this->routes;
		} else {
			return null;
		}
		
		$route = new Route();
		
		// split the path
		$parts = explode('/',trim($path,"/ \n\r\t\v\0"));
		
		// walk the path
		foreach ($parts as $part) {
			//var_dump($node);
			//var_dump($part);
			// check for exact match
			if(isset($node['children'][$part])) {
				$node = $node['children'][$part];
				$route->path.="/$part";
			} else if($name = $this->findParam($node)) {
				$route->params[substr($name,1)] = $part;
				$node = $node['children'][$name];
				$route->path .= "/$name";
			} else if(isset($node['children']['*'])) {
				$node = $node['children']['*'];
				$route->path .= '/*';
			} else {
				return null;
			}
			// check for classes to be added to the stack
			if(isset($node['use'])) {
				$this->loadMiddleware($route,$node['use']);
			}
		}
		
		if(isset($node[$method])) {
			$this->loadController($route,$node[$method]);
			// stack a GET after a POST so we can fallback on form failures
			if('POST' == $method) {
				if(isset($node['GET'])) {
					$this->loadController($route,$node['GET']);
				}
			}
			trigger_error(print_r($route,1));
			return $route;
		}
		
		return null;
	}
	
	/**
	 * Look for a named parameter 
	 * @param array $branch
	 * @return string|NULL
	 */
	protected function findParam($node) {
		if(isset($node['children']) && is_array($node['children'])) {
			$children = $node['children'];
			foreach ($children as $name => $value) {
				if(strlen($name)>1 && substr_compare($name, ':', 0,1) === 0) {
					return $name;
				}
			}
		}
		return null;
	}
	
	/**
	 * Load a callable component
	 * @param Route $route
	 * @param string $name The name of the component
	 * @param string $path The path to be used for relative names
	 */
	protected function load($route,$name,$path) {
		if(strlen($name) > 4 && substr_compare($name, '.php',-4,4) === 0) {
			if(!str::startsWith($name, '/')) {
				$name = $path.'/'.$name;
			}
		}
		$route->stack($name);
	}
	
	/**
	 * Load a callable controller
	 * @param Route $route
	 * @param string $name
	 */
	protected function loadController($route,$name) {
		$this->load($route,$name,self::$controllerDir);
	}
	
	/**
	 * Load callable middleware
	 * @param Route $route
	 * @param string $name
	 */
	protected function loadMiddleware($route,$name) {
		if(is_array($name)) {
			foreach ($name as $a_name) {
				$this->loadMiddleware($route,$a_name);
			}
			return;
		}
		return $this->load($route,$name,self::$middlewareDir);
	}
	
	public function setTrace($bool=true) {
		self::$trace = $bool ? true : false;
	}
	
}