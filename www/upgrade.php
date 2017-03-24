<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");


// Process the application here
if (isset($_POST["upgrade"])) {	
	$error = array();
	if (isset($_POST["terms"])) {		
		if ($securityCheck->checkForm($_POST["form"]) == true) {
			if ($user->activateUpgrade()) {
				// Reload user 
				$user = new user ($db);
			}
		}		
	} else { $error["terms"] = "You must agree to the terms and conditions."; }
}

// Do account checks
$acc 		= $user->account_type();
$continue 	= false;

if ($acc == 1) {	
	
	if ($user->isAddressInfoComplete()) {
		$continue = true;
	} else {
		$_SESSION["caution"] = "Please go to Change Address and complete your address information first.";
	}
	
	if ($user->userPendingUpgrade()) {
		$continue = false;
		$_SESSION["success"] = "Thank you for your application. We will get back to you as soon as possible.";
	}
	
} else {
	gotoPage("dashboard.php");
}


// Include header template
include ("inc/header.inc.php");

?>

<div id = "maincontent">
	
	<div id = "submenu">
	
		<?php dashnav($acc); ?>
	
	</div>
	
	<br/>
	
	<div id = "displaywindow">
		
		<?php 
		
		if ($continue) {
		
		?>
		
			<form action="upgrade.php" method="post" id="contentform">

				<?php echo (isset($status) ? "<div id = \"status\">".$status."</div>" : null); ?>
				<?php echo (isset($error["form"]) ? "<div id = \"error\">".$error["form"]."</div>" : null); ?>

				<input type="hidden" name="token" value="<?php echo generateToken(30); ?>" />
				<input type="hidden" name="form" value="upgraderequest" />

				<div id ="fieldreq" style = "margin-left:20px; width:730px;"><input type="checkbox" name="terms" value="agree" id="check" /> I agree with the <a href="legal.php">terms and conditions</a> and agree to submit my user profile to be considered as a seller. For this process I consent to any amount of background checks that may be performs to ensure the quality of this website and i's services. 
				<?php echo (isset($error["terms"]) ? "<p class = \"inputerror\" style = \"margin:20px 0 0 -3px;\">".$error["terms"]."</p>" : null); ?></p></div>
				
				<input type="submit" name="upgrade" value="Upgrade" id="submit" style = "margin-left:20px;"/>

			</form>
		
		<?php
			
		} else {
			
			errorhandler();
			
		}
		
		?>	
		
	</div>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
