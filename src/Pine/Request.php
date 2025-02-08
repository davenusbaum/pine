<?php

namespace Pine;

/**
 * An HTTP request
 */
class Request {

    /** @var Application The application handling the request */
    public Application $app;

    public string $baseUrl;

    /**
     * The body of the request
     * @var Body
     */
    public Body $body;

    /** @var ArrayMap */
    public ArrayMap $cookies;

    /**
     * The content type for the request.
     * This value is usually empty for GET requests
     * @var string|null
     */
    protected ?string $content_type;

    public string $hostname = '';

    public string $httpVersion;

    /**
     * Contains the remote IP address of the request.
     *
     * When the trust proxy setting does not evaluate to false, the value of
     * this property is derived from the left-most entry in the X-Forwarded-For
     * header.
     *
     * @var string
     */
    public string $ip;

    /**
     * If the `trust proxy`, setting evaluates to true, this property contains
     * an array of IP addresses specified in the X-Forwarded-For request header.
     * Otherwise, it contains an empty array.
     * @var string[]
     */
    public array $ips;

    /**
     * The HTTP method for the request
     * @var string
     */
    public string $method;

    public string $originalUrl;

	/** @var ArrayMap The named route parameters */
	public ArrayMap $params;

    /**
     * The url path for the request (after the base path)
     * @var string
     */
    public string $path;

    public int $port;

    public string $protocol;

    /**
     * The query parameters
     * @var ArrayMap
     */
    public ArrayMap $query;

    /**
     * @var Response The response for this request
     */
    public Response $res;
	
	/** @var Route|null */
	public ?Route $route;

    /** @var array */
    private array $props;

    public bool $secure;
	
	/** @var array Usually $_SERVER */
	private array $server;
	
	/** @var float The request start time with milliseconds */
	private float $timestamp;

	/**
	 * Create a new request object
     * @param Application $app The application handling the request
     * @param array $server
     * @param array $get
     * @param array $post
     * @param array $cookie
	 */
	public function __construct(Application $app, array $server, array $get, array $post, array $cookie) {
        $this->props = [];
        $this->app = $app;
		$this->server = $server;
		$this->query = new ArrayMap($get);
        $this->cookies = new ArrayMap($cookie);
        $this->res = new Response($this);

        // set the content type
        $this->content_type = $server['CONTENT_TYPE'] ?? null;

        // set the method
        $this->method = $server['REQUEST_METHOD'] ?? 'GET';

        // set the script name
        if (php_sapi_name() == 'cli-server') {
            $script_name = '';
        } else {
            $php_self = $this->server['PHP_SELF'] ?? '';
            $path_info = $this->server['PATH_INFO'] ?? '';
            $path_info_len = strlen($path_info);
            if ($path_info && 0 === substr_compare($php_self, $path_info, -$path_info_len)) {
                $script_name = substr($php_self, 0, -$path_info_len);
            } else {
                $script_name = $php_self;
            }
        }

        // set the baseUrl
        $this->baseUrl = substr (
            $script_name,
            0,
            strrpos($script_name, '/' )
        );

        // set the path
        $this->path = substr(
            parse_url($this->server['REQUEST_URI'],PHP_URL_PATH),
            strlen($this->baseUrl)
        );

        // set the body
        if(isset($this->content_type) && str_ends_with($this->content_type,'json')) {
            $content = json_decode(file_get_contents('php://input'),1);
            if($content && is_array($content)) {
               $this->body = new Body($content);
            } else {
                $this->body = new Body();
            }
        } else {
            $this->body = new Body($post);
        }

        // set trust_proxy
        $trust_proxy = false;
        if ($trusted = $this->app->get('trust proxy')) {
            if ($trusted === true || is_array($trusted) && in_array($this->server['REMOTE_ADDR'], $trusted)) {
                $trust_proxy = true;
            }
        }

        // set hostname
        if ($trust_proxy && isset($this->server['HTTP_X_FORWARDED_HOST'])) {
            $this->hostname = $this->server['HTTP_X_FORWARDED_HOST'];
        } else if (isset($this->server['HTTP_HOST'])) {
            $this->hostname = $this->server['HTTP_HOST'];
        } else if (isset($this->server['SERVER_NAME'])) {
            $this->hostname = $this->server['SERVER_NAME'];
        }
        if (($pos = strpos($this->hostname,':')) !== false) {
            if (($port = intval(substr($this->hostname, $pos + 1))) > 0) {
                $this->port = $port;
            }
            $this->hostname = substr($this->hostname,0, $pos);
        }

        // set the port
        if ($trust_proxy && isset($this->server['HTTP_X_FORWARDED_PORT'])) {
            $this->port = intval($this->server['HTTP_X_FORWARDED_PORT']);
        } else {
            $this->port = $this->port ?? $this->server['SERVER_PORT'] ?? 80;
        }

        // set the protocol
        if($trust_proxy && isset($this->server['HTTP_X_FORWARDED_PROTO'])) {
            $this->protocol = $this->server['HTTP_X_FORWARDED_PROTO'];
        } else if(isset($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') {
            $this->protocol =  'https';
        } else if(443 == $this->port ||  8443 == $this->port) {
            $this->protocol = 'https';
        } else {
            $this->protocol = 'http';
        }

        // set secure
        $this->secure = $this->protocol === 'https';

        // set originalUrl
        $this->originalUrl = $this->protocol.'://'.$this->hostname;
        if (80 != $this->port && 443 != $this->port) {
            $this->originalUrl .= ':' . $this->port;
        }
        if(isset($this->server['REQUEST_URI'])) {
            $this->originalUrl .= $this->server['REQUEST_URI'];
        }

        // set ips
        if ($trust_proxy && isset($this->server['HTTP_X_FORWARDED_FOR'])) {
            $this->ips = array_diff(
                array_map(
                    'trim',
                    explode(
                        ',',
                        $this->server['HTTP_X_FORWARDED_FOR']
                    )
                ),
                $this->app->get('trusted proxies')
            );
        } else {
            $this->ips = [];
        }

        // set ip
        if ($trust_proxy && !empty($this->ips)) {
            $this->ip = reset($this->ips);
        } else {
            $this->ip = $this->server['REMOTE_ADDR'] ?? '127.0.0.1';
        }

        if (isset($this->server['SERVER_PROTOCOL']) && strlen($this->server['SERVER_PROTOCOL']) > 5) {
            $this->httpVersion = substr($this->server['SERVER_PROTOCOL'], 5);
        } else {
            $this->httpVersion = '1.0';
        }

        // set the route
        if ($this->method) {
            $this->route = $app->router->match($this->method, $this->path);
        }
	}

    /**
     * Magic method to get a property
     * @param string $name
     * @return mixed
     */
	public function __get(string $name): mixed {
        return $this->props[$name] ?? null;
    }

    /**
     * Magic method to set a property
     * @param string $name
     * @param mixed $value
     * @return void
     */
	public function __set(string $name, mixed $value) {
		$this->props[$name] = $value;
	}

    /**
     * Return the named request header.
     * @param string $name
     * @param string|null $default
     * @return string|null
     */
    public function get(string $name, ?string $default = null): ?string {
        $name = 'HTTP_'.strtoupper(str_replace('-','_',$name));
        return $this->server[$name] ?? $default;
    }
}
