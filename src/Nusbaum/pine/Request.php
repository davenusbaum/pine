<?php
/**
 * Request.php
 * 
 * @copyright 2020 SchedulesPlus LLC
 */
namespace nusbaum\pine;

/**
 * An http request
 */
class Request {

	/** 
	 * The base path for the application url.
	 * This is equivalent to Apache RewriteBase
	 * @var string  
	 */
	public $basePath;
	
	/** 
	 * The base url for the current request
	 * @var string . 
	 */
	public $baseUrl;
	
	/** 
	 * The content type for this request.
	 * Note: This field is only set on request that provided content,
	 * such as POST requests.
	 * @var string  
	 */
	public $contentType;
	
	/** 
	 * The host name provided for this request.
	 * If there is a trusted proxy then the value of the
	 * X-Forwarded-Host header is used.
	 * @var string
	 */
	public $host;
	
	/** 
	 * Just the hostname from host.
	 * The host property includes the port when a none standard port is used.
	 * @var string The name of the host machine. */
	public $hostname;
	
	/** 
	 * The HTTP method for this request.
	 * @var string  
	 */
	public $method;
	
	/** 
	 * The original url used to make this request
	 * @var string  
	 */
	public $originalUrl;
	
	/** @var Parameters The request paramaters */
	public $parameters;
	
	/** 
	 * The url path, after the base path, for this request. 
	 * @var string 
	 */
	public $path;
	
	/** 
	 * The port used for the request.
	 * @todo Need to safely handle X-Forwarded-Host header
	 * @var int
	 */
	public $port = 80;
	
	/** 
	 * The protocol, including version, used for this request.
	 * @var string 
	 */
	public $protocol;
	
	/** @var string The remote ip address used for this request. */
	public $ip;
	
	/** @var Route */
	public $route;
	
	/** 
	 * The request scheme used by the client.
	 * Uses X-Forwarded-Proto header if the proxy is trusted.
	 * @var string The scheme used for the request. 
	 */
	public $scheme;
	
	/** @var string The full name of the base script for this request. */
	public $scriptName;
	
	/** @var array Usually $_REQUEST */
	public $request;
	
	/** @var array */
	private $attributes;
	
	/** @var array Usually $_SERVER */
	private $server;
	
	/** @var float The request start time with milliseconds */
	private $timestamp;
	
	/** @var array */
	private static $trustedProxies;
	
	/**
	 * Set the ip addresses of the proxies that can be trusted
	 * @param string[] 
	 */
	public static function setTrustedProxies($proxies) {
		self::$trustedProxies = $proxies;
	}

	/**
	 * Create a new request object
	 */
	public function __construct($server,$request) {
		$this->server = $server;
		$this->parameters = new Parameters($request);
		$this->attributes = array();
		
		// see if the remote address is a trusted proxy
		if(isset(self::$trustedProxies) && in_array($server['REMOTE_ADDR'], self::$trustedProxies)) {
			$trustProxy = true;
		} else {
			$trustProxy = false;
		}
		
		// set the remote address
		if($trustProxy && isset($server['HTTP_X_FORWARDED_FOR'])) {
			$this->ip = $server['HTTP_X_FORWARDED_FOR'];
		}  else {
			$this->ip = $server['REMOTE_ADDR'];
		}
		
		// set the protocol
		if(isset($server['SERVER_PROTOCOL'])) {
			$this->protocol = $server['SERVER_PROTOCOL'];
		}
		
		// set the content type
		if(isset($server['CONTENT_TYPE'])){
			$this->contentType = $server['CONTENT_TYPE'];
		}
		
		// set the script name
		if (php_sapi_name() == 'cli-server') {
			$this->scriptName = '';
		} else {
			if(isset($server['PATH_INFO'])
					&& 0 === substr_compare(
							$server['PHP_SELF'],
							$server['PATH_INFO'],
							- ($len=strlen($server['PATH_INFO'])))) {
								$this->scriptName =  substr($server['PHP_SELF'],0,-$len);
							} else {
								$this->scriptName = $server['PHP_SELF'];
							}
		}
		
		// set the base path
		$this->basePath = substr (
				$this->scriptName,
				0,
				strrpos($this->scriptName, '/' ));
		
		// set the path
		$this->path = substr(
				parse_url($server['REQUEST_URI'],PHP_URL_PATH),
				strlen($this->basePath));
		
		//set the port
		if($trustProxy && isset($server['HTTP_X_FORWARDED_PORT'])) {
			$this->port = $server['HTTP_X_FORWARDED_PORT'];
		} else if(isset($server['SERVER_PORT'])) {
			$this->port = $server['SERVER_PORT'];
		} 
		
		// set the host and include port if not 80 or 443
		if($trustProxy && isset($server['HTTP_X_FORWARDED_HOST'])) {
			$this->host = $server['HTTP_X_FORWARDED_HOST'];
		} else if(isset($server['HTTP_HOST'])) {
			$this->host = $server['HTTP_HOST'];
		} else if(isset($server['SERVER_NAME'])) {
			$this->host = $server['SERVER_NAME'];
		}
		if(strpos($this->host,':') === false && 80 != $this->port && 443 != $this->port) {
			$this->host = $this->host.':'.$this->port;
		}
		
		// set the hostname
		if(!empty($this->host) && ($pos = strpos($this->host,':')) > 1) {
			$this->hostname = substr($this->host,0,$pos);
		} else {
			$this->hostname = $this->host;
		}
		
		// set the method
		$this->method = $server['REQUEST_METHOD'];
		
		// set the scheme
		if($trustProxy && isset($server['HTTP_X_FORWARDED_PROTO'])) {
			$this->scheme = $server['HTTP_X_FORWARDED_PROTO'];
		} else if(isset($server['HTTPS']) && $server['HTTPS'] !== 'off') {
			$this->scheme = "https";
		} else if(443 == $this->port ||  8443 == $this->port) {
			$this->scheme = "https";
		} else	{
			$this->scheme = "http";
		}
		
		// set original url
		$this->originalUrl = "{$this->scheme}://{$this->host}";
		if(isset($server['REQUEST_URI'])) {
			$this->originalUrl .= $server['REQUEST_URI'];
		}
		
		// set the base url
		$this->baseUrl = "{$this->scheme}://{$this->host}{$this->basePath}";
	}

	public function __get($name) {
		return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
	}
	
	public function __set($name,$value) {
		$this->attributes[$name] = $value;
	}
	
	 
	 /**
	  * Return the named request header.
	  * @param string $name
	  * @param string $default
	  * @return string
	  */
	 public function getHeader($name,$default=null) {
	 	$name = 'HTTP_'.strtoupper(str_replace('-','_',$name));
	 	if(isset($this->server[$name])) {
	 		return $this->server[$name];
	 	}
	 	return $default;
	 }
	 
	 /**
	  * Return true if the command request is a
	  * @return boolean
	  */
	 public function isPost() {
	 	return ('POST' === $this->method ? true : false);
	 }
	 
	 /**
	  * Set the route for this request
	  * @param Router $router
	  * @return Route
	  */
	 public function route($router) {
	 	$this->route = $router->route($this->method,$this->path);
	 	if(isset($this->route->params)) {
	 		foreach ($this->route->params as $name => $value) {
	 			$this->parameters->set($name,$value);
	 		}
	 	}
	 	return $this->route;
	 }
}
