<?php

// This check when a certain form is submitted that it is submitted with the right form variables
// and that they are in the right order. Each form is unique. 

class security {
	
	public function getDefinition($form) {
		
		$allowed 			= array();
		$allowed["token"] 	= "";
		$allowed["form"] 	= "";
		
		switch ($form) {
			
			// FRONTDESK //
			
			// PUBLIC PAGES //
			
			// located on signup.php
			case "signup":
			$allowed["uname"] = "";
			$allowed["pass1"] = "";
			$allowed["pass2"] = "";
			$allowed["email"] = "";
			$allowed["terms"] = "";
			$allowed["register"] = "";
			break;
			
			
			// located on login.php
			case "login":
			$allowed["uname"] = "";
			$allowed["pass"] = "";
			$allowed["login"] = "";
			break;
			
			// PRIVATE PAGES //
			// located on verify.php
			case "update_address":
			$allowed["fname"] = "";
			$allowed["lname"] = "";
			$allowed["street"] = "";
			$allowed["apt"] = "";
			$allowed["pcode"] = "";
			$allowed["city"] = "";
			$allowed["state"] = "";
			$allowed["country"] = "";
			$allowed["phone"] = "";
			$allowed["submit_address"] = "";
			break;
			
			
			// default is always false
			default:
			return false;
			break;			

		}
		
		return $allowed;
		
	}
	
	public function checkForm ($form) {
		
		global $error, $user;
		
		if (!is_array($form)) {
			
			// Simple check for XSS attacks 
			
			$publicpages = array("signup", "login");
			
			$allowed = $this->getDefinition($form);	

			if ($allowed != false) {
				
				$sent 		= array_keys($_POST);
				$allow 		= array_keys($allowed);				
				
				/*
				// debug
				print_r($allow);
				echo "<br/>";
				print_r($sent);
				*/
				
				/*
				//debug
				echo $_POST["token"];
				echo "<br/>";
				echo $_SESSION["token"];
				*/		
				
				if ($allow === $sent && !empty($_POST["token"]) && $_POST["token"] == $_SESSION["token"]) {

					return true;
				
				} else {
					
					$error["form"] = "Error. Please try again.";
					
				}
				
			}
			
		}
		
		return false;
	}

	
}

?>