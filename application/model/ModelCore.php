<?php
namespace Application\Model;

use Application\Core\FrameworkException\FrameworkException;
use Application\Library\Library;
use PDO;
use PDOException;
use stdClass;

require_once(serverPath("/core/FrameworkException.php"));

class ModelCore
{
	protected $db, $connection, $tables, $table;
	protected $charSet	= "utf8";
	
	/**
	 * Constructor
	 * 
	 * @param	field_type
	 * @author	sbebbington
	 * @date	26 Sep 2017 14:43:38
	 * @version 0.1.3a
	 * @return	void
	 * @throws  \Application\Core\FrameworkException\FrameworkException
	 */
	public function __construct(){
		if(file_exists(serverPath('/config/database.json'))){
			$dbConfig	= json_decode(file_get_contents(serverPath('/config/database.json')), true);
		}else{
		    throw new FrameworkException("The framework requires a database configuration file at the application layer", "0xdb");
		}
		$this->db		= $dbConfig['db'];
		$password		= $dbConfig['password'] ?? '';
		$this->tables	= new stdClass();
		$this->lib		= new Library();
		
		try{
		    $this->connection = new PDO("mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$this->db};charset={$this->charSet}", $dbConfig['user'], $password);
		}catch(PDOException $e){
		    throw new FrameworkException(
		        $e->getMessage(),
		        $e->getCode(),
		        array(
		            "class" => __CLASS__,
		            "method" => __METHOD__,
		            "classObjects" => $this
		        )
		    );
		}
	}
}
