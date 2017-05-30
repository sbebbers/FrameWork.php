<?php

namespace Application\Core\Framework;
require_once(serverPath('/core/HtmlBuilder.php'));

class Core extends \Application\Core\Framework\HtmlBuilder
{
	public $segment, $host, $partial, $controller, $title, $description,
	$serverPath, $root, $flash, $filePath, $uriPath, $http;
	
	protected $allowedSegments	= array(
		'home',
	);
	protected $pageController	= array(
		'home'			=> "HomeController",
	);
	private $errorReporting = array(
		"http://framework.php.local",
		"https://framework.php.local"
	);
	private $allowedFileExts	= array(
		'htm', 'html', 'asp', 'aspx', 'js', 'php', 'phtml',
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
		$this->uriPath	= '';
		$page			= array_filter(explode('/', $_SERVER['REQUEST_URI']), 'strlen');
		if(count($page)>1){
			foreach($page as $key => $data){
				if($key != count($page)){
					$this->uriPath	.= "{$data}/";
				}
			}
		}
		$this->segment	= !empty($page) ? strtolower($page[count($page)]) : "";
		
		if(!empty($_GET)){
			$segment 					= explode('?', $this->segment);
			$this->segment				= $segment[0];
			if(isset($segment[1]) && strlen($segment[1])>0){
				$_GET	= array();
				$get	= explode("&", $segment[1]);
				foreach($get as $data){
					$_data				= explode("=", $data);
					$_GET[$_data[0]]	= urldecode($_data[1]);
				}
			}
		}
		
		if(strpos($this->segment, ".") > 0){
			$segments 			= explode(".", $this->segment);				
			if(in_array($segments[count($segments)-1], $this->allowedFileExts)){
				$this->segment		= $segments[count($segments)-2];
				$this->canonical	= "<link rel=\"canonical\" href=\"{$this->host}/{$this->segment}\">" . PHP_EOL;
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
	 * @param	string
	 * @author	sbebbington
	 * @date	2 Feb 2017 - 13:04:10
	 * @version	0.0.2
	 * @return	string
	 * @todo
	 */
	public function setTitle(string $page = ''){
	    $titles = array(
			'home'				=> "Example FrameWork.php skeleton site",
	    );
	    return $titles["{$page}"];
	}
	
	/**
	 * Sets the meta page descriptions in the views
	 * 
	 * @param	string
	 * @author	sbebbington
	 * @date	2 Feb 2017 - 13:04:47
	 * @version	0.0.1
	 * @return	string
	 * @todo
	 */
	public function setDescription(string $page = ''){
	    $descriptions = array(
            'home'				=> "The Skeleton",
	    );
	    return $descriptions["{$page}"];
	}
	
	/**
	 * Bug fixed edition of the using ZF type view variables
	 * added in error surpression to prevent warnings being
	 * logged 
	 * 
	 * @param	resource | \stdClass, string
	 * @author	sbebbington
	 * @date	30 May 2017 - 09:49:39
	 * @version	0.0.4
	 * @return	na
	 * @todo
	 */
	public function setView($instance, string $masterKey = ''){
		foreach($instance as $key => $data){
			if($masterKey == ''){
				@$this->$key				= $data;
			}else{
				@$this->$masterKey->$key	= $data;
			}
		}
	}
	
	/**
	 * Clears the page flash messages as these
	 * are stored in the PHP $_SESSION global
	 * 
	 * @param	boolean
	 * @author	sbebbington
	 * @date	7 Feb 2017 - 15:19:57
	 * @version	0.0.1
	 * @return	resource
	 * @todo
	 */
	public function emptyFlashMessages(bool $emptyFlash){
		return ($emptyFlash === true) ? $_SESSION['flashMessage'] = array() : array();
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
	 * @author	sbebbington
	 * @date	7 Feb 2017 - 15:21:00
	 * @version	0.0.3a
	 * @return	void
	 * @todo
	 */
	public function loadPage(){
		if($this->segment == ""){
			$this->segment		= 'home';
		}
		
		if(in_array($this->segment, $this->allowedSegments) == false || !file_exists(serverPath("/view/{$this->uriPath}{$this->segment}.phtml"))){
			$this->setView(array("_404Error" => 1));
			$this->title		= '404 error - page not found, please try again';
			$this->description	= 'There\'s a Skeleton in the Sandbox';
			require_once(serverPath("/view/404.phtml"));
			exit;
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

			$emptyFlash = false;
			if(isset($_SESSION['flashMessage']) && !empty($_SESSION['flashMessage'])){
				$this->setView($_SESSION['flashMessage'], "flash");
				$emptyFlash = true;
			}
			require_once(serverPath("/view/{$this->uriPath}{$this->segment}.phtml"));
			$this->emptyFlashMessages($emptyFlash);
		}
	}
}
