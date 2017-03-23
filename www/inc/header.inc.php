<?php

// Import configuration file
include ("inc/config.inc.php");
include ("inc/functions.inc.php");
// Import classes
include ("inc/class_database.inc.php");

$db = new pdodb(Conf::DBNAME);

?>
<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The Big Buy Store</title>

  <link rel="stylesheet" type="text/css" href="css/fonts/fonts.css">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  
  <script src="script.js"></script>
  
</head>

<body>

<div id = "wrapper">

	<div id = "header">The Big Buy Store</div>
	
	<div id = "navigation">
	
		<ul id = "menu">
		
			<li><a href = "index.php">Home</a></li>
			<li><a href = "login.php">Login</a></li>
			<li><a href = "index.php">Signup</a></li>
		
		</ul>
	
	</div>