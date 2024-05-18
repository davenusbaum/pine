<?php

namespace Pine;

/**
 * An http request
 */
class Request {

    /**
     * The application that is handling the request
     * @var Application
     */
    public $app;

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
     * The body of the request
     * @var Parameters
     */
    public $body;

    /** @var Parameters */
    public $cookies;
	
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

    /** @var string The remote ip address used for this request. */
    public $ip;
	
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
	
	/** @var Parameters The named route parameters */
	public $params;
	
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

    /**
     * The query parameters
     * @var ArrayMap
     */
    public $query;

    /**
     * @var Response The response for this request
     */
    public $res;
	
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

    /** @var bool True if the protocol is HTTPS */
    public $secure = false;

	
	/** @var array Usually $_SERVER */
	private $server;
	
	/** @var float The request start time with milliseconds */
	private $timestamp;

	/**
	 * Create a new request object
     * @param Application $app The application handling the request
     * @param array $server
     * @param array $get
     * @param array $post
     * @param array $cookie
	 */
	public function __construct($app, $server, $get, $post, $cookie) {
        $this->app = $app;
		$this->server = $server;
		$this->query = new Parameters($get);
        $this->cookies = new Parameters($cookie);
        $this->res = new Response();
		
		// check for trusted proxy
        $trust_proxy = false;
		if ($trusted = $app->get('trust proxy')) {
            if ($trusted === true || is_array($trusted) && in_array($server['REMOTE_ADDR'], $trusted)) {
                $trust_proxy = true;
            }
        }
		
		// set the remote address
		if ($trust_proxy && isset($server['HTTP_X_FORWARDED_FOR'])) {
			$this->ip = $server['HTTP_X_FORWARDED_FOR'];
		}  else {
			$this->ip = $server['REMOTE_ADDR'];
		}
		
		// set the protocol
		if(isset($server['SERVER_PROTOCOL'])) {
			$this->protocol = $server['SERVER_PROTOCOL'];
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
		if ($trust_proxy && isset($server['HTTP_X_FORWARDED_PORT'])) {
			$this->port = $server['HTTP_X_FORWARDED_PORT'];
		} else if(isset($server['SERVER_PORT'])) {
			$this->port = $server['SERVER_PORT'];
		} 
		
		// set the host and include port if not 80 or 443
		if($trust_proxy && isset($server['HTTP_X_FORWARDED_HOST'])) {
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
		if($trust_proxy && isset($server['HTTP_X_FORWARDED_PROTO'])) {
			$this->scheme = $server['HTTP_X_FORWARDED_PROTO'];
		} else if(isset($server['HTTPS']) && $server['HTTPS'] !== 'off') {
			$this->scheme = "https";
		} else if(443 == $this->port ||  8443 == $this->port) {
			$this->scheme = "https";
		} else	{
			$this->scheme = "http";
		}
        if ($this->scheme === 'https') {
            $this->secure = true;
        }
		
		// set original url
		$this->originalUrl = "{$this->scheme}://{$this->host}";
		if(isset($server['REQUEST_URI'])) {
			$this->originalUrl .= $server['REQUEST_URI'];
		}
		
		// set the base url
		$this->baseUrl = "{$this->scheme}://{$this->host}{$this->basePath}";

        // set the body
        if(isset($server['CONTENT_TYPE']) && Str::endsWith($server['CONTENT_TYPE'],'json')) {
            $content = json_decode(file_get_contents('php://input'),1);
            if($content && is_array($content)) {
               $this->body = new Parameters($content);
            } else {
                $this->body = new Parameters();
            }
        } else {
            $this->body = new Parameters($post);
        }

        // set the route
        if (isset($this->mathod) && isset($this->path)) {
            $this->route = $app->router->find($this->method, $this->path);
        }
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
	 public function get($name,$default=null) {
	 	$name = 'HTTP_'.strtoupper(str_replace('-','_',$name));
	 	if(isset($this->server[$name])) {
	 		return $this->server[$name];
	 	}
	 	return $default;
	 }
}
