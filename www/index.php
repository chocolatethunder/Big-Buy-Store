<?php

// Load classes
$file = "home";
include ("inc/classload.inc.php");

// Get all the products
$listings = $db->joinselect("MasterDB."."LISTS", array(array("LISTS" => "listedProd", "PRODUCT" => "pid"), array("PRODUCT" => "department", "DEPARTMENT" => "deptid")), NULL, NULL, "*", FALSE, FALSE);

// Include header template
include ("inc/header.inc.php");

?>


<div id = "maincontent">
	
	<div id = "resultswindow">	
		
		<?php 
			
			foreach ($listings as $item) {
				
				echo "<a id = \"productlink\" href = \"product.php?id=".$item["adId"]."\"><div id = \"tile\">";
				
					echo "<div id = \"prodimage\"></div>";
					echo "<div id = \"prodtitle\"><p>".truncatestr($item["pname"], 18)."</p></div>";
					echo "<div id = \"proddesp\"><p>".truncatestr($item["description"], 78)."</p></div>";
					echo "<div id = \"prodprice\"><p>$".$item["price"]."</p></div>";
				
				echo "</div></a>";
				
			}			

		?>
	
	</div>
	
</div>

<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
