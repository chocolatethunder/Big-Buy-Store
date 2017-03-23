<?php

// Include header template
include ("inc/header.inc.php");

?>

<div id = "content">
		
		<div id = "title">
		
			<p class="microtitle">The</p>
			<p class="title">BIGBUY STORE</p>
			
		</div>
		
		<?php //errorhandler(); ?>
			
		<form action="login.php" method="post" id="nonauthform">  
	
			<input type="hidden" name="token" value="<?php //echo $session->generateToken(30); ?>" />
			<input type="hidden" name="form" value="login" />
			
			<p class = "label">Username</p>
			<input type="text" name="uname" 
			style = "<?php echo (isset($error["uname"]) ? "border:2px solid red;" : null); ?>" 
			value = "<?php echo (isset($_POST["uname"]) ? cleanDisplay($_POST["uname"]) : null); ?>" />
			<?php echo (isset($error["uname"]) ? "<p class = \"inputerror\">".$error["uname"]."</p>" : null); ?>
			<p class="microlabel"><a href ="flare.php?recover=username">I forgot my username</a></p>
			
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
