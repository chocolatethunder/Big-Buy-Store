<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

// Access check
$acc = $user->account_type();

if ($acc < 2) {
	gotoPage("dashboard.php");
} else {
	include ("inc/class_seller.inc.php");
	$seller = new seller($db);
	$depts 	= $seller->getDepartments();
}

$loadpage = "add";
$ldata = null;

// Check actions
if (isset($_GET["action"])) {
	$type = prep($_GET["action"],"a");
	$pattern = "/^(edit|delete)$/";
	if (preg_match($pattern, $type) == true) {
		$loadpage = $type; 
	}
}

if ($loadpage == "delete") {	
	$seller->listingID = prep($_GET["id"],"n");
	if ($seller->deleteListing()) {
		$_SESSION["success"] = "The listing has been deleted succesfully.";
		gotoPage("listings.php");
	} else {
		$_SESSION["failed"] = "There was a problem deleting your listing.";
		gotoPage("listings.php");
	}
}

if ($loadpage == "edit") {	
	$seller->listingID = prep($_GET["id"],"n");
	if ($seller->getListingData()) {
		$ldata = $seller->getListingData();
	} else {
		gotoPage("listings.php");
	}
}


// Process
if(isset($_POST["add_product"])) {
	$error = array();
	
	$seller->deptid 	= $_POST["dept"];
	$seller->pname 		= $_POST["pname"];
	$seller->desp 		= $_POST["desp"];
	$seller->qty 		= $_POST["qty"];
	$seller->price 		= $_POST["price"];
	
	if ($seller->addProduct()) {
		$_SESSION["success"] = "Product has been listed succesfully.";
		gotoPage("listings.php");
	}
	
}

if(isset($_POST["edit_product"])) {
	$error = array();
	
	$seller->deptid 	= $_POST["dept"];
	$seller->pname 		= $_POST["pname"];
	$seller->desp 		= $_POST["desp"];
	$seller->qty 		= $_POST["qty"];
	$seller->price 		= $_POST["price"];
	$seller->listingID 	= prep($_GET["id"],"n");

	if ($seller->updateListing()) {
		$_SESSION["success"] = "Product has been updated succesfully.";
		gotoPage("listings.php");
	}

}

// Include header template
include ("inc/header.inc.php");

?>

<div id = "maincontent">
	
	<div id = "submenu"> <?php dashnav($acc); ?> </div>
	
	<br/>
	
	<div id = "displaywindow">
		
		<?php 
		
		switch ($loadpage) {
			
			case "add":
			echo "<div id = \"divlabel\">Add a new product</div>";
			break;
			
			case "edit":
			echo "<div id = \"divlabel\">Editing product</div>";
			break;
			
			default:
			echo "<div id = \"divlabel\">Add a new product</div>";
			break;
			
		}
		
		?>
		
		<?php errorhandler(); ?>
	
		<form action="addproduct.php<?php echo ($loadpage == "edit" ? "?action=edit" : null); echo ($loadpage == "edit" ? "&id=".$_GET["id"] : null);?>" method="post" id = "contentform">

			<input type="hidden" name="token" id = "csrftoken" value="<?php echo generateToken(30); ?>" />
			<input type="hidden" name="form" id = "csrfform" value="<?php echo ($loadpage == "edit" ? "editproduct" : "addproduct"); ?>" />
			
			<div id ="label">Department</div><div id ="fieldreq">			
				<select name="dept" id="dept" style = "<?php echo (isset($error["dept"]) ? "outline:red solid 2px;" : null); ?>">
					<?php 
					foreach ($depts as $dept) {
						echo "<option ";
								if (isset($_POST["dept"]) && ($_POST["dept"] == $dept["deptid"])) {
									echo "selected=\"selected\" ";
								} else if (isset($ldata["department"]) && ($ldata["department"] == $dept["deptid"])) {
									echo "selected=\"selected\" ";
								}							
						echo "value=\"".$dept["deptid"]."\">".$dept["deptname"]."</option>";
					}
					?>
				</select>		
			<span class = "detail"></span>
			<?php echo (isset($error["dept"]) ? "<p class = \"inputerror\">".$error["dept"]."</p>" : null); ?>
			</div>
			
			<div id ="label">Product Name</div><div id ="fieldreq"><input type="text" name="pname" style = "<?php echo (isset($error["pname"]) ? "border:2px solid red;" : null); ?>" value = "<?php echo (isset($_POST["pname"]) ? cleanDisplay($_POST["pname"]) : (isset($ldata["pname"]) ? cleanDisplay($ldata["pname"]) : null )); ?>" /><span class = "detail"></span><?php echo (isset($error["pname"]) ? "<p class = \"inputerror\">".$error["pname"]."</p>" : null); ?></div>
			
			<div id ="label">Description</div><div id ="fieldreq">​<textarea id="txtArea" name="desp" style = "<?php echo (isset($error["desp"]) ? "border:2px solid red;" : null); ?>"><?php echo (isset($_POST["desp"]) ? cleanDisplay($_POST["desp"]) : (isset($ldata["description"]) ? cleanDisplay($ldata["description"]) : null )); ?></textarea><span class = "detail"></span><?php echo (isset($error["desp"]) ? "<p class = \"inputerror\">".$error["desp"]."</p>" : null); ?></div>
			
			<div id ="label">Quantity</div><div id ="fieldreq"><input type="text" name="qty" style = "<?php echo (isset($error["qty"]) ? "border:2px solid red;" : null); ?>" value = "<?php echo (isset($_POST["qty"]) ? cleanDisplay($_POST["qty"]) : (isset($ldata["units"]) ? cleanDisplay($ldata["units"]) : null )); ?>" /><span class = "detail"></span><?php echo (isset($error["qty"]) ? "<p class = \"inputerror\">".$error["qty"]."</p>" : null); ?></div>
			
			<div id ="label">Price</div><div id ="fieldreq"><input type="text" name="price" style = "<?php echo (isset($error["price"]) ? "border:2px solid red;" : null); ?>" value = "<?php echo (isset($_POST["price"]) ? cleanDisplay($_POST["price"]) : (isset($ldata["price"]) ? cleanDisplay($ldata["price"]) : null )); ?>" /><span class = "detail"></span><?php echo (isset($error["price"]) ? "<p class = \"inputerror\">".$error["price"]."</p>" : null); ?></div>
			
			<input type="submit" id="submit" name="<?php echo ($loadpage == "edit" ? "edit_product" : "add_product"); ?>" value="<?php echo ($loadpage == "edit" ? "Update" : "Add"); ?>" />
		
		</form>
	
	</div>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
