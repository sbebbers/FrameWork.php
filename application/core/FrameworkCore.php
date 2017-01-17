<?php

namespace Application\Core\Framework;
require_once(serverPath('/core/HtmlBuilder.php'));

class Core extends \Application\Core\Framework\HtmlBuilder
{
	public $segment;
	public $host;
	public $allowedSegments	= array(
		'home',
	);
	public $pageController	= array(
		'home'			=> "HomeController",
	);
	public $partial;
	public $controller;
	public $title;
	public $description;
	public $serverPath;
	public $root;
	public $flash;
	public $filePath;
	public $http;
	private $errorReporting = array(
		"http://sandbox.local",
		"https://sandbox.local"
	);
	public $canonical = '';
	
	public function __construct(){
		parent::__construct();
		$this->host	        = isHttps() ? "https://" : "http://";
		$this->host			.= host();
		if(in_array($this->host, $this->errorReporting)){
			error_reporting(-1);
			ini_set('display_errors', '1');
		}
		
		$page			= explode('/', $_SERVER['REQUEST_URI']);
		$pageCount		= count($page) - 1;
		
		$this->segment	= (isset($page[$pageCount]) && strlen($page[$pageCount]) > 1) ? strtolower($page[$pageCount]) : "";
		if(isset($_GET)){
			$segment 					= explode("?", $this->segment);
			$this->segment				= $segment[0];
		}
		
		if(strpos($this->segment, ".") == true){
			$segments 			= explode(".", $this->segment);
			$count				= count($segments) - 1;
			if(in_array($segments[$count], $this->allowedFileExts)){
				$this->segment			= $segments[$count-1];
				$this->core->canonical	= "<link rel=\"canonical\" href=\"{$this->host}/{$this->segment}\">" . PHP_EOL;
			}
		}
		$this->serverPath   = serverPath();
		
		$this->root			= str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
		$this->controller	= new \stdClass();
		$this->flash		= new \stdClass();
		$this->partial 		= array(
	    	'header'	=> serverPath("/view/partial/header.phtml"),
	    	'footer'	=> serverPath("/view/partial/footer.phtml"),
		);
		
	}

	/**
	 * Sets the meta page titles in the views
	 * 
	 * @author	Shaun B
	 * @version	0.0.1
	 * @date	2016-09-02
	 * @param 	string $page
	 * @return	string
	 * @todo
	 */
	public function setTitle($page = ''){
	    $titles = array(
			'home'				=> "Example FrameWork.php skeleton site",
	    );
	    return $titles["{$page}"];
	}
	
	/**
	 * Sets the meta page descriptions in the views
	 * 
	 * @author	Shaun
	 * @version	0.0.1
	 * @date	2016-09-02 
	 * @param	string $page
	 * @return	string
	 * @todo
	 */
	public function setDescription($page = ''){
	    $descriptions = array(
            'home'				=> "The Skeleton",
	    );
	    return $descriptions["{$page}"];
	}
	
	/**
	 * Bug fixed edition of the using ZF type view variables
	 *
	 * @param	array|object
	 * @author	Shaun
	 * @date	14 Sep 2016 09:34:33
	 * @version	0.0.3
	 * @return	na
	 * @todo
	 */
	public function setView($instance, $masterKey = null){
		foreach($instance as $key => $data){
			if($masterKey == null){
				$this->$key = $data;
			}else{
				$this->$masterKey->$key = $data;
			}
		}
	}
	
	/**
	 * This will load the view and related controllers
	 * It has an added exception for Jamie's admin html
	 * template. This version should now allow Zend-alike
	 * view variables - so if you set an object in a page
	 * controller as $this->view->objName, you can use
	 * $this->objName in the PHP/HTML view or something.
	 *
	 * @param	na
	 * @author	Shaun
	 * @date	12 Sep 2016 10:10:02
	 * @version	0.0.2
	 * @return	na
	 * @todo
	 */
	public function loadPage(){
		if((in_array($this->segment, $this->allowedSegments) == false) && $this->segment != ""){
			$this->setView(array("_404Error" => 1));
			$this->title		= '404 error - page not found, please try again';
			$this->description	= 'There\'s a Skeleton in Sandbox';
			require_once(serverPath("/view/404.phtml"));
			exit;
		}
		
		if($this->segment == ""){
			$this->segment		= 'home';
		}
		
		if(in_array($this->segment, $this->allowedSegments) == true){
			$this->title		= $this->setTitle($this->segment);
			$this->description	= $this->setDescription($this->segment);
			foreach($this->pageController as $instance => $controller){
				if($this->segment == $instance){
					require_once(serverPath("/controller/{$controller}.php"));
					$this->controller->$instance = new $controller();
					if(isset($this->controller->$instance->view)){
						$this->setView($this->controller->$instance->view);
						$this->controller->$instance->view = null;
					}
				}
			}
			
			if(isset($_SESSION['flashMessage']) && !empty($_SESSION['flashMessage'])){
				$this->setView($_SESSION['flashMessage'], "flash");
			}
			require_once(serverPath("/view/{$this->segment}.phtml"));
		}
	}
}
