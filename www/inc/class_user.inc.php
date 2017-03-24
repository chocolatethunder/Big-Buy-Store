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
	
	public function account_type() {		
		return $this->udata["lvl"];
	}
	
	public function getuid() {
		return $this->uid;
	}

	public function getUserData($type) {		
		if (login::loginCheck() == true) {
			return $this->userDataQuery($type);	
		} else {
			login::logout();
		}
		return false;		
	}
	
	public function getUserFullNameString() {		
		return $this->userDataQuery("fname")." ".$this->userDataQuery("lname")."<br/>";		
	}
	
	public function getUserAddressString() {		
		$addr = $this->userDataQuery("street").", ".$this->userDataQuery("unit")."<br/>".$this->userDataQuery("city").", ".$this->userDataQuery("province")."<br/>".$this->userDataQuery("pcode").", ".$this->userDataQuery("country")."<br/>".$this->userDataQuery("phone");		
		return $addr;		
	}
	
	public function isAddressInfoComplete() {		
		$addressarray = array ($this->udata["street"],$this->udata["city"],$this->udata["province"],$this->udata["pcode"],$this->udata["country"],$this->udata["phone"]);		
		if (!in_array(NULL,$addressarray, true)) {
			return true;
		}		
		return false;
	}
	
	public function userPendingUpgrade() {
		switch ($this->userDataQuery("upgrade")) {
			case 'N':
			return false;
			
			case 'Y':
			return true;
			
			default:
			return false;
		}
	}
	
	public function activateUpgrade() {
		if ($this->dbo->update($this->dbn."USERINFO", array("upgrade" => "Y"), array("uid" => $this->getuid()))) {
			return true;
		}
		return false;
	}
	
	public function performPasswordChange ($passo, $pass1, $pass2) {
		
		global $error, $securityCheck;
		
		if ($securityCheck->checkForm($_POST["form"]) == true) {
			
			if (isset($passo)) {
				
				if (isset($pass1) && !empty($pass2)) {
				
					if (strlen($pass1) > 8) {
						
						$crypted = crypt($passo, $this->udata["pass"]);
						
						if ($crypted === $this->udata["pass"]) {
							
							similar_text($pass1, $pass2, $matchpair);
						
							if ($matchpair == 100) {
	
								$salt 		= bin2hex(openssl_random_pseudo_bytes(22));
								$salt 		= substr($salt, 0, 22);
								$hashpassword = crypt($pass1, "$2y$12$".$salt);
								
								if (isset($hashpassword) && !empty($hashpassword)) {

									if ($this->dbo->update($this->dbn."USERS", array("pass" => $hashpassword), array("id" => $this->uid))) {
										return true;									
									}			
								
								}
								
							} else { $error["pass1"] = "Passwords don't match"; $error["pass2"] = "Passwords don't match"; }							
							
						} else { $error["passo"] = "You have entered an incorrect password"; }				
						
					} else { $error["pass1"] = "Password is too short. A minimum of 8 characters are required."; }
				
				} else { $error["pass1"] = "Password cannot be blank"; }
				
			} else { $error["passo"] = "You must enter your current password"; }		
		
		}
		return false;
	}
	
}

?>