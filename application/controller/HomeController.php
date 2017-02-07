<?php
require_once (serverPath('/controller/ControllerCore.php'));
require_once (serverPath('/model/HomeModel.php'));

class HomeController extends \Application\Controller\ControllerCore
{
	public function __construct(){
		parent::__construct();
		
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
}