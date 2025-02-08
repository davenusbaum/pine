<?php
namespace Pine\Middleware;

class FlashMessage {
	const SUCCESS = 0;
	const INFO = 1;
	const WARNING = 2;
	const ERROR = 3;
	
	/** @var string The message text. */
	public string $message;
	
	/** @var int The type of message. */
	public int $type;
	
	/** @var string The name of the input field is appropriate. */
	public string $field;
	
	public function __construct(string $msg, int $type = 1, ?string $field = null) {
		$this->message = $msg;
		if($type === E_USER_NOTICE) {
			$this->type = self::INFO;
		} else if($type === E_USER_WARNING) {
			$this->type = self::WARNING;
		} else if($type === E_USER_ERROR) {
			$this->type = self::ERROR;
		} else {
			$this->type = $type;
		}
		$this->field = $field;
	}
}