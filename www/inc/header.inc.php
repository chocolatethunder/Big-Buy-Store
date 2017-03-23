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

	<div id = "header">
		
		<div id = "menu">
			<ul>
			
				<li class = "home"><a href = "index.php">Home</a></li>
				<li class = "login"><a href = "login.php">Login</a></li>
				<li class = "signup"><a href = "signup.php">Signup</a></li>
			
			</ul>
		</div>
		
		<div id = "sitelogo"></div>
		<div id = "sitename">BIGBUY</div>
		
		<div id = "search">
			
			<div id = "searchlabel">SEARCH</div>
			
			<form action="search.php" method="post" id="searchform">  

				<input type="hidden" name="token" value="<?php echo generateToken(30); ?>" />
				<input type="hidden" name="form" value="search" />

				<input type="text" name="uname" 
				style = "<?php echo (isset($error["uname"]) ? "border:2px solid red;" : null); ?>" 
				value = "<?php echo (isset($_POST["uname"]) ? cleanDisplay($_POST["uname"]) : null); ?>" />
				<?php echo (isset($error["uname"]) ? "<p class = \"inputerror\">".$error["uname"]."</p>" : null); ?>
				
				<input type="submit" name="search" value="Go" id="submit" />

			</form>

			
		
		</div>		
		

	</div>
	
	