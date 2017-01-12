<?php
require_once (serverPath('/controller/ControllerCore.php'));
## For when there is a database, don't forget to connect
//require_once (serverPath('/model/HomeModel.php'));

class HomeController extends \Application\Controller\ControllerCore{
	public function __construct(){
		parent::__construct();
		//$this->sql				= new HomeModel();
		$this->view->header		= "FrameWork.php";
		$this->view->subHeader	= "This is a simple OO MVC PHP 5/7 framework";
		$this->view->content	= "<p>This was developed as a teaching tool to mentor a junior PHP developer between March and December 2016. I have decided to continue to develop it as a new framework which will hopefully prove useful to others.</p>"
								. "<p>Despite never being a finished product, it has been pen-tested and deployed on a web-based application. Once you know the system, one can quickly build fairly complex PHP-based web software.</p>";
		
		if(isset($this->post['submit'])){
			// Do something with the posted data here, but for now
			// we'll simply see the contents of the posted data
			$this->lib->debug($this->post, true);
		}
	}
}