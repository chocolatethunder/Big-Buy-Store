<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

// Process
$orders = $user->getOrders();

// Include header template
include ("inc/header.inc.php");

?>

<div id = "maincontent">
	
	<div id = "submenu">
	
		<?php 
		
		dashnav($user->account_type());	
		
		?>
	
	</div>
	
	<br/>
	
	<div id = "displaywindow">
	
	<?php
			
			if ($orders) {
				
				errorhandler();
				
			?>
				<div id = "divlabel">View your orders</div>
				
				<table id="listings">
						<thead>
							<tr>
								<th>Invoice</th>
								<th>Placed</th>
								<th>Items</th>
								<th>Total</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>			
			
			<?php
				
				foreach ($orders as $order) {
					
					echo "<tr>";
					
					echo 	"<td id = \"invoice\" data-title=\"Invoice\"><a href = \"orderdetails.php?id=".$order["oid"]."\">".$order["invoiceid"]."</a></td>";
					
					echo 	"<td id = \"orderplaced\" data-title=\"Placed\">".toDate($order["orderDate"])."</td>";
					
					echo 	"<td id = \"items\" data-title=\"Items\">".$order["count"]."</td>";
					echo	"<td id = \"total\" data-title=\"Total\">$".$order["total"]."</td>";
					echo	"<td id = \"orderstatus\" data-title=\"Status\">".$user->getOrderStatus($order["oid"])."</td>";

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
