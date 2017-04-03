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
	$orders = $seller->getOrders(FALSE);
}

// Process

// Include header template
include ("inc/header.inc.php");

?>

<div id = "maincontent">
	
	<div id = "submenu"> <?php dashnav($acc); ?> </div>
	
	<br/>
	
	<div id = "displaywindow">
	
	<?php
			
			if ($orders) {
				
				errorhandler();
				
			?>
				<div id = "divlabel">Completed orders</div>
				
				<table id="listings">
						<thead>
							<tr>
								<th>Invoice</th>
								<th>Placed</th>
								<th>Name</th>
								<th>Qty</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>			
			
			<?php
				
				foreach ($orders as $order) {
					
					$p = product::getProductData($order["contains"]);
					
					echo "<tr>";
					
					echo 	"<td id = \"invoice\" data-title=\"Invoice\"><a href = \"orderdetails.php?id=".$order["oid"]."\">".$order["invoiceid"]."</a></td>";
					echo 	"<td id = \"orderplaced\" data-title=\"Placed\">".toDate($order["orderDate"], true)."</td>";
					echo 	"<td id = \"pname\" data-title=\"Name\">".$p["pname"]."</td>";
					echo 	"<td id = \"items\" data-title=\"Items\">".$order["units"]."</td>";
					echo	"<td id = \"total\" data-title=\"Total\">$".$order["totalunitprice"]."</td>";

					echo "</tr>";
					
				}
			
			?>
			
					</tbody>
				</table>
			
			<?php
				
			} else {
				
				$_SESSION["caution"] = "You have not placed any orders.";
				errorhandler();
				
			}
			
			?>
	</div>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
