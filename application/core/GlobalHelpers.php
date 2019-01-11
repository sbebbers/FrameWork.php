<?php
if (! defined('FRAMEWORKPHP') || FRAMEWORKPHP != 65535) {
    require_once ("../view/403.phtml");
}

use Application\Core\FrameworkException\FrameworkException;

/**
 * Will return the specific site parameter from
 * the site.json config - will default to the
 * baseURL setting if an empty string
 * is sent, or cookieDomain if no parameter
 * is sent
 *
 * @param string $parameter
 * @author sbebbington
 * @date 30 Aug 2018 16:22:28
 * @version 1.0.0-RC1
 * @return string
 * @throws FrameworkException
 */
function getConfig(string $parameter = 'baseURL'): string
{
    if (! file_exists(serverPath("/config/site.json"))) {
        throw new FrameworkException("A site.json file is required in the configuration at the application level for the framework to run", 0x02);
    }
    return json_decode(file_get_contents(serverPath("/config/site.json")), true)[$parameter] ?? '';
}

/**
 * This will check to see if the server is running
 * http or https.
 * This parameter is now set in the
 * site.json file in the application config
 *
 * @author sbebbington
 * @date 30 Aug 2018 16:21:55
 * @version 1.0.0-RC1
 * @return boolean
 */
function isHttps(): bool
{
    return getConfig('protocol') === 'https';
}

/**
 * Returns the name of the current host file from
 * the PHP $_SERVER global thing #n00b
 *
 * @author sbebbington
 * @date 5 Oct 2016 10:55:01
 * @version 1.0.0-RC1
 * @return string
 */
function host(): string
{
    return "{$_SERVER['HTTP_HOST']}";
}

/**
 * Sets time zone, for a full list, see
 * http://php.net/manual/en/timezones.php
 *
 * @param string $timeZone
 * @author sbebbington
 * @date 24 Jan 2017 09:48:21
 * @version 1.0.0-RC1
 * @return void
 */
function setTimeZone(string $timeZone): void
{
    date_default_timezone_set($timeZone);
}

/**
 * Gets user IP address
 *
 * @author sbebbington
 * @date 2 Feb 2017 - 09:58:21
 * @version 1.0.0-RC1
 * @return string
 */
function getUserIPAddress(): string
{
    $client = $_SERVER['HTTP_CLIENT_IP'] ?? '';
    $forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } else if (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    }
    return $ip;
}

/**
 * Returns the IP address that the framework
 * is running on
 *
 * @author sbebbington
 * @date 2 Jan 2018 10:48:58
 * @version 1.0.0-RC1
 * @return string
 */
function getServerIPAddress(): string
{
    return $_SERVER['SERVER_ADDR'];
}

/**
 * Returns the current or default URI segment
 *
 * @author sbebbington
 * @date 6 Feb 2017 - 11:40:40
 * @version 1.0.0-RC1
 * @return string
 */
function getSegment(): string
{
    $page = array_filter(explode('/', $_SERVER['REQUEST_URI']), 'strlen');
    return ! empty($page) ? strtolower($page[count($page)]) : 'home';
}

/**
 * Returns PHP_SELF
 *
 * @author sbebbington
 * @date 2 Jan 2018 10:49:31
 * @version 1.0.0-RC1
 * @return string
 */
function getSelf(): string
{
    return $_SERVER['PHP_SELF'];
}

/**
 * Returns the server query string
 *
 * @author sbebbington
 * @date 2 Mar 2017 - 13:23:08
 * @version 1.0.0-RC1
 * @return string
 */
function getQueryString(): string
{
    return str_replace("/", '', $_SERVER['QUERY_STRING']);
}

/**
 * Returns the version number of the application
 * as set in the site.json config
 *
 * @author sbebbington
 * @date 27 Jul 2017 - 16:02:26
 * @version 1.0.0-RC1
 * @return string
 */
function getSiteVersion(): string
{
    return getConfig('version');
}

/**
 * If rc is set as true in the site.json config
 * then this is a release candidate, else it isn't
 *
 * @author sbebbington
 * @date 27 Jul 2017 - 16:03:33
 * @version 1.0.0-RC1
 * @return bool
 */
function isReleaseCandidate(): string
{
    return getConfig('rc');
}

/**
 * States whether or not is a development
 * version set in the site.json config
 *
 * @author sbebbington
 * @date 27 Jul 2017 - 16:05:23
 * @version 1.0.0-RC1
 * @return bool
 */
function isDevelopmentVersion(): string
{
    return getConfig('dev');
}

/**
 * Gets the configuration path
 *
 * @param string $routeTo
 * @author Rob Gill && sbebbington
 * @date 16 Aug 2017 - 17:09:28
 * @version 1.0.0-RC1
 * @return string
 */
function logErrorPath(string $routeTo = ''): string
{
    $baseDir = dirname(__DIR__) . "/logs";
    return str_replace("\\", "/", "{$baseDir}{$routeTo}");
}

/**
 * Returns the months according to
 * the Gregorian calendar
 *
 * @author ShaunB
 * @date 11 Jan 2019 12:39:03
 * @version 1.0.0-RC1
 * @return array
 */
function getMonths(): array
{
    return [
        1 => 'jan',
        'feb',
        'mar',
        'apr',
        'may',
        'jun',
        'jul',
        'aug',
        'sep',
        'oct',
        'nov',
        'dec'
    ];
}

/**
 * Writes a log file based on date
 * and day; if log file already exists
 * then it will append the data to the
 * file;
 *
 * @param mixed $error
 * @param mixed $jsonConstant
 * @param bool $generateFileName
 * @author sbebbington
 * @date 19 Oct 2018 13:38:49
 * @version 1.0.0-RC1
 * @return bool
 * @throws Exception
 */
function writeToLogFile($error = [], $jsonConstant = null, bool $generateFileName = false): bool
{
    if (empty($error)) {
        return false;
    }
    $error = is_array($error) ? $error : [
        $error
    ];

    $months = getMonths();

    if (empty($error['ip_address'])) {
        $error['ip_address'] = getUserIPAddress();
    }

    if (empty($error['date'])) {
        $error['date'] = date("Y-m-d");
        $error['time'] = date("H:i:s");
    }
    $fileNames = explode("-", $error['date']);
    $dirName = $months[(int) $fileNames[1]];
    $logPath = str_replace('//', '/', logErrorPath("/{$dirName}/"));
    $fileName = str_replace('//', '/', "{$logPath}/{$fileNames[2]}");
    $fileName .= ($generateFileName === true) ? date("His") : '';
    $fileName .= '.log';
    $file = null;

    if (! is_dir($logPath)) {
        while (isFalse(mkdir($logPath, 0775))) {}
    }
    $error = json_encode($error, $jsonConstant);

    while (isFalse(file_exists($fileName))) {
        file_put_contents($fileName, "");
    }

    $file = file_put_contents($fileName, "{$error}" . PHP_EOL, FILE_APPEND | LOCK_EX);
    return $file;
}

/**
 * Negates a PHP feature on the empty() command
 * whereby a string value of "0" will be empty;
 * note that numeric values of 0 or 0.0 will
 * return as empty so this tries to negate
 * this feature as well
 *
 * @param mixed $value
 * @author sbebbington
 * @date 5 Sep 2017 - 12:51:55
 * @version 1.0.0-RC1
 * @return bool
 */
function isEmpty($value): bool
{
    return (is_string($value) || is_numeric($value)) ? empty($value) && strlen("{$value}") : empty($value);
}

/**
 * Checks if count() can be used on an object or scalar
 *
 * @param mixed $object
 * @author sbebbeington
 * @date 13 Feb 2018 13:26:37
 * @return boolean
 */
function isCountable($object = null): bool
{
    return ((is_array($object) || $object instanceof stdClass) && ! empty($object));
}
