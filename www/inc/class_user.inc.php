<?php

class user {
	
	private $uid;
	private $dbo;
	private $dbn;
	
	private $udata;
	
	function __construct($dbo) {
		
		$this->uid = prep($_SESSION["uid"], "n");
		$this->dbo = $dbo;
		$this->dbn = Conf::DBNAME.".";
		
		// load all the data
		$this->udata = $this->dbo->joinselect($this->dbn."USERS", 
						array(	array("USERS" => "id", "USERINFO" => "uid"), 
								array("USERINFO" => "address", "ADDRESS" => "addrid")),
						array("id" => $this->uid));

	}
	
	private function userDataQuery($col) {
		if ($col != "pass" && array_key_exists($col, $this->udata)) {
			return $this->udata[$col];
		}
		return false;
	}
	
	public function account_type () {		
		return $this->udata["lvl"];
	}

	public function getUserData($type) {
		
		if (login::loginCheck() == true) {
				return $this->userDataQuery($type);	
		} else {
			login::logout();
		}
		return false;
		
	}
	
	public function getOrdersData() {
		
		
	}
	
}

?>