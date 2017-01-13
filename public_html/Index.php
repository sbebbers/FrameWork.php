<?php
	use Application\Core\Framework\Core;
	
	require_once(serverPath('/core/FrameworkCore.php'));
	require_once(serverPath('/core/GlobalHelpers.php'));

	header('X-Content-Type-Options: nosniff');
	header('X-XSS-Protection: 1');
	header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
	session_set_cookie_params(0, '/', getDomain(host(), ($https = isHttps()) ), $https, true);
	if(session_id() == ""){
		session_start();
	}
	
	class Index
	{
		protected $core;
		
		/**
		 * Constructor routes all of the allowed URL paths above and sets up some variables
		 * which will hopefully be usable in the views etc...
		 *
		 * @author	Shaun B && Linden
		 * @version	0.0.1
		 * @date	2015-15-02
		 * @param	na
		 * @return 	na
		 * @todo
		 */
		public function __construct(){
			$this->core	= new \Application\Core\Framework\Core();
			$this->core->loadPage();
		}
	}
	
	/**
	 * Determines server path and will include additional route with optional parameter
	 *
	 * @param	string
	 * @author	Shaun
	 * @date	9 Jun 2016 10:04:26
	 * @version	0.0.1
	 * @return	string
	 * @todo
	 */
	function serverPath($routeTo = null){
		$_x = str_replace("\\", "/", dirname(__FILE__)) . '/application';
		$_x = str_replace("public_html/", "", $_x);
		$_x .= ($routeTo === null) ? '' : $routeTo;
		return str_replace("//", "/", $_x);
	}
?>
<?php
	// Creates new instance and therefore initiates the controllers, models and views etc...
	$page = new Index();