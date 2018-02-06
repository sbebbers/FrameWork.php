<?php
namespace Application\Library;

use Application\Core\FrameworkException\FrameworkException;
use DateTime;

class Library
{
    private $key,
            $encryption;
    
    public function __construct(){
        $this->key          = getConfig('key');
        $this->encryption   = getConfig('encryptionType');
    }
    
    /**
     * For debugging objects, optional die and die message included
     * Parameters now allow for a header for each object
     *
     * @param   object, boolean, string, string, string, string
     * @author  sbebbington && Linden
     * @date    26 Sep 2017 09:47:37
     * @version 0.1.5-RC2
     * @return   null
     */
    public function debug($variable = null, bool $die = false, string $message = '', string $file = '', string $line = '', string $header = ''){
        if(getConfig('mode') != 'test'){
            return null;
        }
        if(is_null($variable)){
            echo '<p>You need to send the scalar or resource that you are trying to debug as your first parameter, else this don\'t work</p>';
            exit;
        }
        echo (strlen($header) > 0) ? "<div><h1>{$header}</h1>" : "";
        echo '<pre>' . print_r($variable, 1) . '</pre>';
        echo $file != null ? '<pre>File: ' . print_r($file, 1) . '</pre>' : "";
        echo $line != null ? '<pre>Line: ' . print_r($line, 1) . '</pre>' : "";
        echo (strlen($header) > 0) ? "</div>" : "";
        if($die === true){
            die("{$message}");
        }
        return null;
    }
    
    /**
     * For seeing contents of variables or objects, optional die
     * and die message included
     *
     * @param   object, boolean, string, string, string
     * @author  sbebbington && Linden
     * @date    26 Sep 2017 09:47:15
     * @version 0.1.5-RC2
     * @return  null
     */
    public function dump($variable, bool $die = false, string $message = '', string $file = '', string $line = ''){
        if(getConfig('mode') != 'test'){
            return null;
        }
        echo var_dump($variable);
        echo $file != null ? '<pre>File: ' . print_r($file,1) . '</pre>' : "";
        echo $line != null ? '<pre>Line: ' . print_r($line,1) . '</pre>' : "";
        if($die === true){
            die($message);
        }
        return null;
    }
    
    /**
     * Returns current version of the framework
     *
     * @param   na
     * @author  sbebbington B
     * @date    26 Sep 2017 10:05:31
     * @return  string
     * @todo    Remember to update this number when
     *          enough changes constitute a new
     *          version
     */
    public function version(){
        return '0.1.5-RC2';
    }
    
    /**
     * Is it Easter yet?
     * Try <?phpecho $this->controllerInstance->libraryInstance->easterEgg(); ?> in your view
     * 
     * @param   na
     * @author  sbebbington B
     * @date    2016-02-19
     * @return  Something good
     * @todo    Nothing as this function is perfect
     */
    public function easterEgg(){
        $easterEgg = chr(116) . chr(104) . chr(101) . chr(99) . chr(97) . chr(116) . chr(97) . chr(112) . chr(105);
        return trim("
            <div class=\"container\">
                <a href=\"http://{$easterEgg}.com\">
                    <img src=\"http://{$easterEgg}.com/api/images/get?format=src&type=gif\" alt=\"Easter Egg\">
                </a>
            </div>
        ");
    }
    
    /**
     * Password encryption generator
     *
     * @param   string, sting, int, boolean
     * @author  sbebbington && Stack Overflow
     * @date    1 Mar 2017 08:54:14
     * @version 0.1.5-RC2
     * @return  string
     */
    public function encryptIt(string $string, string $secret = '', int $padding = 8, bool $urlEncode = false){
        $md5        = ($secret === '') ? md5(md5($this->key)) : md5(md5($secret));
        $encrypt    = $this->getEncryptionPadding($padding)
            . openssl_encrypt($string, $this->encryption, $md5)
            . $this->getEncryptionPadding($padding);
        return ($urlEncode === true) ? urlencode($encrypt) : "{$encrypt}";
    }
    
    /**
     * Password decryption generator
     *
     * @param   string, string, int, boolean
     * @author  sbebbington && Stack Overflow
     * @date    1 Mar 2017 08:57:23
     * @version 0.1.5-RC2
     * @return  string
     */
    public function decryptIt(string $string, string $secret = '', int $padding = 8, bool $urlDecode = false){
        $md5        = ($secret === '') ? md5(md5($this->key)) : md5(md5($secret));
        $decrypt    = openssl_decrypt(substr($string, $padding, -$padding), $this->encryption, $md5);
        return ($urlDecode === true) ? urldecode($decrypt) : "{$decrypt}";
    }
    
    /**
     * Redirects using the PHP header command 
     *
     * @param   string, string, [int]
     * @author  sbebbington || Steve
     * @date	17 Nov 2017 10:46:39
     * @version 0.1.5-RC2
     * @return  void
     */
    public function redirect(string $destination = '', string $host = '', int $serverResponseCode = 307){
        if($destination == '' || $host == ''){
            $this->debug("You need to set a destination and host parameters as a string to call the Library redirect() method", true);
        }
        $host    = preg_replace('/^https?\:\/\//','',$host);
        $http    = isHttps() ? "https://" : "http://";
        header("Location:{$http}{$host}/{$destination}", true, $serverResponseCode);
        exit;
    }
    
    /**
     * Will add a random and predictable padding
     * to the encrypted and decrypted string. Made
     * this method less like a ZX80 sub routine
     * (I had been experimenting with ZX80 BASIC
     * on that day so appologies)
     * 
     * @param   int
     * @author  sbebbington
     * @date    1 Mar 2017 09:01:05
     * @version 0.1.5-RC2
     * @return  string
     */
    public function getEncryptionPadding(int $numberToPad = 8){
        $shuffle    = "1q2w3e4r5t6y7u8i9o0p!AS£D\$%F^G!H*J(K)L-z=x[c]v{b}n;m:QW@E#R*T<Y>U,I.O/P?a|s%d1f2g3h4j5k6l7Z8X9C0VBNM";
        $shuffle    = str_shuffle("{$shuffle}");
        
        return substr($shuffle, 0, $numberToPad);        
    }
    
    /**
     * Redirects by using HTML/JavaScript
     * 
     * @param   string, string
     * @author  sbebbington
     * @date    2 Feb 2017 13:18:50
     * @version 0.1.5-RC2
     * @return  void
     */
    public function redirectExternal(string $destination = '', string $website= ''){
        if($destination == '' || $host == ''){
            $this->debug("You need to set a destination and host parameters as a string to call the Library redirectExternal() method", true);
        }
        die(trim("
            <!DOCTYPE html>
            <html lang=\"en-gb\">
                <head>
                    <meta charset=\"UTF-8\">
                    <title>Please wait, redirecting to {$website}</title>
                    <script type=\"text/javascript\">window.location.href=\"{$destination}\"</script>
                </head>
                <body></body>
            </html>
        "));
    }
    
    /**
     * Unit testing for any method with up to 10 parameters
     * Send the parent object, internal method name, any
     * parameters as a flat array and the expected result
     * to use this
     *
     * @param   object, string, array, any, [boolean]
     * @author  sbebbington
     * @date    26 Sep 2017 09:46:32
     * @version 0.1.5-RC2
     * @return  boolean | string
     */
    public function testUnit($object = null, string $method = '', $params = array(), $expectedResult = null, bool $tested = false){
        if(getConfig('mode') != 'test'){
            return null;
        }
        if($object == null){
            return print("<p>Pass an object to this method in order to test it</p>");
        }
        if($method == ''){
            return print("<p>You need to specify the name of the method that you want to test</p>");
        }
        if($expectedResult == null){
            return print("<p>You need to specify your expected return value from the method that you are testing</p>");
        }
        $passCol    = "color: green;";
        $failCol    = "color: red;";
    
        if(!is_array($params) && (is_string($params) || is_numeric($params))){
            $pass    = $object->$method($params);
            $tested = true;
        }else if(is_array($params)){
            $pass = $object->$method(
                $params[0],
                isset($params[1]) ? $params[1] : null,
                isset($params[2]) ? $params[2] : null,
                isset($params[3]) ? $params[3] : null,
                isset($params[4]) ? $params[4] : null,
                isset($params[5]) ? $params[5] : null,
                isset($params[6]) ? $params[6] : null,
                isset($params[7]) ? $params[7] : null,
                isset($params[8]) ? $params[8] : null,
                isset($params[9]) ? $params[9] : null
            );
                
            $tested = true;
        }
        
        if($tested === true){
            echo "<p style=\"";
            echo ($pass == $expectedResult) ? "{$passCol}\">Test matched expected result" : "{$failCol}\">Test failed";
            echo "</p>" . PHP_EOL;
            echo "Expected: {$expectedResult}" . PHP_EOL;
            echo "Actual: {$pass}" . PHP_EOL;
            return ($pass == $expectedResult);
        }
        return print("<p>Please send the parameters as an array, a string or a numeric value</p>");
    }
    
    /**
     * This will convert the snake_case stuff typically used in databases
     * to normal camelCase typically used in PHP
     * 
     * @param   string
     * @author  sbebbington
     * @date    6 Jul 2017 12:14:42
     * @version 0.1.5-RC2
     * @return  string
     */
    public function convertSnakeCase(string $snake = '', string $delimiter = '_'){
        if($snake != ''){
            $_snake             = explode($delimiter, $snake);
            if(count($_snake) == 1){
                return $snake;
            }
            $camelBuilder       = '';
            foreach($_snake as $key => $data){
                $camelBuilder   .= ($key === 0) ? $data : ucfirst($data);
            }
            return $camelBuilder;
        }
        return "{$snake}";
    }
    
    /**
     * Converts to camelCase where one or more dashes
     * appear in the string
     * 
     * @param   string
     * @author  sbebbington
     * @date    6 Jul 2017 12:17:34
     * @version 0.1.5-RC2
     * @return  string
     */
    public function camelCaseFromDashes($string){
        return $this->convertSnakeCase($string, '-');
    }
    
    /**
     * This will convert a camelCase string to
     * snake_case as database enthusiasts like
     * snake_case. A lot
     *
     * @param   string, int
     * @author  sbebbington
     * @date    3 Feb 2017 13:46:37
     * @version 0.1.5-RC2
     * @return  string
     */
    public function convertToSnakeCase(string $unSnaked = '', int $offset = 0){
        if($unSnaked === ''){
            return '';
        }
        $index          = $charBuffer = 0;
        $stringBuffer   = '';
        while($index < strlen($unSnaked)){
            $charBuffer = ord($unSnaked[$index]);
            if($index > $offset){
                if($charBuffer < 91 && $charBuffer > 64){
                    $charBuffer     += 32;
                    $stringBuffer   .= '_';
                }
            }
            $stringBuffer .= chr($charBuffer);
            $index++;
        }
        return "{$stringBuffer}";
    }
    
    /**
     * Cleanses and trims the data, used for posted data etc...
     * The recursion bit has been removed from the original
     * version as it seemed a little over-kill, although quite
     * clever; it will also handle HTML Special Chars if necessary
     *
     * @param   array, boolean, [array]
     * @author  sbebbington && Vietnam
     * @date    6 Jan 2017 15:36:29
     * @version 0.1.5-RC2
     * @return  array
     */
    public function cleanseInputs($data, bool $htmlSpecialChars = false, $cleanInput = array()){
        foreach($data as $key => $value){
            $cleanInput[$key] = ($htmlSpecialChars === false) ? trim(strip_tags($value)) : htmlspecialchars(trim($value));
        }
        return $cleanInput;
    }
    
    /**
     * Used for identifying .local and .localhost sites to set
     * PHP error reporting - send true to the method to return
     * the subdomain, i.e., staging or test, or whatever the
     * Reapit is for staging domains
     *
     * @param   boolean
     * @author  sbebbington
     * @date    10 Jan 2017 09:25:07
     * @version 0.1.5-RC2
     * @return  string
     */
    public function domainType(bool $subDomain = false){
        $host   = explode(".", $this->host());
        return ($subDomain === false) ? $host[count($host)-1] : $host[0];
    }
    
    /**
     * Turns a PHP array into JSON; use
     * true for the second value to trim
     * the data in array to convert to
     * JSON
     *
     * @param   array|resource, [bool]
     * @author  sbebbington
     * @date    10 Jan 2017 15:56:39
     * @version 0.1.5-RC2
     * @return  \JsonSerializable
     */
    public function convertToJSON(array $data = array(), bool $trimData = false){
        if(!empty($data) && $trimData === true){
            foreach($data as $k => $d){
                $data[$k] = (is_string($k)) ? trim($k) : $k;
            }
        }
        return json_encode($data);
    }
    
    /**
     * Converts a JSON object to a
     * PHP resource
     * 
     * @param   JSON
     * @author  sbebbington
     * @date    3 Feb 2017 14:47:48
     * @version 0.1.5-RC2
     * @return  resource
     */
    public function convertFromJSON($data){
        return json_decode($data);
    }
    
    /**
     * This will perform a soft minimisation of
     * your javascript [jQuery] files; As it is
     * a dumb minimisation there are some a few
     * limitations, for instance:
     *     [1] All comments must be /* style
     *     [2] Variable names are not obfustated
     *     [3] Not all white spaces are removed
     *     [4] It will not remove the final ;
     *         where it is not necessary
     *
     * @param   string, [boolean], [boolean]
     * @author  sbebbington
     * @date    21 Feb 2017 15:20:10
     * @version 0.1.5-RC2
     * @return  string
     */
    public function softMinimiseJS(string $filePathName = '', bool $doubleSpaces = true, bool $spacedTab = true){
        if(empty($filePathName)){
            return '';
        }
        $remove = array(
            'remove' => array(
                "\t",
                "\r\n",
            ),
            'replace' => array(
                '+'     => " + ",
                '=='    => " == ",
                '!='    => " != ",
                'if('   => "if (",
                '++'    => "++ ",
                '==='   => " === ",
                '!=='   => " !== ",
                '{'     => " { ",
                'space' => "  ",
                'tab'   => "    ",
            ),
        );
        $file   = file_get_contents($filePathName);
        foreach($remove['remove'] as $data){
            $file   = str_replace($data, '', $file);
        }
        foreach($remove['replace'] as $key => $data){
            if($key =='space' || $key == 'tab'){
                if($key == 'space' && $doubleSpaces === false){
                    continue;
                }
                if($key == 'tab' && $spacedTab === false){
                    continue;
                }
                $key = '';
            }
            $file = str_replace($data, $key, $file);
        }
        return $file;
    }
    
    /**
     * This will sanitize date strings in
     * yyyy/mm/dd and dd/mm/yyyy format
     * 
     * @param   string
     * @author  sbebbington
     * @date    30 Oct 2017 11:19:41
     * @version 0.1.4
     * @return  DateTime
     */
    public function sanitizeDateString($date = null){
        $error = false;
        $timeZoneType = "+";
        
        if(is_null($date)){
            throw new FrameworkException('Date string was sent as null');
        }

        $day    = $month = $year = 0;
        $time   = "00:00:00";
        $hours  = "0000";
        
        if(strpos($date, "T") == 10 && strlen($date) > 18){
            $_time  = explode("T", $date);
            $time   = $_time[1];
            $date   = $_time[0];
            $timeZone = false;
            
            if(substr($d, -5, -4) == "-"){
                $_time          = explode("-", $time);
                $timeZone       = true;
                $timeZoneType   = "-";
            }else if(substr($d, -5, -4) == "+"){
                $_time          = explode("+", $time);
                $timeZone       = true;
            }
            
            if($timeZone === true){
                $hours  = $_time[1];
                $time   = $_time[0];
            }
        }
        
        $time = 'T' . $this->sanitizeTimeString("{$time}{$timeZoneType}{$hours}");
        
        $date = trim(str_replace("/", "-", str_replace(".", "-", $date)));
        $date = explode("-", $date);
        
        if(isset($date[0]) && strlen($date[0]) == 4 && intval($date[0]) >= 1970){
            $year   = "{$date[0]}";
            $month  = isset($date[1]) ? "{$date[1]}" : '';
            $day    = isset($date[2]) ? "{$date[2]}" : '';
        }else if(isset($date[2]) && strlen($date[2]) == 4 && intval($date[2]) >= 1970){
            $day    = isset($date[0]) ? "{$date[0]}" : '';
            $month  = isset($date[1]) ? "{$date[1]}" : '';
            $year   = "{$date[2]}";
        }else{
            $error = true;
        }
        
        if(checkdate($month, $day, $year) == false || $error == true){
            throw new FrameworkException("Date is invalid; please use dd-mm-yyyy or yyyy-mm-dd with optional time (Thrs:min:sec+0000, i.e., T09:00:00+0100). Value sent (yyyy-mm-ddThrs:min:sec+0000) was {$year}-{$month}-{$day}{$time}");
            return;
        }
        
        $valid = "{$year}-{$month}-{$day}{$time}";
        
        return new DateTime($valid);
    }
    
    /**
     * Checks the time string to see if it
     * is in the accepted parameters, uses
     * 24hr clock format
     * (00:00:00 - 23:59:59)
     * 
     * @param   string
     * @author  sbebbington
     * @date    30 Oct 2017 11:37:27
     * @version 0.1.4
     * @return  string
     */
    protected function sanitizeTimeString($time = ''){
        $timeZoneType = "+";
        if(strpos($time, "-")){
            $_time = explode("-", $time);
            $timeZoneType = "-";
        }else{
            $_time = explode("+", $time);
        }
        
        $time       = $_time[0];
        $plusHrs    = substr($_time[1], 0, 2);
        $plusMin    = substr($_time[1], 2);
        
        if(strlen($time) != 8 || intval($plusHrs) < 0 || intval($plusHrs) > 23 || (intval($plusMin) < 0 || intval($plusMin > 59))){
            throw new FrameworkException("Time is invalid, expected THH:MM:SS+HHMM, recieved T{$time}+{$plusHrs}{$plusMin}");
        }
        
        if(strpos($time, ".")){
            $time = str_replace(".", ":", $time);
        }
        
        $time = explode(":", $time);
        
        if(intval($time[0]) < 0 || intval($time[0]) > 23 || intval($time[1]) < 0 || intval($time[1]) > 59 || intval($time[2]) < 0 || intval($time[2]) > 59){
            throw new FrameworkException("Time is invalid, expected HH:MM:SS, recieved {$time[0]}:{$time[1]}:{$time[2]}");
        }
        
        return "{$time[0]}:{$time[1]}:{$time[2]}{$timeZoneType}{$plusHrs}{$plusMin}";
    }
    
    /**
     * Handles the posting of data
     *
     * @param   string, object, string, string, string
     * @author  sbebbington && Stack Overflow
     * @date    3 Mar 2017 09:51:09
     * @version 0.1.5-RC2
     * @return  object
     */
    function filePostContents(string $url, $data, string $applicationType = 'x-www-form-urlencoded', string $username = '', string $password = '', string $characterEncoding = 'utf-8'){
        if($applicationType === 'x-www-form-urlencoded'){
            if(!is_object($data) || !is_array($data)){
                $data    = [$data];
            }
            $data   = http_build_query($data);
        }
        $options    = array();
    
        $options['http'] = array(
            'method'  => 'POST',
            'content' => $data
        );
        $header    = array(
            'header'  =>    "Content-type: application/{$applicationType};charset={$characterEncoding}",
        );
        if(is_string($data)){
            $header['header']   .= PHP_EOL . 'Content-Length: ' . strlen($data) . PHP_EOL;
        }
        if(!empty($username) && !empty($password)){
            $header['header']   .= PHP_EOL . "Authorization: Basic " . base64_encode("{$username}:{$password}");
        }
        $options['http']    = array_merge($options['http'], $header);
         
        $context    = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }
}
