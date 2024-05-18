<?php
/**
 * Response.php
 * 
 * @copyright 2020 SchedulesPlus LLC
 */
namespace Pine;

/**
 * The cope Context supports a simple command driven MVC framework and
 * provides helper functions over PHP's $_ENV, $_SERVER and $_REQUEST
 * superglobal arrays.
 */
class Response {

    /** @var Application */
    public $app;
	
	/** @var Request */
	private $req;
	
	/** @var ArrayMap */
	public $locals;
	
	/**
	 * Create a new response object
	 */
	public function __construct($request) {
		$this->req = $request;
        $this->app = $request->app;
		$this->locals = new ArrayMap();
	}
	
	/**
	 * Clear the route and ends processing of the
	 */
	public function end() {
        if (isset($this->route->stack)) {
            $this->request->route->stack = [];
        }
	}

    /**
     * Send the body as json.
     * The content-type is set to application/json
     * @param mixed $json
     */
    public function json($json) {
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    /**
     * Send a redirect to the client
     * @param string $to
     * @param int $status
     * @return boolean
     */
    public function redirect($to,$status = null) {

        // make sure headers are not already sent
        if (headers_sent()) {
            return false;
        }

        // check for full redirect URL
        if(FALSE === strpos($to, '://')) {
            //build our own local redirect
            if(substr_compare($to, '/',0,1) !== 0 ) {
                $to = '/'.$to;
            }
            $to = $this->req->baseUrl.$to;
        }

        // status depends on http protocol
        if(!$status) {
            $status = strpos($this->req->protocol,'1.1') ? 303 : 302;
        }
        // set redirect headers
        http_response_code($status);
        header("Location: $to");
        $this->end();
        return true;
    }
	 
    /**
	  * Render the specified page for the current scope
	  * @param string $page
	  */
    public function render($page) {
	 	$this->locals->set('res',$this);
	 	if(isset($this->locals)) {
	 		extract($this->locals->toArray(),EXTR_SKIP);
	 	}
	 	$filename = $this->app->get('views').'/'.$page.'php';
	 	if(false === @include($filename)) {
	 		trigger_error("Could not load view ($filename)");
	 	}
    }

	 /**
	  * Sets the HTTP status for the response. 
	  * This is a chainable  statusCode().
	  * @param int $code
	  * @param string optional override of default message
	  * @return Response
	  */
	 public function status($code,$message = null) {
	 	if(isset($message)) {
	 		header($this->request->protocol." $code $message");
	 	} else {
	 		http_response_code($code);
	 	}
	 	return $this;
     }
}
