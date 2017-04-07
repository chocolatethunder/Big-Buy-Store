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
								array("USERS" => "id", "ADDRESS" => "residenceOf")),
						array("id" => $this->uid));

	}
	
	private function userDataQuery($col) {		
		if ($col != "pass" && array_key_exists($col, $this->udata)) {
			return $this->udata[$col];
		}
		return false;
	}
	
	public function account_type() {
		if (login::loginCheck() == true) {		
			return $this->udata["lvl"];
		} else {
			login::logout();
		}
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
	
	// Commerce section. This should've been a separate class
	
	public function getCart() {		
		$data = $this->dbo->select($this->dbn."SHOPPINGCART", array("belongsto" => $this->getuid()));
		return unserialize($data["items"]);		
	}
	
	public function setCart($data) {
		$cdata = serialize($data);
		$this->dbo->update($this->dbn."SHOPPINGCART", array("items" => $cdata), array("belongsto" => $this->getuid()));
	}
	
	public function clearCart() {
		$this->dbo->update($this->dbn."SHOPPINGCART", array("items" => NULL), array("belongsto" => $this->getuid()));
	}
	
	public function getNumOfItemsInCart() {
		$cart = $this->getCart();
		if (count($cart) > 0 && !empty($cart)) {
			return count($cart);
		}
		return null;
	}
	
	public function cartCheckout($post){
		
		global $error;
		
		// load user cart into array
		$buyItems = $this->getCart();
		// check if each product has qty available
		foreach ($buyItems as $item) {
			// load new product
			$i = new Product($this->dbo, $item);
			// check if its listing is still available
			if ($i->isOpen()) {
				// check quantities
				if ($post[$item] > $i->getQuantity()) {
					$error[$item] = "Not enough product quantities available.";
				}
			}
			
		}
		
		if (!empty($error)) {
			return false;
		}		
		
		$this->dbo->start();
		
		// insert a new row in the orders table
		$querysuccess["orderinsert"] = $this->dbo->insert($this->dbn."ORDERS", array("shippedTo" => $this->getuid(), "invoiceid" => randomNumber(6), "orderDate" => date("Y-m-d H:i:s"), "ordstatus" => "Placed"));
		
		// get the last insert id
		$orderid = $this->dbo->lastinsertid;
		
		
		foreach ($buyItems as $item) {
			$i = new Product($this->dbo, $item);
			if ($i->isOpen()) {
				// insert rows for each item and their price into the orderitems table
				$querysuccess["orderitem".$item] = $this->dbo->insert($this->dbn."ORDERITEMS", array("orderid" => $orderid, "contains" => $i->getProdId(), "unitprice" => $i->getPrice(), "units" => prep($post[$item], "n"), "totalunitprice" => ($i->getPrice() * prep($post[$item], "n")), "seller" => $i->getSellerId()));
				// subtract the bought amount from the sellers inventory
				$querysuccess["subtractitem".$item] = $i->subtractQty(prep($post[$item], "n"));
			}
		}		
		
		if (!in_array(false, array_values($querysuccess), true) == true) {				
			$this->dbo->end();
			// clear the user cart
			$this->clearCart();
			return true;
		}
		
		$this->dbo->rollback();		
		
		return false;
		
	}
	
	public function getOrders() {
		
		$orderdata = $this->dbo->select($this->dbn."ORDERS", array("shippedTo" => $this->getuid()), NULL, "*", FALSE, FALSE);
		
		if (!empty($orderdata)) {
			$i = 0;		
			foreach ($orderdata as $ord) {
				$orderTotal = $this->dbo->select($this->dbn."ORDERITEMS", array("orderid" => $ord["oid"]), NULL, "SUM(totalunitprice), SUM(units)");
				$orderdata[$i]["total"] = $orderTotal["SUM(totalunitprice)"];
				$orderdata[$i]["count"] = $orderTotal["SUM(units)"];
				$i++;
			}			
			return $orderdata;
		}
		
		return null;		
	}
	
	public function getOrderData($orderid) {
		
		$oid = prep($orderid,"n");
		
		if ($this->isUsersOrder($oid)) {
			$orderdata = $this->dbo->joinselect($this->dbn."ORDERITEMS", array( array("ORDERITEMS" => "orderid", "ORDERS" => "oid")), array("orderid" => $oid), NULL, "*", FALSE, FALSE);
			return $orderdata;
		}
		return false;
	}
	
	public function isUsersOrder($orderid) {
		
		$oid = prep($orderid,"n");
		$asUser = $this->dbo->select($this->dbn."ORDERS", array("shippedTo" => $this->getuid(), "oid" => $oid), NULL, "*", FALSE, FALSE);
		$asSeller = $this->dbo->select($this->dbn."ORDERITEMS", array("orderid" => $oid, "seller" => $this->getuid()), NULL, "*", FALSE, FALSE);
		
		if (!empty($asUser) || !empty($asSeller)) {
			return true;
		}
		return false;
	}
	
	public function getOrderStatus($orderid) {
		
		$orderData = $this->getOrderData($orderid);
		$shipped = array();
		
		foreach ($orderData as $item) {
			$shipped[] = ($item["shipped"] == "Y" ? true : false);
		}
		
		if (!in_array(false, $shipped, true)) {
			return "Shipped";
		}
		
		return "Placed";
		
	}
	
}

?>