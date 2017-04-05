<?php

// Load classes
$file = "home";
include ("inc/classload.inc.php");

$where = NULL;
$listings = array();

// Get all the products
if (isset($_POST["gosearch"])) {	
	if (preg_match("/^[a-zA-Z0-9 ]+$/", $_POST["search"])) {
		$sql = "SELECT * FROM `MasterDB`.`LISTS` JOIN `PRODUCT` ON `LISTS`.`listedProd` = `PRODUCT`.`pid` JOIN `DEPARTMENT` ON `PRODUCT`.`department` = `DEPARTMENT`.`deptid` WHERE `description` LIKE :sel_description OR `pname` LIKE :sel_pname";
		$bind = array();
		$bind[":sel_description"] = "%".$_POST["search"]."%";
		$bind[":sel_pname"] = "%".$_POST["search"]."%";
		$listings[] = $db->runquery($sql,$bind);
	}
} else {
	$listings = $db->joinselect("MasterDB."."LISTS", array(array("LISTS" => "listedProd", "PRODUCT" => "pid"), array("PRODUCT" => "department", "DEPARTMENT" => "deptid")), NULL, NULL, "*", FALSE, FALSE);
}


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
