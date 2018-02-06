<?php
use Application\Controller\ControllerCore;

class DateController extends ControllerCore
{
    public function __construct(){
        ControllerCore::__construct();
        
        if(!empty($this->post)){
            $daySubmitted   = $this->post['day'];
            $monthSubmitted = $this->post['month'];
            $yearSubmitted  = $this->post['year'];
            $dateSubmitted  = "You submitted the following date: {$daySubmitted}/{$monthSubmitted}/{$yearSubmitted} ";
    
            if($this->checkDateValidity($daySubmitted, $monthSubmitted, $yearSubmitted) == true){
                $dateSubmitted  .= " - This is a valid date";
            }else{
                $dateSubmitted  .= " - This is not a valid date";
            }
            
            $this->view->dateSubmitted  = $dateSubmitted;
            goto end;
        }
        
        $day    = array(
            ''  => "-- Select Day --",
        );
        $month    = array(
            ''  => "-- Select Month --",
        );
        $year    = array(
            ''  => "-- Select Year --",
        );
        
        $day    += $this->setDays((int)date('d'));
        $month  += $this->setMonths('full', 'numeric', date('m'));
        $year   += $this->setYears(1901, (int)date('Y'), 'asc', (int)date('Y'));
        
        $this->view->days   = $day;
        $this->view->months = $month;
        $this->view->years  = $year;    
        end:
    }
    
    /**
     * Sets the number of days for an array assuming
     * 1 - 31 inclusive as date validation is handled
     * dynamically in the jQuery
     * 
     * @param   int
     * @author  sbebbington
     * @date    4 Jul 2017 17:15:59
     * @version 0.1.5-RC2
     * @return  array
     */
    protected function setDays(int $default = 0){
        $days    = array();
        for($i = 1; $i <= 31; $i++){
            $day    = "{$i}";
            if($i<10){
                $day    = "0{$i}";
            }
            $days[$day] = $day;
        }
        if($default == 0){
            goto end;
        }
        $days['default']    = ($default < 10) ? "0{$default}" : "{$default}";
        
        end:
        return $days;
    }
    
    /**
     * Sets a month object, keys and data can
     * each be set as numeric (01 - 12 inclusive),
     * short (jan, feb etc...) or full (january,
     * february etc...) send data type first and
     * then key type
     * 
     * @param   string, string
     * @author  sbebbington
     * @date    5 Jul 2017 10:13:08
     * @version 0.1.5-RC2
     * @return  array
     */
    protected function setMonths(string $type = "full", string $keyType = "numeric", string $default = ''){
        $types = array(
            'full',
            'short',
            'numeric'
        );
        if(empty($type) || empty($keyType) || !in_array($type, $types) || !in_array($keyType, $types)){
            return ["Error setting month object, please set type and key type as full, short or numeric"];
        }
        $keys    = array(
            'numeric'   => array(
                "01", "02", "03", "04",
                "05", "06", "07", "08",
                "09", "10", "11", "12"
            ),
            'full'      => array(
                "January", "February", "March", "April", "May", "June", "July",
                "August", "September", "October", "November", "December"
            ),
            'short'     => array(
                "Jan", "Feb", "Mar",
                "Apr", "May", "Jun",
                "Jul", "Aug", "Sep",
                "Oct", "Nov", "Dec"
            )
        );
        $months = array();
        $index  = 0;
        foreach($keys[$keyType] as $primaryKey){
            $months[$primaryKey]    = $keys[$type][$index];
            $index++;
        }
        if($default == ''){
            goto end;
        }
        $months['default']  = $default;
        
        end:
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
     * @param   int, int, string, int
     * @author  sbebbington
     * @date    5 Jul 2017 10:10:45
     * @version 0.1.5-RC2
     * @return  array
     */
    protected function setYears(int $start = 1977, int $end = 2017, $order = "asc", int $default = 0){
        $ordering   = array(
            'asc',
            'desc'
        );
        $order      = strtolower($order);
        
        if(!in_array($order, $ordering)){
            return ["Please set your ordering to ascending [asc] or descending [desc]"];
        }
        if($start > $end){
            return ["Error setting the year object, please check that your start year is before your end year"];
        }
        
        $years  = array();
        if($order == "asc"){
            for($i = $start; $i <= $end; $i++){
                $years["{$i}"]    = $i;
            }
            goto checkDefault;
        }
        for($i = $end; $i >= $start; $i--){
            $years["{$i}"]    = $i;
        }
        
        checkDefault:
        if(!in_array($default, $years)){
            goto end;
        }
        $years['default']    = $default;
        
        end:
        return $years;
    }
    
    /**
     * Checks the full date submitted to see if it is
     * valid according to the parameters of the
     * Gregorian calander
     * 
     * @param   int, int, int
     * @author  sbebbington
     * @date    6 Jul 2017 13:50:32
     * @version 0.1.5-RC2
     * @return  boolean
     */
    protected function checkDateValidity($day = null, $month = null, $year = null){
        if($day == null || $month == null || $year == null){
            return false;
        }
        return checkdate($month, $day, $year);
    }
    
    /**
     * Returns the default values to the view
     * to auto-select day, month, and year
     *
     * @param   stdClass
     * @author  sbebbington
     * @date    6 Jul 2017 11:37:21
     * @version 0.1.5-RC2
     * @return  string | null
     */
    public function getDefault($viewObject = null){
        if($viewObject == null){
            return '';
        }
        return $viewObject['default'] ?? null;
    }
    
    /**
     * Clears the default parameter from the view object
     * should one exist
     *
     * @param   stdClass | array
     * @author  sbebbington
     * @date    6 Jul 2017 11:49:16
     * @version 0.1.5-RC2
     * @return  array
     */
    public function clearDefault($viewObject = null){
        if($viewObject == null){
            return '';
        }
        $viewObject['default']    = null;
        return array_filter($viewObject, 'strlen');
    }
}
