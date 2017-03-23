<?php

// Import configuration file
include ("inc/config.inc.php");
include ("inc/class_database.inc.php");

$db = new pdodb (Conf::DBNAME);

?>
<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The Big Buy Store</title>

  <link rel="stylesheet" type="text/css" href="css/style.css">
  
  <script src="script.js"></script>
  
</head>

<body>