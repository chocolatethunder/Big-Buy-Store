<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

if (isset($_GET["id"]) && !empty($_GET["id"])) {
	$prod = new product($db, $_GET["id"]);	
	if (!$prod->isOpen()) {
		gotoPage("index.php");
	}	
} else {
	gotoPage("index.php");
}

// Include header template
include ("inc/header.inc.php");

?>


<div id = "maincontent">
	
	<div id = "productwindow">	
		
		<?php 
		
		echo "<div id = \"listImg\"><img src = \"img/default.png\" /></div>";
		
		if ($prod->getQuantity() > 0) {
			echo "<a href =\"cart.php?action=add&id=".$prod->getListingId()."\"><div id = \"addToCart\">Add to cart</div></a>";
		} else {
			echo "<div id = \"soldOut\">Sold out</div>";
		}
		
		
		echo "<div id = \"listTitle\"><p>".$prod->getTitle()."</p></div>";
		
		
		echo "<div id = \"listPrice\"><p>$".$prod->getPrice()."</p></div>";
		echo "<div id = \"listDetail\"><span class = \"prefix\">Sold by: </span><span class = \"suffix\"> ".$prod->getSeller()."</span></div>";
		echo "<div id = \"listDetail\"><span class = \"prefix\">Listed on: </span><span class = \"suffix\"> ".toDate($prod->getlistingDate(), true)."</span></div>";
		echo "<div id = \"listDetail\"><span class = \"prefix\">Units Left: </span><span class = \"suffix\"> ".($prod->getQuantity() <= 0 ? "Sold Out" : $prod->getQuantity())."</span></div>";
		
		
		echo "<div id = \"listDesp\"><p>".$prod->getDescription()."</p></div>";
		
		?>
	
	</div>
	
	<br/>
	
	<div id = "productwindow">	
		
		<div id = "divlabel">Reviews</div>
	
	</div>
	
	<br/>
	
	<div id = "productwindow">	
		
		<div id = "divlabel">Products that may interest you</div>
	
	</div>
	
</div>

<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
