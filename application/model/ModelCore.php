<?php 
namespace Application\Model;

class ModelCore
{
	protected $db;
	protected $connection;
	protected $table;
	
	public function __construct(){
		$password = "";
		$user		= "root";
		$host		= "127.0.0.1";
		$port		= 3306;
		$this->db	= "skeleton";
		try{
			$this->connection = new \PDO("mysql:host={$host};port:{$host};dbname={$this->db}", $user, $password);
		}catch(PDOException $e){
			echo '<pre>There was an issue connecting to the database: ' . PHP_EOL .
			print_r($e, 1) . '</pre>';
			die('<hr />');
		}
	}
}