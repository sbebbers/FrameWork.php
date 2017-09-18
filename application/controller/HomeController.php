<?php
use Application\Controller\ControllerCore;
use Application\Model\HomeModel;

class HomeController extends \Application\Controller\ControllerCore
{
	public function __construct(){
		ControllerCore::__construct();
		
		$this->sql				= new HomeModel();
		foreach($this->sql->getView() as $key => $data){
			$key				= $this->lib->convertSnakeCase($key);
			$this->view->$key	= htmlspecialchars_decode($data);
		}
		$this->setFlashMessage('message', "Made you look :-P");
// 		if(isset($this->post['submit'])){
// 			// Do something with the posted data here, but for now
// 			// we'll simply see the contents of the posted data
// 			$this->lib->debug($this->post, true);
// 		}
	}
	
	/**
	 * Tester for the encryption and decryption
	 * library methods (and for my own sanity)
	 * Seems worky.
	 * 
	 * @param	string, string
	 * @author	sbebbington
	 * @date	1 Mar 2017 - 09:20:43
	 * @version	0.0.1
	 * @return	boolean
	 * @todo
	 */
	public function passwordTester(string $password = "Password", string $secret = 'password'){
		$_password				= $this->lib->encryptIt($password, $secret);
		$_decryption 			= $this->lib->decryptIt("{$_password}", $secret);
		return (bool)($_decryption === $password);
	}
}