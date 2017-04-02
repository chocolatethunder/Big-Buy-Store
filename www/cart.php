<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

// Clear cart
if (isset($_POST["clear_cart"])) {
	$user->clearCart();
	empty($cart);
}

// Load cart
$cart = $user->getCart();
if (!$cart) {
	$cart = array();
}

// Delete
if (isset($_GET["delete"]) && !empty($_GET["delete"])) {	
	if (($key = array_search($_GET["delete"], $cart)) !== false) {
		unset($cart[$key]);
		$user->setCart($cart);
	}
	gotoPage("cart.php");
}

// Add to cart
if (isset($_GET["action"]) && $_GET["action"] == "add" && isset($_GET["id"]) && !empty($_GET["id"])) {
	$prod = prep($_GET["id"],"n");
	if (!in_array($prod, $cart, true)) {
		$cart[] = $prod;
		$user->setCart($cart);
	}
	gotoPage("cart.php");
}

// Process Checkout
if (isset($_POST["checkout"])) {
	if ($user->isAddressInfoComplete()){		
		// Process checkout
		$error = array();
		if ($user->cartCheckout($_POST)) {
			gotoPage("dashboard.php");
		}
	} else {
		$_SESSION["caution"] 	= "You need to complete your address information before you can checkout.";
		$_SESSION["checkout"] 	= true;
		gotoPage("address.php");
	}
}

// Include header template
include ("inc/header.inc.php");

?>


<div id = "maincontent">
	
	<div id = "cartwindow">
	
		<?php
		
		if (!empty($cart)) {
			
			?>
			
			<div id = "divlabel">Your cart</div>
			
			<div id = "qtylabel">QTY</div>
	
			<form action="cart.php" method="post" id = "cartform">

				<input type="hidden" name="token" id = "csrftoken" value="<?php echo generateToken(30); ?>" />				
			
				<?php
				
				foreach ($cart as $item) {

					$p = new product ($db, $item);
				
					if ($p->isOpen()) {
						
						echo "<div id =\"label\">".$p->getTitle()."<br/><span class = \"soldby\">Sold by: ".$p->getSeller()."</span></div><div id =\"fieldreq\"><input type=\"text\" name=\"".$p->getListingId()."\" style = \"".(isset($error[$p->getListingId()]) ? "border:2px solid red;" : null)."\" value = \"".(isset($_POST[$p->getListingId()]) ? cleanDisplay($_POST[$p->getListingId()]) : "1")."\" /><a href = \"?delete=".$p->getListingId()."\"><div id =\"remove\"></div></a>".(isset($error[$p->getListingId()]) ? "<p class = \"inputerror\">".$error[$p->getListingId()]."</p>" : null)."</div>";
						
					}
					
				}
				
				?>
			
				<input type="submit" id="submit" name="checkout" value="Checkout" />
				<input type="submit" id="cancel" name="clear_cart" value="Clear" />
			
			</form>
			
			
		<?php
			
		} else {
			
			$_SESSION["caution"] = "Your cart is empty!";
			errorhandler();
			
		}

		?>
	
	</div>
	
</div>

<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
