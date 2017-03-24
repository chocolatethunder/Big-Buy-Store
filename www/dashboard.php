<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

// Process
print_r($db->joinselect(Conf::DBNAME.".USERS", 
				array(	array("USERS" => "id", "USERINFO" => "uid"), 
						array("USERINFO" => "address", "ADDRESS" => "addrid")),
				array("id" => 1)));


// Include header template
include ("inc/header.inc.php");

?>

<div id = "maincontent">
	
	<div id = "submenu">

		<div id = "class_user">
			
			<ul>
			
				<li class = "home"><a href = "#">Orders</a></li>
				<li class = "home"><a href = "#">Address</a></li>
				<li class = "home"><a href = "#">Wishlist</a></li>
				<li class = "home"><a href = "#">Change Password</a></li>
			
			</ul>
		
		</div>
		
		<div id = "class_seller">
			
			<ul>
			
				<li class = "home"><a href = "#">Pending Orders</a></li>
				<li class = "home"><a href = "#">Listings</a></li>
				<li class = "home"><a href = "#">Add Product</a></li>
			
			</ul>
		
		</div>
		
		<div id = "class_mods">
			
			<ul>
			
				<li class = "home"><a href = "#">Approve sellers</a></li>
				<li class = "home"><a href = "#">Approve moderators</a></li>
			
			</ul>
		
		</div>
	
	</div>
	
	<br/>
	
	<div id = "displaywindow">
		<?php echo "orders here"; ?>
	</div>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
