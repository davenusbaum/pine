<?php

namespace Pine;

/**
 * An HTTP request
 *
 * @property string $basePath The base path for the application
 * @property string $host The host, which may include the port
 * @property string $hostname Just the host name
 * @property string $ip Returns the remote client IP address
 * @property string $ips The IP address plus the IP addresses of any untrusted
 * @property string $method The request method
 * @property string $originalUrl
 * @property string $path The url path for the request (after the base path)
 * @property int $port The port that received the request
 * @property string $protocol The request protocol string
 * @property bool $secure True if the protocol is https
 * @property string $scriptName
 * @property string $trustProxy True if the proxy can be trusted
 */
class Request {

    /** @var Application The application handling the request */
    public $app;

    /**
     * The body of the request
     * @var Parameters
     */
    public $body;

    /** @var Parameters */
    public $cookies;

	/** @var Parameters The named route parameters */
	public $params;

    /**
     * The query parameters
     * @var Parameters
     */
    public $query;

    /**
     * @var Response The response for this request
     */
    public $res;
	
	/** @var Route */
	public $route;

    /** @var array */
    private $props;
	
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
        $this->props = [];
        $this->app = $app;
		$this->server = $server;
		$this->query = new Parameters($get);
        $this->cookies = new Parameters($cookie);
        $this->res = new Response($this);

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
        if ($this->method && !is_null($this->path)) {
            $this->route = $app->router->match($this->method, $this->path);
        }
	}

    /**
     * Magic method to get a property
     * @param string $name
     * @return mixed
     */
	public function __get($name) {
        if (isset($this->props[$name])) {
            return $this->props[$name];
        }
        $method_name = 'get'. ucfirst($name);
        if (method_exists($this, $method_name)) {
            return $this->props[$name] = $this->$method_name();
        }
        return null;
	}

    /**
     * Magic method to set a property
     * @param string $name
     * @param mixed $value
     * @return void
     */
	public function __set($name,$value) {
		$this->props[$name] = $value;
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

    protected function getBasePath(): string {
        $scriptName = $this->scriptName;
        return substr (
            $scriptName,
            0,
            strrpos($scriptName, '/' ));
    }

    protected function getBaseUrl(): string {
        return "{$this->protocol}://{$this->host}{$this->basePath}";
    }

    protected function getHost(): string {
        $host = '';
        if ($this->trustProxy && isset($this->server['HTTP_X_FORWARDED_HOST'])) {
            $host = $this->server['HTTP_X_FORWARDED_HOST'];
        } else if (isset($this->server['HTTP_HOST'])) {
            $host = $this->server['HTTP_HOST'];
        } else if (isset($this->server['SERVER_NAME'])) {
            $host = $this->server['SERVER_NAME'];
        }
        $port = $this->port;
        if(strpos($host,':') === false && 80 != $port && 443 != $port) {
            $host .= ':' . $port;
        }
        return $host;
    }

    protected function getHostname(): string {
        $host = $this->host;
        if(!empty($host) && ($pos = strpos($host,':')) > 1) {
            return substr($host,0,$pos);
        }
        return $host;
    }

    /**
     * Returns the remote client IP address
     * @return string|null
     */
    protected function getIp(): ?string {
        return $this->ips[0];
    }

    /**
     * If there is trusted proxy and an X-Forwarded-For header, the method returns
     * the address in the X-Forwarded-For header minus the trusted proxies.
     * If there is no trusted proxy, the REMOTE_ADDR is the only IP returned.
     * @return array
     */
    protected function getIps(): array {
        $ips = null;
        if ($this->trustProxy && isset($this->server['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $this->server['HTTP_X_FORWARDED_FOR']);
            $ips = array_map('trim', $ips);
            $ips = array_diff($ips, $this->app->get('trusted proxies'));
        }
        if (empty($ips)) {
            $ips = [$this->server['REMOTE_ADDR'] ?? null];
        }
        return $ips;
    }

    protected function getMethod(): string {
        return $this->server['REQUEST_METHOD'];
    }

    protected function getOriginalUrl(): string {
        $originalUrl = "{$this->protocol}://{$this->host}";
        if(isset($this->server['REQUEST_URI'])) {
            $originalUrl .= $this->server['REQUEST_URI'];
        }
        return $originalUrl;
    }

    protected function getPath(): string {
        return substr(
            parse_url($this->server['REQUEST_URI'],PHP_URL_PATH),
            strlen($this->basePath));
    }

    /**
     * Returns the PORT that received the request
     * @return int|null
     */
    protected function getPort(): ?int {
        if ($this->trustProxy && isset($this->server['HTTP_X_FORWARDED_PORT'])) {
            return intval($this->server['HTTP_X_FORWARDED_PORT']);
        }
        return $this->server['SERVER_PORT'] ?? 80;
    }

    protected function getProtocol(): string {
        if($this->trustProxy && isset($this->server['HTTP_X_FORWARDED_PROTO'])) {
            return $this->server['HTTP_X_FORWARDED_PROTO'];
        } else if(isset($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') {
            return "https";
        } else if(443 == $this->port ||  8443 == $this->port) {
            return "https";
        }
        return "http";
    }

    protected function getSecure(): bool {
        if ($this->protocol == 'https') {
            return true;
        }
        return false;
    }

    protected function getScriptName(): string {
        // set the script name
        if (php_sapi_name() == 'cli-server') {
           return '';
        }
        $php_self = $this->server['PHP_SELF'] ?? '';
        $path_info = $this->server['PATH_INFO'] ?? '';
        $path_info_len = strlen($path_info);
        if ($path_info && 0 === substr_compare($php_self, $path_info, -$path_info_len)) {
            return substr($this->server['PHP_SELF'], 0, -$path_info_len);
        }
        return $php_self;
    }

    /**
     * Returns true if the proxy can be trusted
     * @return bool
     */
    protected function getTrustProxy(): bool {
        if ($trusted = $this->app->get('trust proxy')) {
            if ($trusted === true || is_array($trusted) && in_array($this->server['REMOTE_ADDR'], $trusted)) {
                return true;
            }
        }
        return false;
    }
}
