<?php

class review {
	
	private $dbo;
	private $dbn;
	private $type;
	private $id;
	
	private $reviewData;
	private $averageRating;
	private $udata;
	private $pdata;
	
	function __construct($db, $type, $id) {
		$this->dbo = $db;
		$this->id = prep($id,"n");
		$this->type = $type;
		$this->dbn = "MasterDB.";
		
		$this->loadData();
	}
	
	public function submitReview() {
		
		global $_POST;
		
		$rating 	= prep($_POST["rating-input-1"],"n");
		$review 	= $_POST["review"];
		$reviewer 	= prep($_SESSION["uid"] ,"n");
		
		if ($this->reviewAllowed()) {
			
			switch ($_POST["type"]) {
			
				case "seller":
				if($this->dbo->insert($this->dbn."SELLERREVIEW", array("seller" => $this->id, "reviewer" => $reviewer, "review" => $review, "rating" => $rating))) {
					return true;
				}
				break;
				
				case "product":
				if ($this->dbo->insert($this->dbn."PRODUCTREVIEW", array("product" => $this->id, "reviewer" => $reviewer, "review" => $review, "rating" => $rating))) {
					return true;
				}
				break;
				
				default:
				break;
				
			}
			
		}	
		
		return false;
		
	}
	
	public function reviewAllowed() {
		
		switch ($this->type) {
			
			case "seller":
			$select = $this->dbo->select($this->dbn."SELLERREVIEW", array("seller" => $this->id, "reviewer" => $_SESSION["uid"]), NULL, "*", FALSE, FALSE);			
			if (count($select) == 0 && $this->id != $_SESSION["uid"]) {
				return true;
			}			
			break;
			
			case "product":
			$select = $this->dbo->select($this->dbn."PRODUCTREVIEW", array("product" => $this->id, "reviewer" => $_SESSION["uid"]), NULL, "*", FALSE, FALSE);
			if (count($select) == 0 && $this->getProdSellerId() != $_SESSION["uid"]) {
				return true;
			}			
			break;
			
			default:
			break;
			
		}
		
		return false;
	}
	
	private function loadData() {
		
		switch ($this->type) {
			
			case 'seller':			
			$this->reviewData = $this->dbo->joinselect($this->dbn."SELLERREVIEW", array(array("SELLERREVIEW" => "reviewer", "USERS" => "id")), array("seller" => $this->id), NULL, "id, uname, reviewer, seller, rating, review", FALSE, FALSE);
			
			$this->udata = $this->dbo->joinselect($this->dbn."USERS", 
						array(array("USERS" => "id", "USERINFO" => "uid")),
						array("id" => $this->id));
			break;
			
			case 'product':
			$this->reviewData = $this->dbo->joinselect($this->dbn."PRODUCTREVIEW", array(array("PRODUCTREVIEW" => "reviewer", "USERS" => "id")), array("product" => $this->id), NULL, "id, uname, reviewer, product, rating, review", FALSE, FALSE);
			
			$this->pdata = $this->dbo->joinselect($this->dbn."PRODUCT", 
						array(array("PRODUCT" => "department", "DEPARTMENT" => "deptid"), array("PRODUCT" => "pid", "LISTS" => "listedProd")),
						array("pid" => $this->id));
			break;
			
			default:
			return false;
			
		}
		
	}
	
	public function getAverageRating() {
		
		switch ($this->type) {
			
			case 'seller':
			$this->averageRating = $this->dbo->select($this->dbn."SELLERREVIEW", array("seller" => $this->id), NULL, "AVG(rating)");
			break;
			
			case 'product':
			$this->averageRating = $this->dbo->select($this->dbn."PRODUCTREVIEW", array("product" => $this->id), NULL, "AVG(rating)");
			break;
			
			default:
			return false;
			
		}
		
		return $this->averageRating["AVG(rating)"];
		
	}
	
	public function printStars() {
		
		$starCount = round($this->getAverageRating());
		$totalStars = 5;
		$str = "";
		
		while ($totalStars > 0) {
			
			if ($starCount > 0) {
				$str .= "<span class = \"star-lit\"></span>";
				$starCount--;
			} else {
				$str .= "<span class = \"star-dull\"></span>";
			}			
			$totalStars--;
			
		}		
		return $str;
	}
	
	public function printStarsFromNum ($stars) {
		
		$starCount = round($stars);
		$totalStars = 5;
		$str = "";
		
		while ($totalStars > 0) {
			
			if ($starCount > 0) {
				$str .= "<span class = \"star-lit\"></span>";
				$starCount--;
			} else {
				$str .= "<span class = \"star-dull\"></span>";
			}			
			$totalStars--;
			
		}		
		return $str;
		
	}
	
	// Seller gets 
	
	public function getSellerName() {		
		return $this->udata["uname"];		
	}
	
	public function getSellerReviews() {
		return $this->reviewData;
	}
	
	// Product gets
	
	public function getProductName() {		
		return $this->pdata["pname"];		
	}

	public function getProdSellerId() {		
		return $this->pdata["listedBy"];		
	}
	
	
	// SELECT * FROM `PRODREVIEW` LEFT JOIN `ORDERITEMS` ON `PRODREVIEW`.`product` = `ORDERITEMS`.`contains` JOIN `ORDERS` ON `ORDERITEMS`.`orderid` = `ORDERS`.`oid`;
	
	// SELECT * FROM `SELLERREVIEW` LEFT JOIN `ORDERITEMS` ON `SELLERREVIEW`.`seller` = `ORDERITEMS`.`seller` JOIN `ORDERS` ON `ORDERITEMS`.`orderid` = `ORDERS`.`oid` WHERE `SELLERREVIEW`.`seller` = :sel_seller;

	// IF reviewer == uid then verified owner
	
}

?>