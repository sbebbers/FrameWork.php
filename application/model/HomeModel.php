<?php
namespace Application\Model;
use PDO;

require_once(serverPath("/model/ModelCore.php"));

class HomeModel extends ModelCore
{
	protected $table;
	public function __construct(){
		ModelCore::__construct();
		$this->table = "view";
	}
	
	/**
	 * Gets the generic view stuff
	 * 
	 * @param	[string]
	 * @author	sbebbington
	 * @date	13 Jan 2017 10:13:07
	 * @version	0.0.1
	 * @return	array
	 */
	public function getView($colName = 'home'){
		$query	= "SELECT `header`, `sub_header`, `content` FROM `{$this->db}`.`{$this->table}` "
				."WHERE `name`=?;";
		$result	= $this->connection->prepare($query);
		$result->execute(array($colName));
		return $result->fetch(PDO::FETCH_ASSOC);
	}
}
