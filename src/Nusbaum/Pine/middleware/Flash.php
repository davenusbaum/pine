<?php
namespace nusbaum\pine\middleware;

use Nusbaum\Pine\Collection;
use Nusbaum\Pine\Request;
use Nusbaum\Pine\Response;

class Flash extends Collection {
	const FLASH_KEY = 'flashMessages';
	
	/**
	 * A generator to be added to a pine route.
	 * @param Request $req
	 * @param Response $res
	 */
	public function __invoke($req,$res,$next) {
		if($req->session->has(self::FLASH_KEY)) {
			$this->addAll($req->session->get(self::FLASH_KEY));
		}
		$res->locals->set(self::FLASH_KEY, $this);
		$req->session->remove(self::FLASH_KEY);
		$next();
		if($res->isRedirect() && $req->session->isActive()) {
			if(($messages = $res->locals->get(self::FLASH_KEY))
				&& $messages->size() > 0) {
				$req->session->set(self::FLASH_KEY,$this);	
			}
		}
	}
	
	/**
	 * Add a flash message to the message list
	 * @param string $msg
	 * @param number $type
	 * @param string $field
	 */
	public function flash($msg,$type=1,$field=null) {
		$fm = new FlashMessage($msg,$type,$field);
		$this->add($fm);
	}
	
	/**
	 * Add a success message to the message list
	 * @param string $msg
	 */
	public function flashSuccess($msg) {
		$this->flash($msg,FlashMessage::SUCCESS);
	}
	
	/**
	 * Add an info message to the message list
	 * @param string $msg
	 */
	public function flashInfo($msg) {
		$this->flash($msg,FlashMessage::INFO);
	}
	
	/**
	 * Add a warning message to the message list
	 * @param string $msg
	 */
	public function flashWarning($msg) {
		$this->flash($msg,FlashMessage::WARNING);
	}
	
	/**
	 * Add an error message to the message list
	 * @param string $msg
	 */
	public function flashError($msg,$field=null) {
		$this->flash($msg,FlashMessage::ERROR,$field);
	}
	
}