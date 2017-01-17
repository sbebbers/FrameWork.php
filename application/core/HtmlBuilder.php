<?php 
namespace Application\Core\Framework;

class HtmlBuilder{
	public function __construct(){
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
	
	public function p(){
		print("<p");
		return $this;
	}
	
	public function id($id){
		print(" id=\"{$id}\"");
		return $this;
	}
	
	public function class($class){
		print(" class=\"{$class}\"");
	}
	
	public function dataAttr($attrName, $data){
		print(" data-{$attrName}=\"{$data}\"");
		return $this;
	}
	
	public function closeElement($selfClose = false){
		print($selfClose === false ? ">" : " />");
		return $this;
	}
	
	public function text($text, $decode = false){
		print($decode === false ? $text : htmlspecialchars_decode($text));
		return $this;
	}
	
	public function input(){
		print("<input");
		return $this;
	}
	
	public function name($name){
		print(" name=\"{$name}\"");
		return $this;
	}
	
	public function type($type){
		print(" type=\"{$type}\"");
		return $this;
	}
	
	public function value($value){
		print(" value=\"{$value}\"");
		return $this;
	}
	
	public function disabled($disabled = true){
		print($disabled === true ? " disabled=\"disabled\"" : "");
		return $this;
	}
}