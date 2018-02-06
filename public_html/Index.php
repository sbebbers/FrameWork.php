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

    /**
     * This will initiate the core to load the view
     * according to the uri path, one may also
     * change the default timezone for the project
     * by altering the public $timeZone string above
     * for a list of valid timezones, see:
     * http://php.net/manual/en/timezones.php
     * 
     * @param   na
     * @author  sbebbington
     * @date    19 Jan 2018 13:38:24
     * @version 0.1.5-RC2
     * @return  void
     */
    public function __construct(){
        $this->core = new Core();
        
        if($this->checkPageLoad() == true){
            $_error = null;
            try{
                setTimeZone(getConfig('timeZone'));
                $this->core->loadPage();
            }catch(FrameworkException $error){
                $_error = $error;
            }catch(Exception $error){
                $_error = $error;
            }
            
            if(!is_null($_error)){
                try{
                    writeToLogFile($_error);
                }catch(Exception $e){
                    echo '<!-- unable to write to error log: ' . print_r($e, true) . ' -->';
                }
            }
        }
    }
    
    /**
     * Check if request is for page view
     * or site asset
     *
     * @param   na
     * @author  sbebbington
     * @date    28 Jul 2017 - 17:03:54
     * @version 0.1.5-RC2
     * @return  boolean
     */
    public function checkPageLoad(){
        $exts = array_filter(
            explode('/', $_SERVER['REQUEST_URI']),
            'strlen'
        );
        $last    = [];
        if (count($exts)) {
            $last   = explode(".", $exts[count($exts)-1] ?? '');
            $last   = (count($last) > 0) ? $last[count($last)-1] : '';
            return in_array($last, $this->core->ignoredExts) ? false : true;
        }
        return true;
    }
}

/**
 * This will correctly route to the application
 * directory on your server
 *
 * @param   string
 * @author  Rob Gill && sbebbington
 * @date    26 Sep 2017 09:50:01
 * @version 0.1.5-RC2
 * @return  string
 */
function serverPath(string $routeTo = ''){
    $baseDir = dirname(__DIR__) . "/application";
    return str_replace("\\","/", "{$baseDir}{$routeTo}");
}

// Creates new instance and therefore initiates the controllers, models and views etc...
$page = new Index();
