<?php

class product {
	
	private $dbo;
	private $dbn;
	private $listId;
	
	private $listData;
	
	function __construct($db, $id) {
		$this->dbo = $db;
		$this->listId = prep($id,"n");
		$this->dbn = "MasterDB.";
	}
	
	public function isOpen() {
		$this->listingData = $this->dbo->joinselect($this->dbn."LISTS", array(array("LISTS" => "listedProd", "PRODUCT" => "pid"), array("PRODUCT" => "department", "DEPARTMENT" => "deptid")), array("adId" => $this->listId));
		if (!empty($this->listingData)) {
			return true;
		}
		return false;
	}
	
	public function getTitle() {
		return $this->listingData["pname"];
	}
	
	public function getSeller() {
		$seller = $this->dbo->select($this->dbn."USERS", array("id" => $this->listingData["listedBy"]), NULL, array("uname"));
		return $seller["uname"];
	}
	
	public function getListingDate() {
		return $this->listingData["addedon"];
	}
	
	public function getQuantity() {
		return $this->listingData["units"];
	}
	
}

?>