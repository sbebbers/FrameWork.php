<?php
namespace Application\Core\FrameworkException;

class FrameworkException extends \Exception
{
	public $error;
	
	public function __construct($message, $code = null, $errors = []){
		parent::__construct($message, (int)$code);
		$error	= [
			'message'	=> $message,
			'code'		=> $code,
		];
		$error	= array_merge($error, $errors);
	
		$this->error	= $error;
	}
}