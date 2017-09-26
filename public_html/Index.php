<?php
use Application\Core\Framework\Core;
use Application\Core\FrameworkException\FrameworkException;

require_once(serverPath('/core/FrameworkCore.php'));

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
	 * @date	24 Jan 2017 09:49:15
	 * @version	0.0.2
	 * @return	void
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
 * @author	Rob Gill && sbebbington
 * @date	26 Sep 2017 09:50:01
 * @version	0.0.4
 * @return	string
 */
function serverPath(string $routeTo = ''){
	$baseDir = dirname(__DIR__) . "/application";
	return str_replace("\\","/", "{$baseDir}{$routeTo}");
}

// Creates new instance and therefore initiates the controllers, models and views etc...
$page = new Index();
