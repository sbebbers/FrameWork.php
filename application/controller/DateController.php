<?php
require_once (serverPath('/controller/ControllerCore.php'));

class DateController extends \Application\Controller\ControllerCore
{
	public function __construct(){
		parent::__construct();
		$day	= array(
			''	=> "-- Select Day --",
		);
		$month	= array(
			''	=> "-- Select Month --",
		);
		$year	= array(
			''	=> "-- Select Year --",
		);
		
		$day	+= $this->setDays();
		$month	+= $this->setMonths();
		$year	+= $this->setYears(1901, (int)date('Y'), 'asc');
		
		$this->view->days	= $day;
		$this->view->months	= $month;
		$this->view->years	= $year;
		
		if(isset($this->post['submit'])){
			// Do something with the posted data here, but for now
			// we'll simply see the contents of the posted data
			// $this->lib->debug($this->post, true);
		}
	}
	
	/**
	 * Sets the number of days for an array assuming
	 * 1 - 31 inclusive as date validation is handled
	 * dynamically in the jQuery
	 * 
	 * @param	na
	 * @author	sbebbington
	 * @date	4 Jul 2017 - 17:15:59
	 * @version	0.0.1
	 * @return	array
	 */
	protected function setDays(){
		$days	= array();
		for($i=1; $i<31; $i++){
			$day	= "{$i}";
			if($i<10){
				$day	= "0{$i}";
			}
			$days[$day]	= $day;
		}
		return $days;
	}
	
	/**
	 * Sets a month object, keys and data can
	 * each be set as numeric (01 - 12 inclusive),
	 * short (jan, feb etc...) or full (january,
	 * february etc...) send data type first and
	 * then key type
	 * 
	 * @param	string, string
	 * @author	sbebbington
	 * @date	5 Jul 2017 - 10:13:08
	 * @version	0.0.1
	 * @return	array
	 */
	protected function setMonths(string $type = "full", string $keyType = "numeric"){
		$types = array(
			'full', 'short', 'numeric'
		);
		if(empty($type) || empty($keyType) || !in_array($type, $types) || !in_array($keyType, $types)){
			return ["Error setting month object, please set type and key type as full, short or numeric"];
		}
		$keys	= array(
			'numeric' => array(
				"01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"
			),
			'full'	=> array(
				"January", "February", "March", "April", "May", "June", "July",
				"August", "September", "October", "November", "December"
			),
			'short' => array(
				"Jan", "Feb", "Mar", "Apr", "May", "Jun",
				"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
			)
		);
		$months	= array();
		$index	= 0;
		foreach($keys[$keyType] as $primaryKey){
			$months[$primaryKey]	= $keys[$type][$index];
			$index++;
		}
		return $months;
	}
	
	/**
	 * Example method to set the number of years
	 * by sending two integers starting year and
	 * ending year. To set order, use 'asc' for
	 * assending years and 'desc' for decending
	 * years - please note that this method has
	 * a practical use for the deaded goto
	 * command, replacing if/else logic
	 * 
	 * @param	int, int, string
	 * @author	sbebbington
	 * @date	5 Jul 2017 - 10:10:45
	 * @version	0.0.1
	 * @return	array
	 */
	protected function setYears(int $start = 1977, int $end = 2017, $order = "asc"){
		$ordering	= array(
			'asc', 'desc'
		);
		$order		= strtolower($order);
		if(!in_array($order, $ordering)){
			return ["Please set your ordering to ascending [asc] or descending [desc]"];
		}
		if($start > $end){
			return ["Error setting the year object, please check that your start year is before your end year"];
		}
		$years	= array();
		if($order == "asc"){
			for($i = $start; $i <= $end; $i++){
				$years["{$i}"]	= $i;
			}
			goto end;
		}
		for($i = $end; $i >= $start; $i--){
			$years["{$i}"]	= $i;
		}
		
		end:
		return $years;
	}
}