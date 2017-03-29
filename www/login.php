<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

// Process Login
if (login::loginCheck() == true) {
	login::logout();
}

if (isset($_POST["login"])) {			
	$error = array();	
	if ($securityCheck->checkForm($_POST["form"]) == true) {		
		$login = new login($db, $_POST["uname"], $_POST["pass"]);
		if (login::loginCheck() == true) {
			if (isset($_GET["redirect"]) && !empty($_GET["redirect"])) {
				gotoPage($_GET["redirect"]);
			} else {
				gotoPage("dashboard.php");
			}
		}
	}
}

// Include header template
include ("inc/header.inc.php");

?>

<div id = "content">
		
	<div id = "title">
	
		<p class="microtitle">BIGBUY Store</p>
		<p class="title">LOGIN</p>
		
	</div>
	
	<?php errorhandler(); ?>
		
	<form action="login.php<?php 
	
		if (isset($_GET["redirect"]) && !empty($_GET["redirect"])) {
			echo "?redirect=".urlencode($_GET["redirect"]);
		}?>" method="post" id="nonauthform">  

		<input type="hidden" name="token" value="<?php echo generateToken(30); ?>" />
		<input type="hidden" name="form" value="login" />
		
		<p class = "label">Username</p>
		<input type="text" name="uname" 
		style = "<?php echo (isset($error["uname"]) ? "border:2px solid red;" : null); ?>" 
		value = "<?php echo (isset($_POST["uname"]) ? cleanDisplay($_POST["uname"]) : null); ?>" />
		<?php echo (isset($error["uname"]) ? "<p class = \"inputerror\">".$error["uname"]."</p>" : null); ?>
		
		<p class = "label">Password</p>
		<input type="password" name="pass" 
		style = "<?php echo (isset($error["pass"]) ? "border:2px solid red;" : null); ?>" 
		value = "<?php echo (isset($_POST["pass"]) ? cleanDisplay($_POST["pass"]) : null); ?>" />
		<?php echo (isset($error["pass"]) ? "<p class = \"inputerror\">".$error["pass"]."</p>" : null); ?>
		<p class="microlabel"><a href ="flare.php?recover=password">I forgot my password</a></p>
		
		<input type="submit" name="login" value="Login" id="submit" />
		
		<p class="label">Don't have an account? <a href="signup.php">Sign up here</a></p>			


	</form>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
