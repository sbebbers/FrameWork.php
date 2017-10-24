<?php
namespace Application\Core\FrameworkException;
use Exception;

class FrameworkException extends Exception
{
	public function __construct($message, $code = null, $errors = []){
		Exception::__construct($message, (int)$code);
		$error	= [
			'message'	=> $message,
			'code'		=> $code,
		];
		$error	= array_merge($error, $errors);
	
		$this->errorInfo	= $error;
	}
}
