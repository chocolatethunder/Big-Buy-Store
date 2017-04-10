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
		$this->loadData();
	}
	
	public function loadData() {
		$this->listingData = $this->dbo->joinselect($this->dbn."LISTS", array(array("LISTS" => "listedProd", "PRODUCT" => "pid"), array("PRODUCT" => "department", "DEPARTMENT" => "deptid")), array("adId" => $this->listId));
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
	
	public function getSellerId() {
		return $this->listingData["listedBy"];
	}
	
	public function getListingDate() {
		return $this->listingData["addedon"];
	}
	
	public function getQuantity() {
		return $this->listingData["units"];
	}
	
	public function getDescription() {
		return $this->listingData["description"];
	}
	
	public function getProdId() {
		return $this->listingData["listedProd"];
	}

	public function getPrice() {
		return $this->listingData["price"];
	}
	
	public function getListingId() {
		return $this->listId;
	}
	
	public function getDeptId() {
		return $this->listingData["department"];
	}
	
	public function subtractQty($qty=1) {
		if ($this->dbo->update($this->dbn."LISTS", array("units" => $this->getQuantity()-$qty), array("adId" => $this->getListingId()))) {
			return true;
		}
		return false;
	}
	
	public static function getProductData($pid) {
		global $db;
		$product = $db->select("MasterDB.PRODUCT", array("pid" => prep($pid, "n")));
		return $product;
	}
	
}

?>