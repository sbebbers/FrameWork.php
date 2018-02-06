<?php
use Application\Core\FrameworkException\FrameworkException;

/**
 * Will return the specific site parameter from
 * the site.json config - will default to the
 * baseURL setting if an empty string
 * is sent, or cookieDomain if no parameter
 * is sent
 * 
 * @param   string
 * @author  sbebbington
 * @date    2 Jan 2018 10:48:15
 * @version 0.1.5-RC2
 * @return  string
 * @throws  FrameworkException
 */
function getConfig(string $parameter= ''){
    if(!file_exists(serverPath("/config/site.json"))){
        throw new FrameworkException("A site.json file is required in the configuration at the application level for the framework to run", 0x02);
    }
    if(empty($parameter)){
        $parameter = 'baseURL';
    }
    return json_decode(file_get_contents(serverPath("/config/site.json")), true)[$parameter] ?? null;
}

/**
 * This will check to see if the server is running
 * http or https. This parameter is now set in the
 * site.json file in the application config
 * 
 * @param   na
 * @author  sbebbington
 * @date    27 Jul 2017 15:35:10
 * @version 0.1.5-RC2
 * @return  boolean
 */
function isHttps(){
    return getConfig('protocol') == 'https';
}

/**
 * Gets the path to the public facing directory
 * To include further file paths, include a following
 * / at the end
 *
 * @param   string, string
 * @author  sbebbington
 * @date	21 Nov 2017 09:31:00
 * @version 0.1.5-RC2
 * @return  string
 */
function documentRoot(string $routeTo = '', string $replace = 'public_html'){
    $baseDir = str_replace("\\", "/", dirname(__FILE__));
    $baseDir = str_replace("application/core", $replace, $baseDir);
    return str_replace("//", "/", $baseDir . $routeTo);
}

/**
 * Returns the name of the current host file from
 * the PHP $_SERVER global thing #n00b
 *
 * @param   na
 * @author  sbebbington
 * @date    5 Oct 2016 10:55:01
 * @version 0.1.5-RC2
 * @return  string
 */
function host(){
    return "{$_SERVER['HTTP_HOST']}";
}

/**
 * Sets time zone, for a full list, see
 * http://php.net/manual/en/timezones.php
 * 
 * @param   string
 * @author  sbebbington
 * @date    24 Jan 2017 09:48:21
 * @version 0.1.5-RC2
 * @return  void
 */
function setTimeZone(string $timeZone){
    date_default_timezone_set($timeZone);
}

/**
 * Gets user IP address
 *
 * @param   na
 * @author  sbebbington
 * @date    2 Feb 2017 - 09:58:21
 * @version 0.1.5-RC2
 * @return  string
 */
function getUserIPAddress(){
    $client     = $_SERVER['HTTP_CLIENT_IP'] ?? '';
    $forward    = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    $ip         = $_SERVER['REMOTE_ADDR'] ?? '';
    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip     = $client;
    }else if(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip     = $forward;
    }
    return $ip;
}

/**
 * Returns the IP address that the framework
 * is running on
 *
 * @param   na
 * @author  sbebbington
 * @date    2 Jan 2018 10:48:58
 * @version 0.1.5-RC2
 * @return  string
 */
function getServerIPAddress(){
    return $_SERVER['SERVER_ADDR'];
}

/**
 * Returns the current or default URI segment
 *
 * @param   na
 * @author  sbebbington
 * @date    6 Feb 2017 - 11:40:40
 * @version 0.1.5-RC2
 * @return  string
 */
function getSegment(){
    $page    = array_filter(explode('/', $_SERVER['REQUEST_URI']), 'strlen');
    return !empty($page) ? strtolower($page[count($page)]) : 'home';
}

/**
 * Returns PHP_SELF
 * 
 * @param   na
 * @author  sbebbington
 * @date    2 Jan 2018 10:49:31
 * @version 0.1.5-RC2
 * @return  string
 */
function getSelf(){
    return $_SERVER['PHP_SELF'];
}

/**
 * Returns the server query string
 *
 * @param   na
 * @author  sbebbington
 * @date    2 Mar 2017 - 13:23:08
 * @version 0.1.5-RC2
 * @return  string
 */
function getQueryString(){
    return str_replace("/", '', $_SERVER['QUERY_STRING']);
}

/**
 * Returns the version number of the application
 * as set in the site.json config
 *
 * @param   na
 * @author  sbebbington
 * @date    27 Jul 2017 - 16:02:26
 * @version 0.1.5-RC2
 * @return  string
 */
function getSiteVersion(){
    return getConfig('version');
}

/**
 * If rc is set as true in the site.json config
 * then this is a release candidate, else it isn't
 *
 * @param   na
 * @author  sbebbington
 * @date    27 Jul 2017 - 16:03:33
 * @version 0.1.5-RC2
 * @return  bool
 */
function isReleaseCandidate(){
    return getConfig('rc');
}

/**
 * States whether or not is a development
 * version set in the site.json config
 *
 * @param   na
 * @author  sbebbington
 * @date    27 Jul 2017 - 16:05:23
 * @version 0.1.5-RC2
 * @return  bool
 */
function isDevelopmentVersion(){
    return getConfig('dev');
}

/**
 * Gets the configuration path
 *
 * @param   string
 * @author  Rob Gill && sbebbington
 * @date    16 Aug 2017 - 17:09:28
 * @version 0.1.5-RC2
 * @return  string
 */
function logErrorPath(string $routeTo = ''){
    $baseDir = dirname(__DIR__) . "/logs";
    return str_replace("\\","/", "{$baseDir}{$routeTo}");
}

/**
 * Writes a log file based on date
 * and day; if log file already exists
 * then it will append the data to the
 * file;
 *
 * @param   array | mixed
 * @author  sbebbington
 * @date    19 Oct 2018 13:38:49
 * @version 0.1.5-RC2
 * @return  resource | false
 * @throws  Exception
 */
function writeToLogFile($error = []){
    if(empty($error)){
        return false;
    }
    $error  = is_array($error) ? $error : [$error];
    
    $months = [1 => 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
    
    if(empty($error['ip_address'])){
        $error['ip_address']    = getUserIPAddress();
    }
    
    if(empty($error['date'])){
        $error['date']          = date("Y-m-d");
        $error['time']          = date("H:i:s");
    }
    $fileNames  = explode("-", $error['date']);
    $dirName    = $months[(int)$fileNames[1]];
    $logPath    = logErrorPath("/{$dirName}");
    $fileName   = "{$logPath}/{$fileNames[2]}.log";
    
    if(!is_dir(logErrorPath())){
        if(!mkdir(logErrorPath(), 0755)){
            throw new Exception("Filepath " . logErrorPath() . " could not be created", 0xf17e);
        }
    }
    
    if(!is_dir($logPath)){
        if(!mkdir($logPath, 0755)){
            throw new Exception("Filepath {$logPath} could not be created", 0xf17e);
        }
    }
    $error  = json_encode($error);
    
    if(!file_exists($fileName)){
        if(!file_put_contents($fileName, "")){
            throw new Exception("File {$fileName} could not be created", 0xf17e);
        }
    }
    return file_put_contents($fileName, $error . PHP_EOL, FILE_APPEND | LOCK_EX) ?? false;
}

/**
 * Negates a PHP feature on the empty() command
 * whereby a string value of "0" will be empty;
 * note that numeric values of 0 or 0.0 will
 * return as empty so this tries to negate
 * this feature as well
 * 
 * @param   scalar | object
 * @author  sbebbington
 * @date    5 Sep 2017 - 12:51:55
 * @version 0.1.5-RC2
 * @return  bool
 */
function isEmpty($value){
    return (is_string($value) || is_numeric($value)) ? empty($value) && strlen("{$value}") : empty($value);
}
