<?php
use Application\Core\Framework\Core;
use Application\Core\FrameworkException\FrameworkException;

require_once(serverPath('/core/FrameworkCore.php'));
require_once(serverPath('/core/GlobalHelpers.php'));

header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
session_set_cookie_params(0, '/', getConfig('cookieDomain'), isHttps(), true);
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
		$this->core	= new Core();
		setTimeZone($this->timeZone);
		try{
			$this->core->loadPage();
		}catch(FrameworkException $e){
			writeToLogFile($e);
		}catch(Exception $e){
			$this->core->lib->debug($e);
			exit;
		}
	}
}

/**
 * This will correctly route to the application
 * directory on your server
 *
 * @param	string
 * @author	Rob Gill && Shaun
 * @date	2 Aug 2017 - 13:47:12
 * @version	0.0.3
 * @return	string
 * @todo
 */
function serverPath(string $routeTo = ''){
	$base_dir = dirname(dirname($_SERVER['SCRIPT_FILENAME']))."/application";
	return "{$base_dir}{$routeTo}";
}

// Creates new instance and therefore initiates the controllers, models and views etc...
$page = new Index();