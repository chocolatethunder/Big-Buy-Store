<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

// Process

// Include header template
include ("inc/header.inc.php");

?>

<div id = "maincontent">
	
	<div id = "submenu">
	
		<?php 
		
		$acc = $user->account_type();
		
		// Buyer level
		if ($acc >= 1) {
			?>
			<div id = "class_user">
				
				<ul>
				
					<li class = "home"><a href = "dashboard.php">Orders</a></li>
					<li class = "home"><a href = "address.php">Address</a></li>
					<li class = "home"><a href = "#">Wishlist</a></li>
					<li class = "home"><a href = "#">Change Password</a></li>
				
				</ul>
			
			</div>
			<?php
		}
		
		// Seller level
		if ($acc >= 2) {
			?>
			<div id = "class_seller">
			
				<ul>
				
					<li class = "home"><a href = "#">Pending Orders</a></li>
					<li class = "home"><a href = "#">Listings</a></li>
					<li class = "home"><a href = "#">Add Product</a></li>
				
				</ul>
			
			</div>
			<?php
		}
		
		// Admin Level
		if ($acc >= 3) {
			
			?>
			<div id = "class_mods">
			
				<ul>
				
					<li class = "home"><a href = "#">Approve sellers</a></li>
					<li class = "home"><a href = "#">Approve moderators</a></li>
				
				</ul>
			
			</div>
			<?php
		}
		
		?>		
	
	</div>
	
	<br/>
	
	<div id = "displaywindow">
		<div id = "divlabel">View your orders</div>
	</div>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
