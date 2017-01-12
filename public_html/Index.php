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
	
	/**
	 * Project Framework.php MCV v0.0.8
	 * =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	 * This is a fairly simple and fairly stupid MCV framework for PHP 5.4 or later
	 * Simply set up your path in the allowed segments, it will now allow all file
	 * extensions by default (.aspx, .jsp, .html etc...) - this is handled by creating
	 * a canonical tag thing to include in the header.
	 * 
	 * Add in your meta titles and descriptions in the methods below and then get on
	 * with writing the relevant PHP in the correct place.
	 * 
	 * This was developed for the application folder outside of your htdocs or public_html
	 * directory (or other public facing directory), but works with the application folder
	 * within the public facing directory.
	 * 
	 * Structure:
	 * ../application
	 * 		---> controller
	 * 				\ For all of the custom controller methods and logic
	 * 		---> model
	 * 				\ main Db connection and model logic
	 * 		---> library
	 * 				\ Helper functions and other such things
	 * 		---> view
	 * 				\ HTML (with embedded PHP) views
	 * 		---> partial
	 * 				\ For partial HTML views such as headers, footers and menus
	 * 
	 * @version 0.0.8
	 * @date	February - January 2017
	 * @author	Shaun Bebbington (version 0.0.1 to current)
	 * 			&& Linden Bryon (version 0.0.1 to 0.0.7)
	 * @todo
	 * 
	 * @changes	as of 2016-02-19:
	 * 				Added partial views in seperate directory
	 * 				Added model and controller core so all other model and controller classes
	 * 				extend this as required
	 * 				Controller core file will include the library class for all helper functions
	 * 				Default to Bootstrap 3.3.x and jQuery 2.2.x
	 * 				Application folder moved outside of the public-facing directory
	 * 				Admin area added for future use and expansion
	 * 				Fixed: Chooses correct segment (may need changing according to URL structure)
	 * @changes as of 2016-05-12:
	 * 				Fixed: Session Cookie handling correctly for HTTP and HTTPS connections
	 * @changes as of 2016-06-02:
	 * 				Forgot to say that the TimeAndDate class in the library has been fixed
	 * @changes as of 2016-06-09:
	 * 				Removed much of the functionality to the Framework Core class
	 * 				Added global methods for file routing to the application folder
	 * 				$this->_55Number is set up in the core and as available as $this->_55Number,
	 * 				rather than $controllerInstance->_55Number
	 * 				You can now relate controllers to views; if a view has a related controller,
	 * 				the controller is automatically required and an instance of which is initiated
	 * 				in the $this->controller object set in the core
	 * 				Partial views are set in the core under $this->partial['partial-instance'], to use
	 * 				this, use require_once($this->partial['partial-instance']); in the PHP view
	 * @changes as of 2016-09-12:
	 * 				Can now utilise ZF-style view variables if using $this->view->objName in the
	 * 				controllers; this becomes $this->objName in the PHP/HTML view files, meaning
	 * 				you no longer require $this->controller->instance->view->objName - note that
	 * 				anything set in the view object will overwrite any other objects of the same
	 * 				name
	 * @changes as of 2016-09-14:
	 * 				Now has specific flash message for session cookie, use $this->setFlashMessage($k, $v);
	 * 				in the controllers where $k is the key and $v is the value, and to check if the
	 * 				flash message has been set, use $this->getFlashMessage($k), which will check if
	 * 				the key is set. In the view, use $this->flash->$k to get the value.
	 * @changes as of 2017-01-06:
	 * 				Namespaces beginning to be added for more flexibility, renaming project and other
	 * 				tidying up to improve the stuff; as of this date all updates and improvements will
	 * 				be handled by Shaun Bebbington
	 * @changes as of 2017-01-11:
	 * 				Minor refactoring to the methods in the library
	 */
	class Index
	{
		private $allowedFileExts = array(
        	'php', 'html', 'htm', 'xhtml', 'asp', 'aspx', 'jsp', 'js', 'so', 'jspa', 'cgi',
        );
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
			$this->core			= new \Application\Core\Framework\Core();
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