<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

// Clear cart
if (isset($_GET["action"]) && $_GET["action"] == "clear") {
	$user->clearCart();
	empty($cart);
}

// Load cart
$cart = $user->getCart();
if (!$cart) {
	$cart = array();
}

// Add to cart
if (isset($_GET["action"]) && $_GET["action"] == "add" && isset($_GET["id"]) && !empty($_GET["id"])) {
	$prod = prep($_GET["id"],"n");
	if (!in_array($prod, $cart, true)) {
		$cart[] = $prod;
		$user->setCart($cart);
	}
}

// Include header template
include ("inc/header.inc.php");

?>


<div id = "maincontent">
	
	<div id = "productwindow">
	
		<?php
		
		if (!empty($cart)) {
			
			echo "<div id = \"divlabel\">Your cart</div>";
			
			foreach ($cart as $item) {

				$p = new product ($db, $item);
			
				if ($p->isOpen()) {
					echo $p->getTitle()."<br/>";
				}
				
			}	
			
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
