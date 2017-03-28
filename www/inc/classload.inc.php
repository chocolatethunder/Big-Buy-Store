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
include ("inc/class_product.inc.php");

$db 			= new pdodb(Conf::DBNAME);
$securityCheck 	= new security();

if (login::loginCheck() == true) {
	include ("inc/class_user.inc.php");
	include ("inc/class_address.inc.php");
	
	$user = new user($db);	
}


// Allowed public places

$publicpages = array ("login","signup","search","home","product");

// Auto logout if it is not a private page and user is not logged in.

if (in_array($file, $publicpages, true) == false) {	

	if (login::loginCheck() == false) {
		login::logout();	
	}

}

?>