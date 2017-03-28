<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

// Access check
$acc = $user->account_type();

if ($acc < 2) {
	gotoPage("dashboard.php");
} else {
	include ("inc/class_seller.inc.php");
	$seller = new seller($db);
	$listings = $seller->getListings();
}

// Process

// Include header template
include ("inc/header.inc.php");

?>

<div id = "maincontent">
	
	<div id = "submenu"> <?php dashnav($acc); ?> </div>
	
	<br/>
	
	<div id = "displaywindow">
		
		<?php errorhandler(); ?>

		<?php
			
			if ($listings) {
				
			?>
			<br/>
			
			<div id = "divlabel">Your Marketplace Listings</div>
			
			<table id="listings">
					<thead>
						<tr>
							<th></th>
							<th>Name</th>
							<th>Price</th>
							<th>Units</th>
							<th>Listed On</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
			
			
			<?php
				
				foreach ($listings as $item) {
					
					echo "<tr>";
					echo	"<td id = \"pid\" data-title=\"\"><a href = \"addproduct.php?action=edit&id=".$item["adId"]."\"><div id =\"edit\"></div></a></td>";
					echo 	"<td id = \"pname\" data-title=\"Name\">".$item["pname"]."</td>";
					echo	"<td id = \"price\" data-title=\"Price\">$".$item["price"]."</td>";
					echo	"<td id = \"units\" data-title=\"Price\">".$item["units"]."</td>";
					echo 	"<td id = \"datetime\" data-title=\"Listed On\">".toDate($item["addedon"], true)."</td>";
					echo 	"<td id = \"delete\" data-title=\"\"><a href = \"addproduct.php?action=delete&id=".$item["adId"]."
					\"><div id =\"remove\"></div></a></td>";
					echo "</tr>";
					
				}
			
			?>
			
					</tbody>
				</table>
			
			<?php
				
			} else {
				
				$_SESSION["caution"] = "You have not listed any products to sell.";
				errorhandler();
				
			}

		?>		
	
	</div>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
