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

	/** @var string The directory for the application views */
	private static $viewDir;
	
	/** @var Request */
	private $request;
	
	/** @var array Response variables for the current request. */
	private $state;
	
	/** @var Collection */
	public $locals;
	
	/**
	 * Set the directory for the application views
	 * @param string $name
	 */
	public static function setViewDir($name) {
		self::$viewDir = $name;
	}
	
	/**
	 * Create a new response object
	 */
	public function __construct($request) {
		$this->request = $request;
		$this->state = array();
		$this->locals = new Collection();
	
		// set the view directory, if necessary
		if(!self::$viewDir) {
			self::$viewDir = dirname(getcwd()).'/views';
		}
	}
	
	/**
	 * Clear the route and ends processing of the
	 */
	public function end() {
		$this->request->route->clear();
	}
	
	/**
	 * Get a named value from the context state.
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($name,$default = null) {
		return (isset($this->state[$name]) ? $this->state[$name] : $default);
	}
	
	 /**
	  * Return an array of messages that have been sent in this context
	  * @return array
	  */
	 public function getMessages() {
	 	if(!isset(self::$messages)) {
	 		// get any messages stored in the session
	 		if(self::$messages = self::getAttribute(self::MESSAGES)) {
	 			self::setAttribute(self::MESSAGES, null);
	 		} else {
	 			self::$messages = array();
	 		}
	 	}
	 	return self::$messages;
	 }
	 
	 /**
	  * Returns the current response status code
	  * @return int
	  */
	 public function getStatusCode() {
	 	return http_response_code();
	 }
	 
	 /**
	  * Return if the named value exists
	  * @param string $name
	  * @return boolean
	  */
	 public function has($name) {
	 	return isset($this->state['$name']);
	 }
	 
	 /**
	  * Build a url for a href
	  * @param array|string $path
	  * @param array $query
	  * @return string
	  */
	 public function href($path,$query=null) {
	 	$url = $this->request->baseUrl;
	 	if(is_array($path)) {
	 		foreach ($path as $element) {
	 			$url.='/'.$element;
	 		}
	 	} else {
	 		$url.=$path;
	 	}
	 	if(is_array($query) && count($query)) {
	 		$url .= '?'.http_build_query($query);
	 	}
	 	return $url;
	 }
	 
	 /**
	  * Returns true if the command is being redirected.
	  * @return boolean
	  */
	 public function isRedirect() {
	 	return in_array($this->getStatusCode(),[301,302,303,307,308]);
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
	 	$filename = self::$viewDir.'/'.$page;
	 	if(false === @include($filename)) {
	 		trigger_error("Could not load $filename");
	 	}
	 }
	 
	 /**
	  * Send the body as json.
	  * The content-type is set to application/json
	  * @param mixed $json
	  */
	 public function sendJson($json) {
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
	 	
	 	// save messages to the session
	 	//if(self::hasMessages() && self::hasAttributes()) {
	 	//    self::setAttribute(self::MESSAGES, self::getMessages());
	 		//}
	 	
	 		// check for full redirect URL
	 		if(FALSE === strpos($to, '://')) {
	 			//build our own local redirect
	 			if(substr_compare($to, '/',0,1) !== 0 ) {
	 				$to = '/'.$to;
	 			}
	 			$to = $this->baseUrl.$to;
	 		}
	 		
	 		// status depends on http protocol
	 		if(!$status) {
	 			$status = strpos($this->server['SERVER_PROTOCOL'],'1.1') ? 303 : 302;
	 		}
	 		// set redirect headers
	 		http_response_code($status);
	 		header("Location: $to");
	 		$this->end();
	 		return true;
	 }
	 
	 /**
	  * Send the status to the client.
	  * @param int $status_code
	  * @param string $message The message to be sent with the status code.
	  */
	 public function sendError($status_code,$message=null) {
	 	http_response_code($status_code);
	 	echo $message;
	 }
	 
	 /**
	  * Send an http status code
	  * @param int $status_code
	  */
	 public function sendStatus($status_code) {
	 	http_response_code($status_code);
	 }
	 
	 /**
	  * Set a global value.
	  * @param string $name
	  * @param mixed $value
	  * @return mixed
	  */
	 public function set($name,$value) {
	 	return ($this->state[$name] = $value);
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
	 
	 /**
	  * Set the status code and end the response processing.
	  * @param int $code
	  * @param string $message optional override message
	  */
	 public function statusCode($code,$message=null) {
	 	$this->status($code,$message)->end();
	 }
}
