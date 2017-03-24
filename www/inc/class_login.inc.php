<?php

class login {
	
	private $user;
	private $pass;
	
	private $uid;
	private $uname;
	private $uac;
	private $email;
	
	public $dbo; // database object
	public $logit;
	private $dbn;
	
	function __construct($db, $user, $pass) {
		
		$this->dbo 		= $db;
		$this->dbn		= Conf::DBNAME.".";
		
		$this->user 	= $user;
		$this->pass 	= $pass;		
		$this->dtime 	= date("Y-m-d H:i:s");
		
		$this->checkLogin();
	}
	
	/* VALIDATION FUNCTIONS */
	
	private function validateUname () {
		
		global $error;
		
		$uname = $this->user;	
		
		if (!$this->user) {			
			$error["uname"] = "Username cannot be blank";			
		} else {			
			if (!preg_match("/^[a-zA-Z0-9_]+$/", $uname)) {				
				$error["uname"] = "Alphabets, numbers and underscore only";
			} else {
				if (strlen($uname) > 20) {				
					$error["uname"] = "Username too long";		
				} else {
					return true;					
				}
			}
		}
		$this->user = NULL;
		return false;				
	}
	
	private function validatePass () {
		
		global $error;
		
		if (!$this->pass) {					
			$error["pass"] = "Password cannot be blank";		
		} else {			
			return true;			
		}
		$this->pass = NULL;
		return false;
	}
	
	private function validateForm () {
		
		global $securityCheck;
		
		$validate = array();
		
		$validate[] = $this->validateUname();
		$validate[] = $this->validatePass();
		
		if (!in_array(false, $validate, true)) {
			if ($securityCheck->checkForm($_POST["form"]) == true) {
				return true;
			} else { $error["form"] = "Uh-oh! Something went wrong. Please try again."; }
		} else {
			return false;
		}
		
	}	
	
	/* END VALIDATION FUNCTIONS */
	
	/* LOGIN CHECK FUNCTIONS */

	private function checkCredentials() {
		
		global $error;
		
		$userdata = $this->dbo->select($this->dbn."USERS", array("uname" => $this->user), null, array("id","uname","pass","email"));
		if ($this->dbo->rowcount == 1) {			
			$crypted 		= crypt($this->pass, $userdata["pass"]);
			$this->uid 		= $userdata["id"];
			$this->uname 	= $userdata["uname"];
			$this->email 	= $userdata["email"];
			
			$_SESSION["uid"] = $this->uid;			

			if ($crypted === $userdata["pass"]) {
				return true;
			} else {
				$error["form"] = "Bad username or password";
			}

		} else {
			$error["form"] = "Bad username or password";
		}
		return false;
	}
	
	private function checkLogin () {
		
		global $error;
		
		if ($this->validateForm() == true) {		

			if ($this->checkCredentials() == TRUE) {
				
				session_regenerate_id();
				
				$_SESSION["uid"] 			= $this->uid;
				$_SESSION["uname"] 			= $this->uname;
				$_SESSION["email"] 			= $this->email;
				$_SESSION["lastactivity"] 	= time();
				$_SESSION["loggedin"] = 1;
				
				$sessionid = session_id();				
				
				gotoPage("index.php");
				return true;
			}
	
		}
		unset($_SESSION['uid']);
		return false;		
	}	

	
	/* END CHECK FUNCTIONS */
	
	public static function resetsession() {
		
		$_SESSION["uid"] 			= NULL;
		$_SESSION["uname"] 			= NULL;
		$_SESSION["lastactivity"] 	= NULL;
		$_SESSION["loggedin"] 		= NULL;
		
	}
	
	public static function loginCheck () {
		
		$sessionVarCheck 	= array();
		$sessionVarCheck[] 	= !empty($_SESSION["uid"]);
		$sessionVarCheck[] 	= !empty($_SESSION["uname"]);
		$sessionVarCheck[] 	= !empty($_SESSION["lastactivity"]);
		$sessionVarCheck[] 	= (!empty($_SESSION["loggedin"]) ? ($_SESSION["loggedin"] == 1 ? true : false) : false );		

		if (!in_array(false, $sessionVarCheck, true) == true && self::sessionTimeout() == false) {
			// still logged in
			return true;
		}
		// Not logged in
		return false;		
	}
	
	public static function logout() {

		login::resetsession();
		
		unset($_SESSION);
		
		session_unset();
        session_destroy();
		
		gotoPage("login.php");
		
	}
	
	public static function sessionTimeout () {
		if (isset($_SESSION["lastactivity"]) && ((time() - $_SESSION["lastactivity"]) > Conf::EXPIRETIME)) {
			return true;		
		}
		$_SESSION["lastactivity"] = time();
		return false;		
	}
	
}

?>