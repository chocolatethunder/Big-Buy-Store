<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

// Process
if (isset($_GET["id"]) && !empty($_GET["id"])) {
	$orderid = prep($_GET["id"],"n");
	$order = $user->getOrderData($orderid);
	if (!$order) {
		gotoPage("dashboard.php");
	}
} else {
	gotoPage("dashboard.php");
}


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
			
			if ($order) {
				
				errorhandler();
				
			?>
				<div id = "divlabel"><?php echo "Invoice #: ".$order[0]["invoiceid"]."<br/>"; echo "Placed on: ".toDate($order[0]["orderDate"]); ?></div>
				
				<table id="listings">
						<thead>
							<tr>
								<th>Product</th>
								<th>Price</th>
								<th>Qty</th>
								<th>Total Price</th>
								<th>Shipped</th>
							</tr>
						</thead>
						<tbody>			
			
			<?php
			
				$subTotal = 0;				
				$shipped = array();
				
				foreach ($order as $item) {
					
					$p = product::getProductData($item["contains"]);
					
					$subTotal += $item["totalunitprice"];
					$shipped[] = ($item["shipped"] == "Y" ? true : false);
					
					echo "<tr>";
					
					echo 	"<td id = \"pname\" data-title=\"Product\">".$p["pname"]."</a></td>";
					echo 	"<td id = \"price\" data-title=\"Price\">".$item["unitprice"]."</td>";
					echo 	"<td id = \"items\" data-title=\"Qty\">".$item["units"]."</td>";
					echo	"<td id = \"price\" data-title=\"Total Price\">$".$item["totalunitprice"]."</td>";
					echo	"<td id = \"orderstatus\" data-title=\"Shipped\">".$item["shipped"]."</td>";

					echo "</tr>";
					
				}
				
				echo "<tr>";
				
				echo "<td id =\"subtotal\" colspan = 3>Sub Total</td>";
				echo "<td id =\"subtotalval\" colspan = 2>$".$subTotal."</td>";
				
				echo "</tr>";
				
			
			?>
			
					</tbody>
				</table>
				
				<br/><br/>
				
				<?php 
				
				if (in_array(false, $shipped, true)) {
					echo "<div id = \"divlabel\">Will ship to</div>";
				} else {
					echo "<div id = \"divlabel\">Shipped to</div>";					
				}
				
				?>				
				
				<br/>
				
				<div id = "regulartext">
		
					<?php
					
					echo $user->getUserFullNameString();
					echo $user->getUserAddressString();
					
					?>
					
				</div>
			
			<?php
				
			} else {
				
				$_SESSION["caution"] = "No such order exists.";
				errorhandler();
				
			}
			
			?>
	</div>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
