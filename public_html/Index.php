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
	public $timeZone = "Europe/London";

	/**
	 * This will initiate the core to load the view
	 * according to the uri path, one may also
	 * change the default timezone for the project
	 * by altering the public $timeZone string above
	 * for a list of valid timezones, see:
	 * http://php.net/manual/en/timezones.php
	 * 
	 * @param	na
	 * @author	sbebbington
	 * @date	24 Jan 2017 - 09:49:15
	 * @version	0.0.2
	 * @return	
	 * @todo
	 */
	public function __construct(){
		$this->core	= new \Application\Core\Framework\Core();
		setTimeZone($this->timeZone);
		$this->core->loadPage();
	}
}

/**
 * This will correctly route to the application
 * directory on your server
 *
 * @param	string
 * @author	Shaun
 * @date	2 Feb 2017 - 13:01:13
 * @version	0.0.2
 * @return	string
 * @todo
 */
function serverPath(string $routeTo = ''){
	$_x = str_replace("\\", '/', dirname(__FILE__)) . '/application';
	$_x = str_replace("public_html/", '', $_x);
	$_x .= $routeTo;
	return str_replace("//", '/', $_x);
}

// Creates new instance and therefore initiates the controllers, models and views etc...
$page = new Index();