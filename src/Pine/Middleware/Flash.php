<?php
namespace Pine\Middleware;

use Pine\ArrayList;
use Pine\Request;
use Pine\Response;

class Flash extends ArrayList {
	const FLASH_KEY = 'flashMessages';

    /**
     * A generator to be added to a pine route.
     * @param Request $req
     * @param Response $res
     * @param callable|null $next
     */
	public function __invoke(Request $req, Response $res, ?Callable $next): void {
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
	 * @param int $type
	 * @param ?string $field
	 */
	public function flash(string $msg, int $type = 1, ?string $field = null): void {
		$fm = new FlashMessage($msg,$type,$field);
		$this->add($fm);
	}
	
	/**
	 * Add a success message to the message list
	 * @param string $msg
	 */
	public function flashSuccess(string $msg): void {
		$this->flash($msg,FlashMessage::SUCCESS);
	}
	
	/**
	 * Add an info message to the message list
	 * @param string $msg
	 */
	public function flashInfo(string $msg): void {
		$this->flash($msg,FlashMessage::INFO);
	}
	
	/**
	 * Add a warning message to the message list
	 * @param string $msg
	 */
	public function flashWarning(string $msg): void {
		$this->flash($msg,FlashMessage::WARNING);
	}

    /**
     * Add an error message to the message list
     * @param string $msg
     * @param string|null $field
     */
	public function flashError(string $msg, ?string $field = null): void {
		$this->flash($msg,FlashMessage::ERROR,$field);
	}
}