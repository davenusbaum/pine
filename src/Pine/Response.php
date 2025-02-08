<?php

namespace Pine;

/**
 * An HTTP response to an HTTP request
 * @property-read int $statusCode
 */
class Response {

    /** @var Application */
    public Application $app;
	
	/** @var Request */
	private Request $req;
	
	/** @var ArrayMap */
	public ArrayMap $locals;

    protected int $statusCode = 200;
	
	/**
	 * Create a new response object
	 */
	public function __construct($request) {
		$this->req = $request;
        $this->app = $request->app;
		$this->locals = new ArrayMap();
	}

    public function __get(string $name): mixed {
        return match ($name) {
            'statusCode' => $this->statusCode,
            default => null,
        };
    }
	
	/**
	 * Clear the route and ends processing of the
	 */
	public function end(): void {
        if (isset($this->req->route->stack)) {
            $this->req->route->stack = [];
        }
	}

    /**
     * Returns true if the response is a redirect.
     * @return boolean
     */
    public function isRedirect(): bool {
        return in_array($this->statusCode,[301,302,303,307,308]);
    }

    /**
     * Send the body as json.
     * The content-type is set to application/json
     * @param mixed $json
     */
    public function json(mixed $json): void {
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    /**
     * Send a redirect to the client
     * @param string $to
     * @param ?int $status
     * @return boolean
     */
    public function redirect(string $to, ?int $status = null): bool {

        // make sure headers are not already sent
        if (headers_sent()) {
            return false;
        }

        // check for full redirect URL
        if (!str_contains($to, '://')) {
            //build our own local redirect
            if(substr_compare($to, '/',0,1) !== 0 ) {
                $to = '/'.$to;
            }
            $to = $this->req->baseUrl.$to;
        }

        // status depends on http protocol
        if(!$status) {
            $this->statusCode = $this->req->httpVersion == '1.0' ? 302 : 303;
        }

        // set redirect headers
        http_response_code($this->statusCode);
        header("Location: $to");
        $this->end();
        return true;
    }
	 
    /**
	  * Render the specified page for the current scope
	  * @param string $page
	  */
    public function render(string $page): void {
	 	$this->locals->set('res',$this);
	 	if(isset($this->locals)) {
	 		extract($this->locals->toArray(),EXTR_SKIP);
	 	}
	 	$filename = $this->app->get('views').'/'.$page.'.php';
	 	if(false === @include($filename)) {
	 		trigger_error("Could not load view ($filename)");
	 	}
    }

	 /**
	  * Sets the HTTP status for the response. 
	  * This is a chainable  statusCode().
	  * @param int $code
	  * @param ?string $message optional override of default message
	  * @return Response
	  */
	 public function status(int $code, ?string $message = null): Response {
	 	if(isset($message)) {
	 		header('HTTP/'.$this->req->httpVersion." $code $message");
	 	} else {
	 		http_response_code($code);
	 	}
        $this->statusCode = $code;
	 	return $this;
     }
}
