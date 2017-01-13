<?php

namespace Application\Library;

class Library
{
	public $date;
	private $key;
	
	public function __construct($aAccess = false){
		$this->key			= 'Skelet0n';
	}
	
	/**
	 * For debugging objects, optional die and die message included
	 * Parameters now allow for a header for each object
	 *
	 * @param	object, boolean, string, string, string, string
	 * @author 	Shaun && Linden
	 * @date	11 Jan 2017 - 13:39:08
	 * @version 0.0.4
	 * @return 	null
	 * @todo
	 */
	public function debug($variable = null, $die = false, $message = '', $file = null, $line = null, $header = ''){
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
	 * @param	Library
	 * @author 	Shaun && Linden
	 * @date	9 Jan 2017 - 15:47:16
	 * @version 0.0.3
	 * @return	null
	 * @todo
	 */
	public function dump($variable, $die = false, $message = '', $file = null, $line = null){
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
	 * @param	na
	 * @author  Shaun B
	 * @date 	2016-02-19
	 * @return  string
	 * @todo	Remember to update this number when
	 * 			changes are made
	 */
	public function version(){
		return '0.0.8';
	}
	
	/**
	 * Is it Easter yet?
	 * Try <?php echo $this->controllerInstance->libraryInstance->easterEgg(); ?> in your view
	 * 
	 * @param	na
	 * @author	Shaun B
	 * @date	2016-02-19
	 * @return	Something good
	 * @todo	Nothing as this function is perfect
	 */
	public function easterEgg(){
		$easterEgg = chr(116) . chr(104) . chr(101) . chr(99) . chr(97) . chr(116) . chr(97) . chr(112) . chr(105);
		return "
			<div class=\"container\">
				<a href=\"http://{$easterEgg}.com\">
					<img src=\"http://{$easterEgg}.com/api/images/get?format=src&type=gif\" alt=\"Easter Egg\">
				</a>
			</div>
		";
	}
	
	/**
	 * Password encryption generator
	 *
	 * @param	string
	 * @author 	Shaun && Stack Overflow
	 * @date 	22 Mar 2016 16:25:47
	 * @version 0.0.2
	 * @return	String
	 * @todo
	 */	
	public function encryptIt($string, $_55Number = null){
		$md5a = ($_55Number == null) ? md5($this->key) : md5($_55Number);
		$md5b = ($_55Number == null) ? md5(md5($this->key)) : md5(md5($_55Number));
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $md5a, $string, MCRYPT_MODE_CBC, $md5b));
	}
	
	/**
	 * Password decryption generator
	 *
	 * @param	string
	 * @author 	Shaun && Stack Overflow
	 * @date 	22 Mar 2016 16:15:34
	 * @version 0.0.2
	 * @return	string
	 * @todo
	 */
	public function decryptIt($string, $_55Number = null){
		$md5a = ($_55Number == null) ? md5($this->key) : md5($_55Number);
		$md5b = ($_55Number == null) ? md5(md5($this->key)) : md5(md5($_55Number));
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $md5a, base64_decode($string), MCRYPT_MODE_CBC, $md5b), "\0");
	}
	
	/**
	 * Redirects using the PHP header command 
	 *
	 * @param	string, string
	 * @author 	Shaun || Steve
	 * @date 	29 Sep 2016 11:16:08
	 * @version 0.0.4a
	 * @return	na
	 * @todo
	 */
	public function redirect($destination, $host){
		$host	= preg_replace('/^https?\:\/\//','',$host);
		$http	= isHttps() ? "https://" : "http://";
		header("Location:{$http}{$host}/{$destination}");
		exit;
	}
    
    /**
     * Redirects by using HTML/JavaScript
     * 
     * @param	string, string
     * @author	Shaun
     * @date	10 Oct 2016 21:09:00
     * @version	0.0.1
     * @return	na
     * @todo
     */
    public function redirectExternal($destination, $website){
        die("
        	<!DOCTYPE html>
        	<html lang=\"en-gb\">
        		<head>
        			<meta charset=\"UTF-8\">
        			<title>Please wait, redirecting to {$website}</title>
        			<script type=\"text/javascript\">window.location.href=\"{$destination}\"</script>
        		</head>
        		<body></body>
        	</html>
        ");
    }
    
    /**
     * Unit testing for any method with up to 10 parameters
     * Send the parent object, internal method name, any
     * parameters as a flat array and the expected result
     * to use this
     *
     * @param	object, string, array | scalar, scalar
     * @author	sbebbington
     * @date	6 Jan 2017 - 15:31:16
     * @version	0.0.1
     * @return	boolean | string
     * @todo
     */
    public function testUnit($object, $method, $params = array(), $expectedResult){
    	if($object == null){
    		return print("<p>Pass an object to this method in order to test it</p>");
    	}
    	$passCol	= "color: green;";
    	$failCol	= "color: red;";
    	$tested		= false;
    
    	if(!is_array($params) && (is_string($params) || is_numeric($params))){
    		$pass	= $object->$method($params);
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
    	
    	if($tested){
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
     * @param	string
     * @author	sbebbington
     * @date	13 Jan 2017 - 10:35:31
     * @version	0.0.1
     * @return	string
     * @todo
     */
    public function convertSnakeCase($snake){
    	if(strpos('_', $snake) === true){
    		return $snake;
    	}
    	$_snake			= explode('_', $snake);
    	$camelBuilder	= '';
    	foreach($_snake as $key => $data){
    		$camelBuilder .= ($key === 0) ? $data : ucfirst($data);
    	}
    	return $camelBuilder;
    }
    
    /**
     * Cleanses and trims the data, used for posted data etc...
     * The recursion bit has been removed from the original
     * version as it seemed a little over-kill, although quite
     * clever; it will also handle HTML Special Chars if necessary
     *
     * @param	array, boolean, [array]
     * @author	sbebbington && Vietnam
     * @date	6 Jan 2017 - 15:36:29
     * @version	0.0.2
     * @return	array
     * @todo
     */
    public function cleanseInputs($data, $htmlSpecialChars = false, $cleanInput = array()){
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
     * @param	boolean
     * @author	sbebbington
     * @date	10 Jan 2017 - 09:25:07
     * @version	0.0.2
     * @return	string
     * @todo
     */
    public function domainType($subDomain = false){
    	$host	= explode(".", $this->host());
    	return ($subDomain === false) ? $host[count($host)-1] : $host[0];
    }
    
    /**
     * Turns a PHP scalar into JSON
     *
     * @param	scalar
     * @author	sbebbington
     * @date	10 Jan 2017 - 15:56:39
     * @version	0.0.1
     * @return	JSON
     * @todo
     */
    public function convertToJSON($data){
    	return json_encode($data);
    }
}