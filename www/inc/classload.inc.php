<?php
session_start();

// Import configuration file
include ("inc/config.inc.php");
include ("inc/functions.inc.php");

// Import classes
include ("inc/class_database.inc.php");
include ("inc/class_login.inc.php");
include ("inc/class_registration.inc.php");
include ("inc/class_formsecurity.inc.php");

$db 			= new pdodb(Conf::DBNAME);
$securityCheck 	= new security();

?>