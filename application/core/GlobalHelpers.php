<?php 
if(!defined(PHP_EOL)){
	define(PHP_EOL, "\r\n", true);
}

/**
 * Works out whether or not it's .local or .com domain (for instance)
 * This is for the purposes of allowing session cookies and stuff.]
 *
 * @param	string, boolean
 * @author 	Shaun
 * @date 	12 May 2016 10:58:44
 * @version	0.0.3
 * @return	string
 * @todo
 */
function getDomain($domain, $https){
	$domain				= explode('.', $domain);
	$domain[0]			= ($https == true) ? str_replace('https://', '', $domain[0]) : str_replace('http://', '', $domain[0]);
	$countedElements	= sizeof($domain)-1;
	return ".{$domain[$countedElements-1]}.{$domain[$countedElements]}";
}

/**
 * This will check to see if the server is running over http or https
 *
 * @author	Shaun B
 * @version	0.0.1
 * @date	2016-19-02
 * @param	na
 * @return	boolean
 * @todo
 */
function isHttps(){
	return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off');
}

/**
 * Gets the path to the public facing directory
 * To include further file paths, include a following
 * / at the end
 *
 * @param	na
 * @author	Shaun
 * @date	6 Sep 2016 14:20:11
 * @version	0.0.1
 * @return	string
 * @todo
 */
function documentRoot($routeTo = null){
	$_x = str_replace("\\", "/", dirname(__FILE__));
	$_x .= ($routeTo === null) ? '' : $routeTo;
	return str_replace("//", "/", $_x);
}

/**
 * Returns the name of the current host file from
 * the PHP $_SERVER global thing #n00b
 *
 * @param	na
 * @author	Shaun
 * @date	5 Oct 2016 10:55:01
 * @version	0.0.1
 * @return	string
 * @todo
 */
function host(){
	return $_SERVER['HTTP_HOST'];
}

/**
 * Sets time zone, for a full list, see
 * http://php.net/manual/en/timezones.php
 * 
 * @param	string
 * @author	sbebbington
 * @date	24 Jan 2017 - 09:48:21
 * @version	0.0.1
 * @return	void
 * @todo
 */
function setTimeZone(string $timeZone){
	date_default_timezone_set($timeZone);
}