<?php 
namespace Application\Model;
use Application\Library\Library;
use stdClass;
use PDOException;
use PDO;

class ModelCore
{
	protected $db, $connection, $tables, $table;
	protected $charSet	= "utf8";
	
	public function __construct(){
		if(file_exists(serverPath('/config/database.json'))){
			$dbConfig	= json_decode(file_get_contents(serverPath('/config/database.json')), true);
		}else{
			die("<pre>The framework requires a database configuration file at the application layer</pre>");
		}
		$this->db		= $dbConfig['db'];
		$password		= $dbConfig['password'] ?? '';
		$this->tables	= new stdClass();
		$this->lib		= new Library();
		
		try{
			$this->connection = new PDO("mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$this->db};charset={$this->charSet}", $dbConfig['user'], $password);
		}catch(PDOException $e){
			echo '<pre>There was an issue connecting to the database</pre>';
			$this->lib->debug($e,true);
		}
	}
}
