<?php
namespace Application\Library;

use stdClass;
use Application\Core\FrameworkException\FrameworkException;

if(!defined('FRAMEWORKPHP') || FRAMEWORKPHP != 65535){
    require_once("../view/403.phtml");
}

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
     * @version 0.1.5-RC3
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
        if(isTrue($die)){
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
     * @version 0.1.5-RC3
     * @return  null
     */
    public function dump($variable, bool $die = false, string $message = '', string $file = '', string $line = ''){
        if(getConfig('mode') != 'test'){
            return null;
        }
        var_dump($variable);
        echo $file != null ? '<pre>File: ' . print_r($file,1) . '</pre>' : "";
        echo $line != null ? '<pre>Line: ' . print_r($line,1) . '</pre>' : "";
        if(isTrue($die)){
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
        return '0.1.5-RC3';
    }
    
    /**
     * Is it Easter yet?
     * Try <?phpecho $this->controllerInstance->libraryInstance->easterEgg(); ?> in your view
     * 
     * @param   na
     * @author  sbebbington B
     * @date    2016-02-19
     * @return  "Something good"
     * @todo    Nothing as this function is perfect
     */
    public function easterEgg(string $id = "something-good"){
        $easterEgg = chr(116) . chr(104) . chr(101) . chr(99) . chr(97) . chr(116) . chr(97) . chr(112) . chr(105);
        return trim("
            <div id=\"{$id}\" class=\"container\">
                <a href=\"http://{$easterEgg}.com\">
                    <img src=\"http://{$easterEgg}.com/api/images/get?format=src&type=gif\" alt=\"Easter Egg\" />
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
     * @version 0.1.5-RC3
     * @return  string
     */
    public function encryptIt(string $string, string $secret = '', int $padding = 8, bool $urlEncode = false){
        $md5        = ($secret === '') ? md5(md5($this->key)) : md5(md5($secret));
        $encrypt    = $this->getEncryptionPadding($padding)
            . openssl_encrypt($string, $this->encryption, $md5)
            . $this->getEncryptionPadding($padding);
        return (isTrue($urlEncode)) ? urlencode($encrypt) : "{$encrypt}";
    }
    
    /**
     * Password decryption generator
     *
     * @param   string, string, int, boolean
     * @author  sbebbington && Stack Overflow
     * @date    1 Mar 2017 08:57:23
     * @version 0.1.5-RC3
     * @return  string
     */
    public function decryptIt(string $string, string $secret = '', int $padding = 8, bool $urlDecode = false){
        $md5        = ($secret === '') ? md5(md5($this->key)) : md5(md5($secret));
        $decrypt    = openssl_decrypt(substr($string, $padding, -$padding), $this->encryption, $md5);
        return (isTrue($urlDecode)) ? urldecode($decrypt) : "{$decrypt}";
    }
    
    /**
     * Redirects using the PHP header command 
     *
     * @param   string, string, [int]
     * @author  sbebbington || Steve
     * @date	17 Nov 2017 10:46:39
     * @version 0.1.5-RC3
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
     * @version 0.1.5-RC3
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
     * @version 0.1.5-RC3
     * @return  void
     * @throws  FrameworkException
     */
    public function redirectExternal(string $destination = '', string $website= null){
        if($destination == '' || $website == ''){
            throw new FrameworkException("You need to set a destination and host parameters as a string to call the Library redirectExternal() method");
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
     * @date	21 Feb 2018 09:53:55
     * @version 0.1.5-RC3
     * @return  boolean | string
     */
    public function testUnit($object = null, string $method = null, $params = array(), $expectedResult = null, bool $tested = false){
        if($this->checkUnitTestParameters($object, $method, $expectedResult)){
            return false;
        }
        $passCol    = "color: green;";
        $failCol    = "color: red;";
    
        if(!is_array($params) && (is_string($params) || is_numeric($params))){
            $pass    = $object->{$method}($params);
            $tested = true;
        }else if(is_array($params)){
            $pass = $object->{$method}(
                $params[0],
                $params[1] ?? null,
                $params[2] ?? null,
                $params[3] ?? null,
                $params[4] ?? null,
                $params[5] ?? null,
                $params[6] ?? null,
                $params[7] ?? null,
                $params[8] ?? null,
                $params[9] ?? null
            );
                
            $tested = true;
        }
        
        return ($tested === true) ? $this->outputUnitTestResult($passCol, $failCol, $pass) : print("<p>Please send the parameters as an array, a string or a numeric value</p>");
    }
    
    /**
     * Does a check for the main parameters sent to
     * $this->testUnit()
     *
     * @param   mixed $object
     * @param   string $method
     * @param   mixed $expectedResult
     * @author  Shaun B
     * @date	6 Aug 2018 13:21:07
     * @throws  FrameworkException
     */
    public function checkUnitTestParameters($object, $method, $expectedResult) : bool
    {
        if(getConfig('mode') != 'test'){
            return false;
        }
        if($object === null || !is_object($object)){
            throw new FrameworkException("Pass an object to this method in order to test it");
        }
        if($method === null){
            throw new FrameworkException("You need to specify the name of the method that you want to test");
        }
        if($expectedResult == null){
            throw new FrameworkException("You need to specify your expected return value from the method that you are testing");
        }
        return true;
    }
    
    /**
     * Shows the test results in <P> tags
     *
     * @param   string $passCol
     * @param   string $failCol
     * @author  Shaun B
     * @date	6 Aug 2018 13:25:49
     */
    public function outputUnitTestResult(string $passCol, string $failCol, $pass = null) : bool
    {
        if(isTrue($tested)){
            echo "<p style=\"";
            echo ($pass == $expectedResult) ? "{$passCol}\">Test matched expected result" : "{$failCol}\">Test failed";
            echo "</p>" . PHP_EOL;
            echo "Expected: {$expectedResult}" . PHP_EOL;
            echo "Actual: {$pass}" . PHP_EOL;
            return ($pass == $expectedResult);
        }
        return false;
    }
    
    /**
     * This will convert the snake_case stuff typically used in databases
     * to normal camelCase typically used in PHP
     * 
     * @param   string
     * @author  sbebbington
     * @date    6 Jul 2017 12:14:42
     * @version 0.1.5-RC3
     * @return  string
     */
    public function convertSnakeCase(string $snake = null, string $delimiter = '_'){
        if($snake !== null){
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
     * @version 0.1.5-RC3
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
     * @version 0.1.5-RC3
     * @return  string
     */
    public function convertToSnakeCase(string $unSnaked = null, int $offset = 0){
        if($unSnaked === null || $unSnaked === ''){
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
     * @version 0.1.5-RC3
     * @return  array
     */
    public function cleanseInputs($data, bool $htmlSpecialChars = false, $cleanInput = array()){
        foreach($data as $key => $value){
            $cleanInput[$key] = (isFalse($htmlSpecialChars)) ? trim(strip_tags($value)) : htmlspecialchars(trim($value));
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
     * @version 0.1.5-RC3
     * @return  string
     */
    public function domainType(bool $subDomain = false){
        $host   = explode(".", $this->host());
        return (isFalse($subDomain)) ? $host[count($host)-1] : $host[0];
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
     * @version 0.1.5-RC3
     * @return  string
     */
    public function softMinimiseJS(string $filePathName = null, bool $doubleSpaces = true, bool $spacedTab = true){
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
        foreach($remove['replace'] as $_key => $data){
            if($_key === 'space' || $_key === 'tab'){
                if($_key == 'space' && isFalse($doubleSpaces)){
                    continue;
                }
                if($_key === 'tab' && isFalse($spacedTab)){
                    continue;
                }
                $_key = '';
            }
            $file = str_replace($data, $_key, $file);
        }
        return $file;
    }
    
    /**
     * Handles the posting of data
     *
     * @param   string, object, string, string, string
     * @author  sbebbington && Stack Overflow
     * @date    3 Mar 2017 09:51:09
     * @version 0.1.5-RC3
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
    
    /**
     * Checks if count() can be used on an object or scalar
     * 
     * @param   mixed $object
     * @author	sbebbeington
     * @date	13 Feb 2018 13:26:37
     * @return	boolean
     */
    public function isCountable($object = null){
        return ((is_array($object) || $object instanceof stdClass) && !empty($object));
    }
}
