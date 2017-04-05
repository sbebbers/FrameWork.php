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
	 * @param	na
	 * @author	sbebbington
	 * @date	16 Jan 2017 - 17:19:50
	 * @version	0.0.1
	 * @return	this
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
	 * Opens an a tag
	 *
	 * @param	string, string, string, string, string, string | array, [boolean]
	 * @author	sbebbington
	 * @date	30 Mar 2017 - 11:30:31
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function a(string $id = '', string $href = '', string $target = '', string $onClick = '', string $class = '', $style = null, bool $close = true){
		print("<a");
		
		if(strlen($id)){
			print(" id=\"{$id}\"");
		}
		if(strlen($href)){
			print(" href=\"{$href}\"");
		}
		if(strlen($target)){
			print(" target=\"{$target}\"");
		}
		if(strlen($onClick)){
			print(" onclick=\"{$onClick}\"");
		}
		if(strlen($class)){
			print(" class=\"{$class}\"");
		}
		if(!empty($style) && (is_string($style) || is_array($file))){
			$this->style($style);
		}
		if($close === true){
			print(">");
		}
		
		return $this;
	}
	
	/**
	 * Makes a HR element
	 * 
	 * @param	string, string
	 * @author	sbebbington
	 * @date	5 Apr 2017 - 16:14:08
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function hr(string $id = '', string $class=''){
		print("<hr");
		if(strlen($id)){
			print(" id=\"{$id}\"");
		}
		if(strlen($class)){
			print(" class=\"{$class}\"");
		}
		print(" />");
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
	 * Generates the HTML image tag
	 * 
	 * @param	string, string, int, int, string, string
	 * @author	sbebbington
	 * @date	29 Mar 2017 - 11:37:44
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function img(string $id = '', string $path, int $width = 0, int $height = 0, string $alt = '', string $class = ''){
		print("<img");
		if(!empty($id)){
			print(" id=\"{$id}\"");
		}
		print(" src=\"{$path}\"");
		if($width > 0){
			print(" width=\"{$width}\"");
		}
		if($height > 0){
			print(" height=\"{$height}\"");
		}
		if(!empty($alt)){
			print(" alt=\"{$alt}\"");
		}
		if(!empty($class)){
			print(" class=\"{$class}\"");
		}
		print(" />");
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
	 * @return	this
	 * @todo
	 */
	public function text(string $text, bool $decode = false){
		print($decode === false ? $text : htmlspecialchars_decode($text));
		return $this;
	}
	
	/**
	 * Generates a <textarea></textarea>
	 * for your view
	 * 
	 * @param	string, string, int, int, string, string, boolean
	 * @author	sbebbington
	 * @date	30 Mar 2017 - 14:25:06
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function textArea(string $id = '', string $name = '', int $rows = 0, int $cols = 0, string $placeHolder = '', string  $class = '', bool $required = false){
		print("<textarea");
		
		if(!empty($id)){
			print(" id=\"{$id}\"");
		}
		if(!empty($name)){
			print(" name=\"{$name}\"");
		}
		if($rows > 0){
			print(" rows=\"{$rows}\"");
		}
		if($cols> 0){
			print(" cols=\"{$cols}\"");
		}
		if(!empty($placeHolder)){
			print(" placeholder=\"{$placeholder}\"");			
		}
		if(!empty($class)){
			print(" class=\"{$class}\"");
		}
		if($required === true){
			print(" required=\"required\"");
		}
		print("><textarea>");
		
		return $this;
	}
	
	/**
	 * Opens a form tag
	 * 
	 * @param	string, string, string, string, string | array
	 * @author	sbebbington
	 * @date	30 Mar 2017 - 11:27:48
	 * @version	0.0.3
	 * @return	this
	 * @todo
	 */
	public function form(string $id = '', string $action = '', string $method = 'post', string $class = '', $style = null){
		print("<form");
		
		if(!empty($id)){
			print(" id=\"{$id}\"");
		}
		if(!empty($action)){
			print(" action=\"{$action}\"");
		}
		if(!empty($method)){
			print(" method=\"{$method}\"");
		}
		if(!empty($class)){
			print(" class=\"{$class}\"");
		}
		if(!empty($style) && (is_string($style) || is_array($file))){
			$this->style($style);
		}
		$this->closeElement(false);
		
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
	 * Label generator
	 * 
	 * @param	string, string, string, string, string | array
	 * @author	sbebbington
	 * @date	30 Mar 2017 - 11:26:28
	 * @version	0.0.2
	 * @return	this
	 * @todo
	 */
	public function label(string $text, string $id = '', string $for = '', string $class = '', $style = null){
		print("<label");
		
		if(!empty($id)){
			print(" id=\"{$id}\"");
		}
		if(!empty($for)){
			print(" for=\"{$for}\"");
		}
		if(!empty($class)){
			print(" class=\"{$class}\"");
		}
		if(!empty($style) && (is_string($style) || is_array($file))){
			$this->style($style);
		}
		if(!empty($text)){
			print(">{$text}</label>");
		}
		
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
	 * For <select id="id" name="name" class="class"><option></option></select>
	 * Option values will be the $key and the display will be $data in the
	 * array; if you want a selected item from the drop down, send the key
	 * name of the item to be marked as selected after the options array
	 * 
	 * @param	string, string, string, array, string, boolean
	 * @author	sbebbington
	 * @date	27 Feb 2017 - 10:42:12
	 * @version	0.0.2
	 * @return	this
	 * @todo
	 */
	public function select(string $id = '', string $name = '', string $class = '', array $options, string $selected = '', bool $close = true){
		print("<select");
		if(!empty($id)){
			print(" id=\"{$id}\"");
		}
		if(!empty($name)){
			print(" name=\"{$name}\"");
		}
		if(!empty($class)){
			print(" class=\"{$class}\"");
		}
		print(">");
		if(!empty($options)){
			$this->option($options, $selected);
		}
		print($close === true ? "</select>" : "");
		return $this;
	}
	
	/**
	 * Builds the options for your select
	 * 
	 * @param	array, string
	 * @author	sbebbington
	 * @date	27 Feb 2017 - 10:43:37
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function option(array $options, string $selected = ''){
		foreach($options as $key => $data){
			print("<option value=\"{$key}\"");
			if($key === $selected){
				print(" selected=\"selected\"");
			}
			print(">{$data}</option>");
		}
		return $this;
	}
	
	/**
	 * Used for opening any element not covered here
	 * i.e. in your view:
	 * 		$this->open("div", "content", "col-xs-12", array('display' => "none"));
	 * will generate the following HTML:
	 * 		<div id="content" class="col-xs-12" style="display: none;">
	 * The style attribute has been added for those
	 * who use [primarily] jQuery to make web pages
	 * dynamic
	 * 
	 * @param	string, string, string | array, string | array, [boolean]
	 * @author	sbebbington
	 * @date	30 Mar 2017 - 11:22:00
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function open(string $element, string $id = '', $class = null, $style = null, bool $selfClose = false){
		print("<{$element}");
		if(strlen($id)>0){
			$this->id($id);
		}
		if(is_string($class) || is_array($class)){
			$this->class($class);
		}
		if(!empty($style) && (is_string($style) || is_array($file))){
			$this->style($style);
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
	 * the style attribute is added for use
	 * with jQuery and such
	 * 
	 * @param	int, string, string, string | array, string | array
	 * @author	sbebbington
	 * @date	30 Mar 2017 - 10:51:02
	 * @version	0.0.2
	 * @return	this
	 * @todo
	 */
	public function h(int $size, string $text, string $id = '', $class = null, $style = null){
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
		if(!empty($style) && (is_string($style) || is_array($style))){
			$this->style($style);
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
	public function javaScript(string $src = ''){
		print("<script type=\"text/javascript\"");
		print(strlen($src) ? " src=\"{$src}\">" : ">");
		return $this;
	}
	
	/**
	 * Sets title attribute within an HTML element
	 * 
	 * @param	string
	 * @author	sbebbington
	 * @date	28 Mar 2017 - 15:29:22
	 * @version	0.0.1
	 * @return	this
	 * @todo
	 */
	public function title(string $title = ''){
		print(" title=\"{$title}\"");
		return $this;
	}
	
	/**
	 * This will allow style="display: none;"
	 * and other such malevolence to be added
	 * to your HTML
	 * 
	 * @param	string | array
	 * @author	sbebbington
	 * @date	30 Mar 2017 - 10:52:43
	 * @version	0.0.1a
	 * @return	this
	 * @todo
	 */
	public function style($style = null){
		if(empty($style)){
			$style = '';
		}
		if(is_array($style)){
			$_style	= '';
			foreach($style as $key => $value){
				$_style	.= "{$key}: {$value};";
			}
			$style	= $_style;
			unset($_style);
		}
		print(" style=\"{$style}\"");
		
		return $this;
	}
}