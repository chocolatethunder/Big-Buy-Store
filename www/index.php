<?php

// Import configuration file
include ("inc/config.inc.php");

// Include header template
include ("inc/header.inc.php");


echo "Running Diagnostics... <br/>";

echo "PHP Installation is ok. Successful php echo().<br/>";

$con = mysqli_connect(Conf::DBHOST,Conf::DBUSER,Conf::DBPASS,Conf::DBNAME);

// Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
	echo "Amazon RDS Connection Established.";
}



// Include footer template
include ("inc/footer.inc.php");
	
?>
