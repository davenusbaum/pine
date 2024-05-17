<?php
/**
 * Session.php
 *
 * @copyright 2020 SchedulesPlus LLC
 */
namespace Nusbaum\Pine\Middleware;

use Nusbaum\Pine\Collection;
use Nusbaum\Pine\Request;
use Nusbaum\Pine\Response;

class Session extends Collection {
	
	public $active = false;
	
	/**
	 * Start a session to store persistent attributes
	 * @return boolean
	 */
	public function __construct() {
		if((PHP_SESSION_ACTIVE == ($status = session_status()))
			|| (PHP_SESSION_NONE == $status && session_start())) {
			parent::__construct(null,$_SESSION);
		} else {
			parent::__construct();
			trigger_error('Session cannot be started');
		}
	}
	
	/**
	 *
	 * @param Request $req
	 * @param Response $res
	 * @param callable
	 */
	public function __invoke($req,$res,$next) {
		if($this->isActive()) {
			$req->session = $this;
			$next();
		} else {
			trigger_error('Session cannot be started');
			$res->sendStatus(500);
		}
	}
	
	/**
	 * Clear the current context.
	 */
	public function clear() {
		if(session_status() === PHP_SESSION_ACTIVE) {
			session_unset();
		}
	}
	
	
	 /**
	  * Returns the session id.
	  * Returns null when there is no session, unlike session_id()
	  * which returns an empty string.
	  */
	 public function getId() {
	 	if(empty($id = session_id())) {
	 		return null;
	 	}
	 	return $id;
	 	
	 }
	 
	 
	 /**
	  * Invalidate the session for this context.
	  */
	 public function destroy() {
	 	//remove session cookie from browser
	 	if ( isset( $_COOKIE[session_name()] ) ) {
	 		setcookie( session_name(), "", time()-3600, "/" );
	 	}
	 	//clear session
	 	$this->clear();
	 	//clear session from disk
	 	session_destroy();
	 }
	 
	 /**
	  * Returns true if the session is active
	  * @return boolean
	  */
	 public static function isActive() {
	 	if(PHP_SESSION_ACTIVE == session_status()) {
	 		return true;
	 	}
	 	return false;
	 }

	 
	 
	 
}
