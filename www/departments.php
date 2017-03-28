<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

// Access check
$acc = $user->account_type();

if ($acc < 3) {
	gotoPage("dashboard.php");
} else {
	include ("inc/class_moderator.inc.php");
	$mod = new moderator($db);
}

if (isset($_POST["add_dept"])) {	
	$error = array();	
	if($mod->addDepartment($_POST["dname"])) {
		$_SESSION["success"] = "Department has been added succesfully.";
		gotoPage("departments.php");
	}	
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
	
	
		<div id = "divlabel">Add a new department</div>
		
		<?php errorhandler(); ?>
	
		<form action="departments.php" method="post" id = "contentform">

			<input type="hidden" name="token" id = "csrftoken" value="<?php echo generateToken(30); ?>" />
			<input type="hidden" name="form" id = "csrfform" value="adddeptname" />
			
			<div id ="label">Department Name</div><div id ="fieldreq"><input type="text" name="dname" style = "<?php echo (isset($error["dname"]) ? "border:2px solid red;" : null); ?>" value = "<?php echo (isset($_POST["dname"]) ? cleanDisplay($_POST["dname"]) : null); ?>" /><span class = "detail"></span><?php echo (isset($error["dname"]) ? "<p class = \"inputerror\">".$error["dname"]."</p>" : null); ?></div>
			
			<input type="submit" id="submit" name="add_dept" value="Add" />
		
		</form>
		
	
	</div>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
