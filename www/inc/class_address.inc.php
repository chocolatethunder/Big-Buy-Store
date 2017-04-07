<?php

class address extends user {
	
	private $dbo;
	private $dbn;
	
	// dirty variables
	public $fname;
	public $lname;
	public $street;
	public $apt;
	public $city;
	public $state;
	public $pcode;
	public $country;
	public $phone;
	
	// cleaned up verified variables
	private $vfname;
	private $vlname;
	private $vstreet;
	private $vapt;
	private $vcity;
	private $vstate;
	private $vpcode;
	private $vcountry;
	private $vphone;
	private $dtime;
	
	function __construct($db) {
		
		$this->dbo 		= $db;
		$this->dbn 		= Conf::DBNAME.".";
		$this->dtime 	= date("Y-m-d H:i:s");
		
		// Invoke the parent
		parent::__construct($this->dbo);

	}
	
	private function validatefname () {
		
		global $error;
		
		if (isset($this->fname) && !empty($this->fname)) { // check for value set
		
			$fname = $this->fname;
			
			if (preg_match("/^[a-zA-Z\- ]+$/", $fname)) { // check if alphanum and -
			
				if (strlen($fname) <= 10) { // make sure less it is less than 100 chars					
					
					$this->vfname = $fname;
					return true;
					
				} else { $error["fname"] = "First name too long"; }
				
			} else { $error["fname"] = "Invalid Characters"; }	
			
		} else { $error["fname"] = "First name cannot be blank"; }
		
		return false;
	}
	
	private function validatelname () {
		
		global $error;
		
		if (isset($this->lname) && !empty($this->lname)) { // check for value set
		
			$lname = $this->lname;
			
			if (preg_match("/^[a-zA-Z\- ]+$/", $lname)) { // check if alphanum and -
			
				if (strlen($lname) <= 100) { // make sure less it is less than 100 chars
					
					$this->vlname = $this->lname;
					return true;
					
				} else { $error["lname"] = "Last name too long"; }
				
			} else { $error["lname"] = "Invalid Characters"; }
			
		} else { $error["lname"] = "Last name cannot be blank";	}
		
		return false;
	}
	
	private function validatestreet () {
		
		global $error;
		
		if (isset($this->street) && !empty($this->street)) { // check for value set
		
			$street = $this->street;
			
			if (preg_match("/^[a-zA-Z0-9-. ]+$/", $street)) { // check if alphanum and -
			
				if (strlen($street) <= 100) { // make sure less it is less than 100 chars
					
					$this->vstreet = $this->street;
					return true;
					
				} else { $error["street"] = "Street name too long";	}
			
			} else { $error["street"] = "Invalid Characters";}
		
		} else { $error["street"] = "Street name cannot be blank"; }
		
		return false;
	}
	
	private function validateapt () {
		
		global $error;
		
		$apt = $this->apt;
		
		if (!isset($this->apt) || empty($this->apt)) {
			return true;
		}
		
		if (preg_match("/^[a-zA-Z0-9- ]+$/", $apt)) { // check if alphanum and -
		
			if (strlen($apt) <= 100) { // make sure less it is less than 100 chars
				
				$this->vapt = $this->apt;
				return true;
				
			} else { $error["apt"] = "Apt too long"; }			
		
		} else { $error["apt"] = "Invalid Characters"; }
		
		return false;
	}
	
	private function validatecity () {
		
		global $error;
		
		$city = $this->city;
		
		if (isset($this->city) && !empty($this->city)) { // check for value set
		
			if (preg_match("/^[a-zA-Z]+$/", $city)) { // check if alphanum and -
			
				if (strlen($city) <= 100) { // make sure less it is less than 100 chars
					
					$this->vcity = $this->city;
					return true;
					
				} else { $error["city"] = "City name too long";	}
				
			} else { $error["city"] = "Invalid Characters"; }				
		
		} else { $error["city"] = "City name cannot be blank"; }
		
		return false;	
	}
	
	private function validatestate () {
		
		global $error;
		
		$state = $this->state;
		
		if (isset($this->state) && !empty($this->state)) { // check for value set
			
			if (strlen($state) <= 100) { // make sure less it is less than 100 chars
				
				$this->vstate = $this->state;
				return true;
				
			} else { $error["state"] = "Province/State name too long"; }			
		
		} else { $error["state"] = "Province/State name cannot be blank"; }
		
		return false;	
	}
	
	private function validatepcode () {
		
		global $error;
		
		if (isset($this->pcode) && !empty($this->pcode)) { // check for value set
		
			$pcode = $this->pcode;
			
			if (preg_match("/^[a-zA-Z0-9]+$/", $pcode)) { // check if alphanum and -
			
				if (strlen($pcode) <= 10) { // make sure less it is less than 10 chars
					
					$this->vpcode = $this->pcode;
					return true;
					
				} else { $error["pcode"] = "Postal Code too long"; }			
			
			} else { $error["pcode"] = "Invalid Characters"; }			
		
		} else { $error["pcode"] = "Postal Code cannot be blank"; }
		
		return false;
	}
	
	private function validatecountry () {
		
		global $error;
		
		if (isset($this->country) && !empty($this->country)) { // check for value set
		
			$country = $this->country;
			
			if (preg_match("/^[a-zA-Z]+$/", $country)) { // check if alphanum and -
			
				if (strlen($country) == 3) { // make sure less it is less than 150 chars
					
					$this->vcountry = $this->country;
					return true;
					
				} else { $error["country"] = "Country name too long"; }			
			
			} else { $error["country"] = "Invalid Characters"; }			
		
		} else { $error["country"] = "Country name cannot be blank"; }
		
		return false;
	}
	
	private function validatephone () {
		
		global $error;
		
		if (isset($this->phone) && !empty($this->phone)) { // check for value set
		
			$phone = $this->phone;
			
			if (preg_match("/^[0-9\-\+]+$/", $phone)) { // check if alphanum and -
			
				if (strlen($phone) <= 50) { // make sure less it is less than 150 chars
					
					$this->vphone = $this->phone;
					return true;
					
				} else { $error["phone"] = "phone name too long"; }
				
			} else { $error["phone"] = "Invalid Characters"; }
			
		} else { $error["phone"] = "Phone cannot be blank"; }
		
		return false;
	}
	
	private function validateData () {
		
		global $securityCheck, $error; // Refer to class_security.php
		
		if ($securityCheck->checkForm($_POST["form"]) == true) { // Refer to class_security.php
		
			$validate = array();
			$validate[] = $this->validatefname();
			$validate[] = $this->validatelname();
			$validate[] = $this->validatestreet();
			$validate[] = $this->validateapt();
			$validate[] = $this->validatecity();
			$validate[] = $this->validatestate();
			$validate[] = $this->validatepcode();
			$validate[] = $this->validatecountry();
			$validate[] = $this->validatephone();
			
			if (!in_array(false, $validate, true)) {			
				return true;
			}
		} else {
			$error["form"] = "Security parameters failed. Please try again.";
		}			
		return false;	
	}
	
	public function processData () {
		
		global $error;
		
		if ($this->validateData()) {
			
			$namedata["fname"] 		= $this->vfname;
			$namedata["lname"]		= $this->vlname;
			
			$data["street"]		= $this->vstreet;
			$data["unit"]		= $this->vapt;
			$data["city"]		= $this->vcity;
			$data["province"]	= $this->vstate;
			$data["pcode"]		= $this->vpcode;
			$data["country"]	= $this->vcountry;
			$data["phone"]		= $this->vphone;

			// Begin
			$this->dbo->start();
			
			$qstatus["updatename"] = $this->dbo->update($this->dbn."USERINFO", $namedata, array("uid" => $this->getuid()));
			$qstatus["updateaddr"] = $this->dbo->update($this->dbn."ADDRESS", $data, array("residenceOf" => $this->getuid()));
			
			if (!in_array(false, array_values($qstatus), true) == true) {
				$this->dbo->end();
				return true;
			}
			
			$this->dbo->rollback();
			
		}
		
	}	
	
}


?>