<?php 
namespace Application\Model;

class ModelCore
{
	protected $db, $connection, $tables, $table;
	protected $charSet	= "utf8";
	
	public function __construct(){
		$password		= (getServerIPAddress() == '127.0.0.1') ? "" : "database_password";
		$user			= (getServerIPAddress() == '127.0.0.1') ? "root" : "database_username";
		$host			= getServerIPAddress();
		$port			= 3306;
		$this->db		= (getServerIPAddress() == '127.0.0.1') ? "skeleton" : "database_name";
		
		try{
			$this->connection = new \PDO("mysql:host={$host};port:{$host};dbname={$this->db};charset={$this->charSet}", $user, $password);
		}catch(PDOException $e){
			echo '<pre>There was an issue connecting to the database: ' . PHP_EOL .
			print_r($e, 1) . '</pre>';
			die('<hr />');
		}
	}
}