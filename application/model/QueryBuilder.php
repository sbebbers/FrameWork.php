<?php
namespace Application\Model;

use Application\Core\FrameworkException\FrameworkException;

if(!defined('FRAMEWORKPHP') || FRAMEWORKPHP != 65535){
    require_once("../view/403.phtml");
}

require_once(serverPath("/core/FrameworkException.php"));

class QueryBuilder
{
    protected $config,
              $select,
              $from,
              $where;
    
    public function __construct(){
        if(file_exists(serverPath('/config/database.json'))){
            $this->config   = json_decode(file_get_contents(serverPath('/config/database.json')), true);
        }else{
            throw new FrameworkException("The framework requires a database configuration file at the application layer", 0xdb);
        }
    }
    
    /**
     * Returns the specific database configuration
     * settings
     *
     * @author  Shaun B
     * @date	18 Apr 2018 09:59:02
     * @return  resource
     */
    public function getConfig(){
        return $this->config;
    }
    
    /**
     * Builds SELECT part of the query
     *
     * @param   array $items
     * @author  Shaun B
     * @date	18 Apr 2018 10:32:32
     * @return  $this
     */
    public function select(array $items = []){
        if(empty($items)){
            $items = ['*'];
        }
        $select = 'SELECT ';
        foreach($items as $item){
            $select .= $item === '*' ? $item : "`{$item}`,";
        }
        if(strpos($select, ',') > 0){
            $select = substr($select, 0, -1);
        }
        $this->select = $select;
        return $this;
    }
    
    /**
     * Builds the 
     *
     * @param   string $table, [$database]
     * @author  Shaun B
     * @date	18 Apr 2018 09:55:33
     * @return  $this
     * @throws  FrameworkException
     */
    public function from(string $table = '', string $database = ''){
        if(strlen($database) === 0){
            $database = getConfig('db');
        }
        if(strlen($table) === 0){
            throw new FrameworkException("Malformed SELECT statement in Application\Model::QueryBuilder()");
        }
        $this->from = " FROM `{$database}`.`{$database}`";
        return $this;            
    }
    
    /**
     * Shorthand for SELECT * FROM `db`.`table`
     *
     * @param   array $items, string $table, string $database
     * @author  Shaun B
     * @date	18 Apr 2018 10:34:41
     * @return  $this
     */
    public function selectFrom(array $items = [], string $table = '', string $database = ''){
        $this->select($items)->from($table, $database);
        return $this;
    }
    
    /**
     * Builds the where clause
     *
     * @param   array $conditions
     * @author  Shaun B
     * @date	10 May 2018 16:24:09
     * @return  $this
     */
    public function where(array $conditions = []){
        
    }
}
