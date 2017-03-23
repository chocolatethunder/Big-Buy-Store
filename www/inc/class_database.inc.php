<?php

// The database class needs to stay because sessions functions depends on it.
class database {
	
	public $connection;
	public $last_query;
	private $db;
	
	function __construct($dbname) {
		$this->db = $dbname;
		$this->db_connect();	
	}
	
	private function db_connect() {
		$this->connection = mysqli_init();		
		mysqli_options($this->connection, MYSQLI_INIT_COMMAND, "SET time_zone = UTC");		
		mysqli_ssl_set($this->connection, NULL, NULL, NULL, NULL, NULL);	
		if (!mysqli_real_connect($this->connection, Conf::DBHOST, Conf::DBUSER, Conf::DBPASS)) {
			die("Could not connect to the Database"); 
		} else {
			if (!mysqli_select_db($this->connection, $this->db)) {
				die("Could not connect to the Database");	
			}
		}
	}
	
}

class pdodb {
	
	public $PDO;
	public $dbname;
	public $stmt;
	public $rowcount;
	public $lastinsertid;
	public $ecode;
	
	function __construct($database) {		
		(!$database ? die("No database selected") : $this->dbname = $database);		
		$this->connect();		
	}
	
	function connect() {
		
		$dbopts = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = 'UTC'");
		
		try {			
			$this->PDO = new PDO ("mysql:host=".Conf::DBHOST.";dbname=".$this->dbname.";charset=utf8", Conf::DBUSER, Conf::DBPASS, $dbopts);
			$this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
		} catch (PDOException $e) {
			die("Fatal error trying to connect to the database");
		}
		
	}
	
	private function bind($prefix, $opts) {		
		if (is_array($opts)) {
			foreach ($opts as $key => $value) {
				$bind[":".$prefix.$key] = $value;
			}
			return $bind;
		}
		return null;
	}
	
	private function filter($table, $info) {
		$driver = $this->PDO->getAttribute(PDO::ATTR_DRIVER_NAME);
		if($driver == 'sqlite') {
			$sql = "PRAGMA table_info('" . $table . "');";
			$key = "name";
		}
		elseif($driver == 'mysql') {
			$sql = "DESCRIBE " . $table . ";";
			$key = "Field";
		}
		else {	
			$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
			$key = "column_name";
		}	

		if(false !== ($list = $this->go($sql))) {
			$fields = array();
			foreach($list as $record)
				$fields[] = $record[$key];
			return array_values(array_intersect($fields, array_keys($info)));
		}
		return array();
	}
	
	public function go($sql, $bind=NULL, $single=TRUE) {
		global $logit;
		try {
			$query = $this->PDO->prepare($sql);

			if ($query->execute($bind) !== false) {
				$this->rowcount = $query->rowCount();
				if(preg_match("/^(" . implode("|", array("select")) . ") /i", $sql)) {
					if ($this->rowcount > 1 || $single == FALSE) {
						$result = $query->fetchAll(PDO::FETCH_ASSOC);
					} else {
						$result = $query->fetch(PDO::FETCH_ASSOC);
					}
				} elseif (preg_match("/^(" . implode("|", array("delete", "update")) . ") /i", $sql)) {
					return true;
				} elseif (preg_match("/^(" . implode("|", array("insert")) . ") /i", $sql)) {
					$this->lastinsertid = $this->PDO->lastInsertId();
					return true;
				} else {
					$result = $query->fetchAll(PDO::FETCH_ASSOC);
				}
				return $result;
			}
			
		} catch (PDOException $e){
			$logit->database($e->getMessage());
			//$code = $query->errorInfo();
			//$this->ecode = ("CODE: ".$code[1]);
		}
		return false;	
	}
	
	public function select($table, $where=NULL, $cond=NULL, $col="*", $lock=FALSE, $single=TRUE) {		
		
		$prefix 	= "sel_";		
		$bind 		= $this->bind($prefix, $where);
		$col 		= (is_array($col) ? "`".implode("`, `",$col)."`" : $col);
		$table		= str_replace(".","`.`",$table);
		
		// Start forming query
		$sql = "SELECT ".$col." FROM `".$table."`";
		// Add any where arguments
		$sql .= ($where != NULL ? " WHERE ".implode(' AND ', array_map(function ($v, $k) { return "`".$k."` = :sel_".$k.""; }, $where, array_keys($where))) : NULL);
		// Add any conditions
		$sql .= ($cond != NULL ? " ".implode(" ",$cond) : NULL);
		// Add rowlock
		$sql .= ($lock == TRUE ? " FOR UPDATE" : NULL);
		
		// Run the query
		$result = $this->go($sql, $bind, $single);		
		if ($result !== NULL && $result !== FALSE) {
			return $result;
		}
		return false;
		
	}
	
	public function update($table, $set, $where=NULL, $cond=NULL) {
		
		$prefix 	= "upd_";		
		$bind 		= $this->bind($prefix, $set);
		$bind		= array_merge($bind, $this->bind($prefix, $where));
		$table		= str_replace(".","`.`",$table);
		
		// Start forming query
		$sql = "UPDATE `".$table."`";
		// Add data arguments
		$sql .= ($set != NULL ? " SET ".implode(', ', array_map(function ($v, $k) { return "`".$k."` = :upd_".$k.""; }, $set, array_keys($set))) : NULL);
		// Add any where arguments
		$sql .= ($where != NULL ? " WHERE ".implode(' AND ', array_map(function ($v, $k) { return "`".$k."` = :upd_".$k.""; }, $where, array_keys($where))) : NULL);
		// Add any conditions
		$sql .= ($cond != NULL ? " ".implode(" ",$cond) : NULL);
		
		// Run the query
		$result = $this->go($sql, $bind);		
		if ($result !== NULL && $result !== FALSE) {
			return $result;
		}
		return false;

	}
	
	public function insert($table, $data, $cond=NULL) {
		
		global $logit;
		
		$prefix 	= "ins_";		
		$bind 		= $this->bind($prefix, $data);
		$table		= str_replace(".","`.`",$table);
		
		// Start forming query
		$sql = "INSERT INTO `".$table."` ";
		// Add data arguments
		$sql .= "( `".implode("`, `", array_keys($data))."` ) VALUES ";
		$sql .= "( :".$prefix.implode(", :".$prefix, array_keys($data))." )";
		// Add any conditions
		$sql .= ($cond != NULL ? " ".implode(" ",$cond) : NULL);
	
		// Run the query
		$result = $this->go($sql, $bind);		
		if ($result !== NULL && $result !== FALSE) {
			return $result;
		}
		return false;
		
	}
	
	public function delete($table, $where, $cond=NULL) {
		
		$prefix 	= "del_";		
		$bind 		= $this->bind($prefix, $where);
		$table		= str_replace(".","`.`",$table);
		
		// Start forming query
		$sql = "DELETE FROM `".$table."` ";
		// Add any where arguments
		$sql .= ($where != NULL ? " WHERE ".implode(' AND `'.$table."`.", array_map(function ($v, $k) { return "`".$k."` = :del_".$k.""; }, $where, array_keys($where))) : NULL);
		// Add any conditions
		$sql .= ($cond != NULL ? " ".implode(" ",$cond) : NULL);

		// Run the query
		$result = $this->go($sql, $bind);		
		if ($result !== NULL && $result !== FALSE) {
			return $result;
		}
		return false;
		
	}
	
	// Why didn't I just extend PDO class?
	
	public function exec($query) {
		$data 	= $this->PDO->exec($query);
		//$data	= $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	
	public function runquery($stmt, $data=null) {
		$query = $this->PDO->prepare($stmt);
		$query->execute($data);
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
	public function clean($str) {
		return $this->PDO->quote($str);
	}
	
	public function start(){
		$this->PDO->beginTransaction();
	}
	
	public function end(){
		$this->PDO->commit();
	}
	
	public function commit(){
		$this->PDO->commit();
	}
	
	public function rollback(){
		$this->PDO->rollBack();
	}
	
	public function close(){
		$this->PDO = NULL;
	}

}

?>
