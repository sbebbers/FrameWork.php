<?php 
namespace Application\Core\Framework;
require_once(serverPath("/library/Library.php"));

class HtmlBuilder
{
	public $lib;
	
	public function __construct(){
		$this->lib	= new \Application\Library\Library();
	}
	
	/**
	 * Tests the extension
	 * 
	 * @param	
	 * @author	sbebbington
	 * @date	16 Jan 2017 - 17:19:50
	 * @version	0.0.1
	 * @return	
	 * @todo
	 */
	public function test(){
		print("<p>HtmlBuilder test</p>");
		return $this;
	}
	
	/**
	 * Opens a paragraph tag
	 * 
	 * @param	na
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:15:10
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function p(){
		print("<p");
		return $this;
	}
	
	/**
	 * Adds an ID attribute to an HTML element
	 * 
	 * @param	string
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:15:29
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function id(string $id){
		print(" id=\"{$id}\"");
		return $this;
	}
	
	/**
	 * Adds a class attribute to an HTML element
	 * 
	 * @param	string | array
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:16:35
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function class($class){
		if(!is_string($class) && !is_array($class)){
			print(">" . PHP_EOL);
			$this->lib->debug("Please send your classes for your HTML element as a string or an array", true);
		}
		print(" class=\"");
		$_class='';
		if(is_array($class)){
			foreach($class as $classes){
				$_class .= "{$classes} ";
			}
			$_class = rtrim($_class, " ");
		}else{
			$_class .= "{$class}";
		}
		print("{$_class}\"");
		return $this;
	}
	
	/**
	 * Adds in data attribute, send the name of the attribute
	 * followed by the relevant attribute value
	 * 
	 * @param	string, string
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:26:47
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function dataAttr(string $attrName, string $data){
		print(" data-{$attrName}=\"{$data}\"");
		return $this;
	}
	
	/**
	 * Closes an element either with a > or a />
	 * with false or true respecitively
	 * 
	 * @param	boolean
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:30:19
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function closeElement(bool $selfClose = false){
		print($selfClose === false ? ">" : " />");
		return $this;
	}
	
	/**
	 * Writes text, intended to be used within an element
	 * If you have encoded your text with htmlspecialchars
	 * then send true as the second parameter to decode
	 * 
	 * @param	string, boolean
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:31:27
	 * @version	0.0.1
	 * @return	string
	 * @todo
	 */
	public function text(string $text, bool $decode = false){
		print($decode === false ? $text : htmlspecialchars_decode($text));
		return $this;
	}
	
	/**
	 * Opens a form
	 * 
	 * @param	na
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:47:46
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function form(){
		print("<form");
		return $this;
	}
	
	/**
	 * Sets form action (page to post etc...)
	 * 
	 * @param	string
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:47:59
	 * @version	0.0.1
	 * @return	$this
	 * @todo
	 */
	public function action(string $file){
		print(" action=\"{$file}\"");
		return $this;
	}
	
	/**
	 * Sets a post method
	 * 
	 * @param	string
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:48:30
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function method(string $method){
		print(" method=\"{$method}\"");
		return $this;
	}
	
	/**
	 * Opens an input element
	 * 
	 * @param	na
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:44:29
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function input(){
		print("<input");
		return $this;
	}
	
	/**
	 * Used for naming a form element
	 * 
	 * @param	string
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:49:19
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function name(string $name){
		print(" name=\"{$name}\"");
		return $this;
	}
	
	/**
	 * Used for input type (hidden, text etc...)
	 * 
	 * @param	string
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:50:04
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function type(string $type){
		print(" type=\"{$type}\"");
		return $this;
	}
	
	/**
	 * A value for your input type
	 * 
	 * @param	string
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:51:04
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function value(string $value){
		print(" value=\"{$value}\"");
		return $this;
	}
	
	/**
	 * Used if you want to disable an input or other
	 * form element
	 * 
	 * @param	boolean
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 09:52:23
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function disabled(bool $disabled = true){
		print($disabled === true ? " disabled=\"disabled\"" : "");
		return $this;
	}
	
	/**
	 * Intended for <select name="name"><option>...</option></select>
	 * 
	 * @param	string, string, array
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 10:27:56
	 * @version	0.0.1
	 * @return	Finish this
	 * @todo
	 */
	public function select(string $id = '', string $name = '', array $options){
		return $this;
	}
	
	/**
	 * Used for opening any element not covered here
	 * i.e. in your view:
	 * 		$this->open("div", "content", "col-xs-12");
	 * will generate the following HTML:
	 * 		<div id="content" class="col-xs-12">
	 * 
	 * @param	string, string, string | array, [boolean]
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 10:05:40
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function open(string $element, string $id = '', $class = null, bool $selfClose = false){
		print("<{$element}");
		if(strlen($id)>0){
			$this->id($id);
		}
		if(is_string($class) || is_array($class)){
			$this->class($class);
		}
		$this->closeElement($selfClose);
		return $this;
	}
	
	/**
	 * Used for closing an element such as a section
	 * or a div etc...
	 * i.e., in your view:
	 * 		$this->close("div");
	 * will generate the following HTML:
	 * 		</div>
	 * 
	 * @param	string
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 10:07:49
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function close(string $element){
		print("</{$element}>");
		return $this;
	}
	
	/**
	 * For HTML elements H1 to H6 inclusive
	 * 
	 * @param	int, string, string, string | array 
	 * @author	sbebbington
	 * @date	23 Jan 2017 - 10:22:45
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function h(int $size, string $text, string $id = '', $class = null){
		if($size < 1 || $size > 6){
			$this->lib->debug("Please set your header size between 1 and 6, value {$size} is not allowed", true);
		}
		print("<h{$size}");
		if(strlen($id)>0){
			$this->id($id);
		}
		if(is_string($class) || is_array($class)){
			$this->class($class);
		}
		$this->closeElement(false);
		print("{$text}");
		$this->close("h{$size}");
		return $this;
	}
	
	/**
	 * Opens a <script type="text/javascript" src="...">
	 * tag
	 *
	 * @param	string
	 * @author	sbebbington
	 * @date	15 Feb 2017 - 13:45:35
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function javaScript(string $src=''){
		print("<script type=\"text/javascript\"");
		print(strlen($src) ? " src=\"{$src}\">" : ">");
		return $this;
	}
}