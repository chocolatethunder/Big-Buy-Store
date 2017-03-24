<?php

class registration {
	
	
	public $username;
	public $password1;
	public $password2;
	public $email;
	public $terms;
	public $invitecode;
	
	private $vusername;
	private $hashpassword;
	private $vemail;
	private $vterms;
	private $vinvitecode;
	
	private $userid;
	private $salt;
	private $emailCode;	
	private $phoneCode;
	private $dtime;
	
	private $dbo;
	private $log;
	
	function __construct($db, $username, $pass1, $pass2, $email, $terms) {
		$this->dbo = $db;
		
		$this->username 	= $username;
		$this->password1 	= $pass1;
		$this->password2	= $pass2;
		$this->email		= $email;
		$this->terms		= $terms;
		
		$this->dtime		= $date = date("Y-m-d H:i:s");
		$this->dbn			= Conf::DBNAME.".";
	}
	
	function validateUname () {
		
		global $error;
		
		if (isset($this->username) && !empty($this->username)) {			
			$uname = $this->username;
			if (preg_match("/^[a-zA-Z0-9_]+$/", $uname)) {
				if (strlen($uname) < 20) {				
					$this->dbo->select($this->dbn."USERS", array("uname"=>$uname));
					if ($this->dbo->rowcount == 0) {
						$this->vusername = $uname;
						return true;
					} else { $error["uname"] = "Username is not available"; }		
				} else { $error["uname"] = "Username too long"; }
			} else { $error["uname"] = "Alphabets, numbers and underscore only"; }
		} else { $error["uname"] = "Username cannot be blank"; }
		
		return false;
	}
	
	function validatePass () {
		
		global $error;
		
		if (isset($this->password1) && !empty($this->password1)) {			
			if (strlen($this->password1) > 8) {
				similar_text($this->password1, $this->password2, $perc);
				if ($perc == 100) {
					// HASH PASSWORD	
					$this->salt 		= bin2hex(openssl_random_pseudo_bytes(22));
					$this->salt 		= substr($this->salt, 0, 22);
					$this->hashpassword = crypt($this->password1, "$2y$12$".$this->salt);
					return true;
				} else { $error["pass1"] = "Passwords don't match"; $error["pass2"] = "Passwords don't match"; }
			} else { $error["pass1"] = "Password is too short. A minimum of 8 characters are required."; }
		} else { $error["pass1"] = "Password cannot be blank"; }
		
		return false;
	}
	
	private function validateEmail () {
		
		global $error;
		
		if (isset($this->email) && !empty($this->email)) {						
			if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
				$this->dbo->select($this->dbn."USERS", array("email"=>$this->email));
				if ($this->dbo->rowcount == 0) {
					$this->vemail = $this->email;
					return true;
				} else { $error["email"] = "Email already exists."; }		
			} else { $error["email"] = "That is not a valid email"; }
		} else { $error["email"] = "Email cannot be blank"; }
	
		return false;
	}
	
	private function validateTerms () {
		
		global $error;
		
		if (isset($this->terms) && !empty($this->terms)) {
			return true;
		} else { $error["terms"] = "You must agree to the terms and conditions."; }
		
		return false;
	}
	
	private function validateData () {
		
		global $securityCheck, $error; // Refer to class_security.php
		
		$validate = array();
		$validate[] = $this->validateUname();
		$validate[] = $this->validatePass();
		$validate[] = $this->validateEmail();
		$validate[] = $this->validateTerms();
		
		if (!in_array(false, $validate, true) == true) {
			if ($securityCheck->checkForm($_POST["form"]) == true) {
				return true;
			} else { $error["form"] = "Uh-oh! Something went wrong. Please try again."; }
		}
		return false;		
	}
	
	function processData () {
		
		global $gAuth, $error;
		
		if ($this->validateData() == true) {
			
			$this->emailCode 	= generateRandString(40);
			$querysuccess 		= array();
			
			$this->dbo->start();			
			
			// First query
			$newuser_data = array("uname" => $this->vusername, "pass" => $this->hashpassword, "email" => $this->vemail, "datetime" => $this->dtime); 
			$querysuccess["user_data"] = $this->dbo->insert($this->dbn."USERS", $newuser_data);
			
			$newuserid = $this->dbo->lastinsertid; 
			$_SESSION["uid"] = $newuserid; // This is so that send verification email can log the userid
			
			// Second query
			$useraddr_data = array("addrid" => $newuserid);
			$querysuccess["user_addr"] = $this->dbo->insert($this->dbn."ADDRESS",$useraddr_data );

			// Third query
			$userauth_data = array("uid" => $newuserid, "lvl" => 1, "address" => $newuserid, "emailCode" => $this->emailCode);			
			$querysuccess["user_auth"] = $this->dbo->insert($this->dbn."USERINFO", $userauth_data);

			// Fourth Query
			$usercart_data = array("belongsto" => $newuserid);
			$querysuccess["user_cart"] = $this->dbo->insert($this->dbn."SHOPPINGCART", $usercart_data);			
			
			
			if (!in_array(false, array_values($querysuccess), true) == true) {				
				$this->dbo->end();
				return true;
			}
			
			$this->dbo->rollback();	

			// Handle errors
			$error["form"] = "We failed to make you an account. Please try again.";
			
		}		
		return false;
	}
	
}

?>
