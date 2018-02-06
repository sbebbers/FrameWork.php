<?php
namespace Application\Model;

use Application\Core\FrameworkException\FrameworkException;
use Application\Library\Library;
use PDO;
use PDOException;
use stdClass;
use PDOStatement;

require_once(serverPath("/core/FrameworkException.php"));

class ModelCore
{
    protected $db,
              $connection,
              $tables;
    protected $charSet    = "utf8";
    protected $userTypes = [
        "readUser",
        "writeUser"
    ];
    private $dbUser;
    
    /**
     * Constructor
     * 
     * @param   field_type
     * @author  sbebbington
     * @date    26 Sep 2017 14:43:38
     * @version 0.1.5-RC2
     * @return  void
     * @throws  FrameworkException
     */
    public function __construct(string $dbUser = ''){
        if(!in_array($dbUser, $this->userTypes)){
            throw new FrameworkException(
                "Database user type was not set or is incorrect when constructing the ModelCore",
                0x03
            );
        }
        
        if(file_exists(serverPath('/config/database.json'))){
            $dbConfig   = json_decode(file_get_contents(serverPath('/config/database.json')), true);
        }else{
            throw new FrameworkException("The framework requires a database configuration file at the application layer", 0xdb);
        }
        
        $this->lib      = new Library();
        $this->db       = $dbConfig['db'];
        $password       = $this->lib->decryptIt($dbConfig['password'][$dbUser]);
        $this->tables   = new stdClass();
        
        try{
            $this->connection = new PDO("mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$this->db};charset={$this->charSet}", $dbConfig[$dbUser], $password);
            $this->setTables($this->db);
        }catch(PDOException $e){
            throw new FrameworkException(
                $e->getMessage(),
                $e->getCode(),
                array(
                    "class" => __CLASS__,
                    "method" => __METHOD__,
                    "config" => $dbConfig,
                    "dbUser" => $dbUser
                )
            );
        }
        
        $this->setDbUser($dbUser);
    }
    
    /**
     * Sets the $dbUser object
     * 
     * @param string $dbUser
     * @author  sbebbington
     * @date    16 Jan 2018 15:36:02
     * @version 0.1.5-RC2
     * @return  void
     */
    public function setDbUser(string $dbUser){
        $this->dbUser = $dbUser;
    }
    
    /**
     * Gets the $dbUser object
     * 
     * @author  sbebbington
     * @date    16 Jan 2018 15:38:42
     * @version 0.1.5-RC2
     * @return  string
     */
    public function getDbUser(){
        return $this->dbUser;
    }
    
    /**
     * Sets the tables object
     * 
     * @param   field_type
     * @author  sbebbington
     * @date    24 Oct 2017 13:26:43
     * @version 0.1.5-RC2
     * @return  void
     */
    private function setTables(string $db = ''){
        $query = "SELECT `TABLE_NAME` FROM `INFORMATION_SCHEMA`.`TABLES` "
            . "WHERE `TABLE_TYPE`='BASE TABLE' AND `TABLE_SCHEMA`=?;";
        $result = $this->connection->prepare($query);
        
        $tables = $this->execute($this->connection->prepare($query), [$db], true);
        
        foreach($tables as $key => $data){
            $table = $data['TABLE_NAME'];
            $this->tables->{$table} = "{$table}";
        }
    }
    
    /**
     * Runs the MySQL query, returning the
     * required result
     *
     * @param   PDOStatement, array, bool, string, constant
     * @author  sbebbington
     * @date    24 Oct 2017 13:32:46
     * @version 0.1.5-RC2
     * @return  resource
     * @throws  FrameworkException
     */
    protected function execute(PDOStatement $query, array $parameters = [], bool $fetchAll = false, string $key = '', $fetchType = PDO::FETCH_ASSOC){
        $trace  = debug_backtrace();
        $caller = $trace[1];
        
        if(empty($query) || empty($parameters)){
            throw new FrameworkException(
                "PDO execution called without valid query or parameters",
                0x07,
                array(
                    "class"     => $caller['class'] ?? __CLASS__,
                    "method"    => $caller['function'] ?? __METHOD__,
                )
            );
        }
        
        if($query->execute($parameters) === true){
            if($fetchAll === false){
                $return = $query->fetch($fetchType);
            }else{
               $return = $query->fetchAll($fetchType);
            }
            return (strlen($key) > 0) ? $return[$key] : $return;
        }else{
            throw new FrameworkException(
                "Unable to run query, see PDOStatement as detailed",
                0x08,
                array(
                    "PDOStatement" => $query,
                    "class" => $caller['class'] ?? __CLASS__,
                    "method" => $caller['function'] ?? __METHOD__,
                    "parameters" => $parameters
                )
            );
        }
    }
}
