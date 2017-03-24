<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Big Buy Store</title>

  <link rel="stylesheet" type="text/css" href="css/fonts/fonts.css">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  
  <script src="script.js"></script>
  
</head>

<body>

<div id = "wrapper">

	<div id = "header">
		
		<div id = "menu">
		
			<?php if (login::loginCheck() == true) { ?>
				
				<div id = "welcomemsg">Welcome <?php echo $_SESSION["uname"]; ?></div>
				
				<ul>
				
					<li class = "home"><a href = "index.php">Home</a></li>
					<li class = "dash"><a href = "dashboard.php">Dashboard</a></li>
					<li class = "cart"><a href = "cart.php">Cart</a></li>
					<li class = "logout"><a href = "login.php">Logout</a></li>
				
				</ul>
			
			<?php } else { ?>		
				
				<div id = "welcomemsg"></div>
				
				<ul>
				
					<li class = "home"><a href = "index.php">Home</a></li>
					<li class = "login"><a href = "login.php">Login</a></li>
					<li class = "signup"><a href = "signup.php">Signup</a></li>
				
				</ul>
			
			<?php } ?>
		
		</div>
		
		<div id = "sitelogo"></div>
		<div id = "sitename">BIGBUY</div>
		
		<br/>
		
		<div id = "search">
			
			<div id = "searchlabel">SEARCH</div>
			
			<form action="search.php" method="post" id="searchform">  

				<input type="hidden" name="token" value="<?php echo generateToken(30); ?>" />
				<input type="hidden" name="form" value="search" />

				<input type="text" name="search" 
				style = "<?php echo (isset($error["search"]) ? "border:2px solid red;" : null); ?>" 
				value = "<?php echo (isset($_POST["search"]) ? cleanDisplay($_POST["search"]) : null); ?>" />
				<?php echo (isset($error["search"]) ? "<p class = \"inputerror\">".$error["search"]."</p>" : null); ?>
				
				<input type="submit" name="search" value="Go" id="submit" />

			</form>			
		
		</div>	

	</div>
	
	