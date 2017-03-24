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
		
		dashnav($user->account_type());	
		
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
