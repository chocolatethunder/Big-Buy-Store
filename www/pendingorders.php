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
}

// Process

// Include header template
include ("inc/header.inc.php");

?>

<div id = "maincontent">
	
	<div id = "submenu"> <?php dashnav($acc); ?> </div>
	
	<br/>
	
	<div id = "displaywindow">
	
		
	
	</div>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
