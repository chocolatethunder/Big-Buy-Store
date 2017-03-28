<?php

class seller extends user {	
	
	private $dbo;
	private $dbn;
	
	public $deptid;
	public $pname;
	public $desp;
	public $qty;
	public $price;
	public $listingID;
	
	private $vdeptid;
	private $vpname;
	private $vdesp;
	private $vqty;
	private $vprice;
	
	private $dtime;
	
	function __construct($db) {
		
		$this->dbo = $db;
		$this->dbn = Conf::DBNAME.".";
		parent::__construct($this->dbo);
		$this->dtime = $date = date("Y-m-d H:i:s");

	}
	
	public function getDepartments() {		
		$result = $this->dbo->select($this->dbn."DEPARTMENT");
		return $result;		
	}
	
	public function validateDepartment() {		
		global $error;		
		if (isset($this->deptid) && !empty($this->deptid)) { // check for value set		
			$deptid = $this->deptid;			
			if (preg_match("/^[0-9]+$/", $deptid)) { // check if alphanum and -			
				if (strlen($deptid) <= 5) { // make sure less it is less than 150 chars					
					$this->vdeptid = $this->deptid;
					return true;					
				} else { $error["dept"] = "Department id too big"; }				
			} else { $error["dept"] = "Only numbers are allowed. No decimals."; }			
		} else { $error["dept"] = "Department id cannot be blank"; }		
		return false;
	}
	
	public function validateProductName() {
		global $error;		
		if (isset($this->pname) && !empty($this->pname)) { // check for value set		
			$pname = $this->pname;			
			if (preg_match("/^[a-zA-Z0-9 ]+$/", $pname)) { // check if alphanum and -			
				if (strlen($pname) <= 150) { // make sure less it is less than 150 chars					
					$this->vpname = $this->pname;
					return true;					
				} else { $error["pname"] = "Product name is too big"; }				
			} else { $error["pname"] = "Only letters, numbers, and spaces allowed"; }			
		} else { $error["pname"] = "Product name cannot be blank"; }		
		return false;	
	}
	
	public function validateDescription() {
		global $error;		
		if (isset($this->desp) && !empty($this->desp)) { // check for value set		
			$desp = $this->desp;			
			if (preg_match("/^[a-zA-Z0-9'-\. ]+$/", $desp)) { // check if alphanum and -			
				if (strlen($desp) <= 250) { // make sure less it is less than 150 chars					
					$this->vdesp = $this->desp;
					return true;					
				} else { $error["desp"] = "Product description is too big"; }				
			} else { $error["desp"] = "Only letters, numbers, spaces, ', -, and . allowed"; }			
		} else { $error["desp"] = "Product description cannot be blank"; }		
		return false;		
	}
	
	public function validateQuantity() {		
		global $error;		
		if (isset($this->qty) && !empty($this->qty) && $this->qty > 0) { // check for value set		
			$qty = $this->qty;			
			if (preg_match("/^[0-9]+$/", $qty)) { // check if alphanum and -			
				if (strlen($qty) <= 5) { // make sure less it is less than 150 chars					
					$this->vqty = $this->qty;
					return true;					
				} else { $error["qty"] = "Quantity is too big"; }				
			} else { $error["qty"] = "Only numbers are allowed. No decimals."; }			
		} else { $error["qty"] = "Quantity must be greater than 0"; }		
		return false;
	}
	
	public function validatePrice() {
		global $error;		
		if (isset($this->price) && !empty($this->price) && $this->qty > 0) { // check for value set		
			$price = $this->price;
			if (preg_match("/^[0-9]+(?:\.[0-9]{2}){0,1}$/", $price)) {			
				$this->vprice = $this->price;
				return true;			
			} else { $error["price"] = "Incorrect price"; }	
		} else { $error["price"] = "Price must be greater than 0"; }	
		return false;
	}
	
	private function validateData () {
		
		global $securityCheck, $error; // Refer to class_security.php
		
		if ($securityCheck->checkForm($_POST["form"]) == true) { // Refer to class_security.php
		
			$validate = array();
			$validate[] = $this->validateDepartment();
			$validate[] = $this->validateProductName();
			$validate[] = $this->validateDescription();
			$validate[] = $this->validateQuantity();
			$validate[] = $this->validatePrice();
			
			if (!in_array(false, $validate, true)) {			
				return true;
			}
			
		} else {
			$error["form"] = "Security parameters failed. Please try again.";
		}			
		return false;	
	}
	
	public function addProduct() {
		
		if ($this->validateData()) {
			
			$this->dbo->start();
			
			// First query
			$newProd_data = array("pname" => $this->vpname, "department" => $this->vdeptid); 
			$querysuccess["prod_data"] = $this->dbo->insert($this->dbn."PRODUCT", $newProd_data);
			
			$newProdId = $this->dbo->lastinsertid;
			// Second query
			$listing_data = array("listedBy" => $this->getuid(), "listedProd" => $newProdId, "price" => $this->vprice, "description" => $this->vdesp, "units" => $this->vqty, "addedon" => $this->dtime);
			$querysuccess["listing_data"] = $this->dbo->insert($this->dbn."LISTS",$listing_data );
			
			if (!in_array(false, array_values($querysuccess), true) == true) {				
				$this->dbo->end();
				return true;
			}
			
			$this->dbo->rollback();	

			// Handle errors
			$error["form"] = "We failed to make add your new listing. Please try again.";
			
		}
		return false;
	}
	
	public function updateListing() {
		
		if ($this->validListingOwnership()) {
			
			if ($this->validateData()) {
			
				$lID = prep($this->listingID, "n");
				$pID = $this->dbo->select($this->dbn."LISTS", array("adId" => $lID), NULL, array("listedProd"));

				$this->dbo->start();
				
				// First query
				$updProd_data = array("pname" => $this->vpname, "department" => $this->vdeptid); 
				$querysuccess["prod_data"] = $this->dbo->update($this->dbn."PRODUCT", $updProd_data, array("pid" => $pID["listedProd"]));

				// Second query
				$listing_data = array("price" => $this->vprice, "description" => $this->vdesp, "units" => $this->vqty, "addedon" => $this->dtime);
				$querysuccess["listing_data"] = $this->dbo->update($this->dbn."LISTS", $listing_data, array("adId" => $lID));				
				
				if (!in_array(false, array_values($querysuccess), true) == true) {				
					$this->dbo->end();
					return true;
				}
				
				$this->dbo->rollback();	

				// Handle errors
				$error["form"] = "We failed to update your new listing. Please try again.";
				
			}
			
		}
		return false;
	}
	
	public function deleteListing() {		
		if ($this->validListingOwnership()) {
			$lID = prep($this->listingID, "n");		
			if($this->dbo->delete($this->dbn."LISTS", array("adId" => $lID))) {
				return true;
			}			
		}
		return false;
	} 
	
	
	private function validListingOwnership() {		
		$lID = prep($this->listingID, "n");
		$data = $this->dbo->select($this->dbn."LISTS", array("adID" => $lID, "listedBy" => $this->getuid()), NULL, "*", FALSE, FALSE);
		if (count($data) == 1) {
			return true;
		}
		return false;		
	}
	
	public function getListingData() {
		$lID = prep($this->listingID, "n");
		if ($this->validListingOwnership()) {
			$data = $this->dbo->joinselect($this->dbn."LISTS", 
						array(array("LISTS" => "listedProd", "PRODUCT" => "pid"),
								array("PRODUCT" => "department", "DEPARTMENT" => "deptid")),
						array("listedBy" => $this->getuid(), "adId" => $lID));
			if ($data) {
				return $data;
			}
		}		
		return false;
	}
	
	public function getListings() {		
		$data = $this->dbo->joinselect($this->dbn."LISTS", 
						array(array("LISTS" => "listedProd", "PRODUCT" => "pid"),
								array("PRODUCT" => "department", "DEPARTMENT" => "deptid")),
						array("listedBy" => $this->getuid()), NULL, "*", FALSE, FALSE);
		if (count($data) > 0) {
			return $data;
		}
		return null;
	}
	
}