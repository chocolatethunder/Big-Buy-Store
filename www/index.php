<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The Big Buy Store</title>

  <link rel="stylesheet" type="text/css" href="css/style.css">
  
  <script src="script.js"></script>
  
</head>

<body>
<?php

	include ("inc/config.inc.php");
	
	echo "Running Diagnostics... <br/>";
	
	echo "PHP Installation is ok. Successful php echo().<br/>";
	
	$con = mysqli_connect(Conf::DBHOST,Conf::DBUSER,Conf::DBPASS,Conf::DBNAME);

	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	} else {
		echo "Amazon RDS Connection Established.";
	}

	
?>
</body>
</html>