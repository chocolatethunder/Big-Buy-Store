<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

if (login::loginCheck() == true) {
	login::logout();
}

// Process registration
if (isset($_POST["register"])) {	
	
	$error = array();	

	$newClient = new registration($db, $_POST["uname"], $_POST["pass1"], $_POST["pass2"], $_POST["email"], (isset($_POST['terms'])? $_POST['terms'] : null ));
	
	if ($newClient->processData() == true) {
		$_SESSION["success"] = "Awesome! Check your email to complete signup";
		gotoPage("login.php");
	}
	
}

// Include header template
include ("inc/header.inc.php");

?>

<div id = "content">	
		
	<div id = "title">
	
		<p class="microtitle">BIGBUY Store</p>
		<p class="title">SIGNUP</p>
		
	</div>
		
	<form action="signup.php" method="post" id="nonauthform">

		<?php echo (isset($status) ? "<div id = \"status\">".$status."</div>" : null); ?>
		<?php echo (isset($error["form"]) ? "<div id = \"error\">".$error["form"]."</div>" : null); ?>

		<input type="hidden" name="token" value="<?php echo generateToken(30); ?>" />
		<input type="hidden" name="form" value="signup" />

		<p class = "label">Username</p>
		<input type="text" name="uname" 
		style = "<?php echo (isset($error["uname"]) ? "border:2px solid red;" : null); ?>" 
		value = "<?php echo (isset($_POST["uname"]) ? cleanDisplay($_POST["uname"]) : null); ?>" />
		<?php echo (isset($error["uname"]) ? "<p class = \"inputerror\">".$error["uname"]."</p>" : null); ?>
		
		<p class = "label">Password</p>
		<input type="password" name="pass1" 
		style = "<?php echo (isset($error["pass1"]) ? "border:2px solid red;" : null); ?>" 
		value = "<?php echo (isset($_POST["pass1"]) ? cleanDisplay($_POST["pass1"]) : null); ?>" />
		<?php echo (isset($error["pass1"]) ? "<p class = \"inputerror\">".$error["pass1"]."</p>" : null); ?>
		
		<p class = "label">Repeat Password</p>
		<input type="password" name="pass2" 
		style = "<?php echo (isset($error["pass2"]) ? "border:2px solid red;" : null); ?>" 
		value = "<?php echo (isset($_POST["pass2"]) ? cleanDisplay($_POST["pass2"]) : null); ?>" />
		<?php echo (isset($error["pass2"]) ? "<p class = \"inputerror\">".$error["pass2"]."</p>" : null); ?>
		
		<p class = "label">Email</p>
		<input type="text" name="email" 
		style = "<?php echo (isset($error["email"]) ? "border:2px solid red;" : null); ?>" 
		value = "<?php echo (isset($_POST["email"]) ? cleanDisplay($_POST["email"]) : null); ?>" />
		<?php echo (isset($error["email"]) ? "<p class = \"inputerror\">".$error["email"]."</p>" : null); ?>
		
		<p class = "label"><input type="checkbox" name="terms" value="agree" id="check"> I agree with the <a href="legal.php">terms and conditions</a>
		<?php echo (isset($error["terms"]) ? "<p class = \"inputerror\">".$error["terms"]."</p>" : null); ?></p>
		
		<input type="submit" name="register" value="Register" id="submit" /><p class="label">Already have an account? <a href="login.php">Login now</a></p>

	</form>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
