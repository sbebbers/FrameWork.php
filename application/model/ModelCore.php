<?php 
namespace Application\Model;
use Application\Library\Library;
use stdClass;
use PDOException;
use PDO;
use Application\Core\FrameworkException\FrameworkException;

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
	 * @return	bare_field_name
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
			echo '<pre>There was an issue connecting to the database</pre>';
			$this->lib->debug($e, true);
		}
	}
}
