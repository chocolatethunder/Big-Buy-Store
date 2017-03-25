<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

$loadpage = "sellers";

if (isset($_GET["type"])) {
	$type = prep($_GET["type"],"a");
	$pattern = "/^(sellers|moderators)$/";
	if (preg_match($pattern, $type) == true) {
		$loadpage = $type; 
	}
}

// Access check
$acc = $user->account_type();

if ($acc < 3) {
	gotoPage("dashboard.php");
} else {
	include ("inc/class_moderator.inc.php");
	$mod = new moderator($db);
}

// Process User -> Seller transition
if (isset($_POST["sellersApproveAll"])) {
	if (!empty($_POST["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$mod->approveAll(1);
	}
}

if (isset($_POST["sellersDenyAll"])) {
	if (!empty($_POST["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$mod->denyAll(1);
	}
}

if (isset($_POST["sellersApprove"])) {
	if (!empty($_POST["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$mod->batchApprove($_POST, 1);
	}
}

// Process Seller -> Moderator transition
if (isset($_POST["modsApproveAll"])) {
	if (!empty($_POST["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$mod->approveAll(2);
	}
}

if (isset($_POST["modsDenyAll"])) {
	if (!empty($_POST["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$mod->denyAll(2);
	}
}

if (isset($_POST["modsApprove"])) {
	if (!empty($_POST["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$mod->batchApprove($_POST, 2);
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
	
		<?php
		
		if ($loadpage == "sellers") {		
			
			$userdata = $mod->getSellerWaitlist();
				
			if ($userdata != null) {
			
				?>
				
				<div id="divlabel">Approve pending seller applications</div>
				
				<form action="approve.php?type=sellers" method="post" id="contentform">
				
					<input type="hidden" name="token" value="<?php echo generateToken(30); ?>" />
					<input type="hidden" name="form" value="approvesellers" />			
					
					<?php
					
					foreach ($userdata as $user) {
						
						echo "<div id =\"label\">".$user["fname"]." ".$user["lname"]." </div><div id =\"fieldreq\">";
						echo "<div id = \"radiocheck_md\">";
						
						echo "<div>
									<input type=\"radio\" name=\"uid_".$user["uid"]."\" value=\"Y\" ".(isset($_POST["uid_".$user["uid"]]) ? $_POST["uid_".$user["uid"]] == "Y" ? "checked" : null : "checked")."><label onlick=\"\" class=\"tgglbtn\">Approve</label>
								</div>
								<div>
									<input type=\"radio\" name=\"uid_".$user["uid"]."\" value=\"N\" ".(isset($_POST["uid_".$user["uid"]]) ? $_POST["uid_".$user["uid"]] == "N" ? "checked" : null : "")."><label onlick=\"\" class=\"tgglbtn\">Deny</label>
								</div>
							</div>
						</div>";
						
					}
					
					?>
					
					<input type="submit" name="sellersApprove" value="Process" id="submit" />
					<input type="submit" name="sellersApproveAll" value="Approve All" id="misc" />
					<input type="submit" name="sellersDenyAll" value="Deny All" id="misc" />
					
				</form>	
			
			<?php
			
			} else {
				
				$_SESSION["caution"] = "There are no users waiting to be approved";
				errorhandler();
				
			}
			
		}
		
		if ($loadpage == "moderators") {		
			
			$userdata = $mod->getModWaitlist();
			
			if ($userdata != null) {
			
				?>
				
				<div id="divlabel">Approve pending moderator applications</div>
				
				<form action="approve.php?type=moderators" method="post" id="contentform">
				
					<input type="hidden" name="token" value="<?php echo generateToken(30); ?>" />
					<input type="hidden" name="form" value="approvemods" />			
					
					<?php
					
					foreach ($userdata as $user) {
						
						echo "<div id =\"label\">".$user["fname"]." ".$user["lname"]." </div><div id =\"fieldreq\">";
						echo "<div id = \"radiocheck_md\">";
						
						echo "<div>
									<input type=\"radio\" name=\"uid_".$user["uid"]."\" value=\"Y\" ".(isset($_POST["uid_".$user["uid"]]) ? $_POST["uid_".$user["uid"]] == "Y" ? "checked" : null : "checked")."><label onlick=\"\" class=\"tgglbtn\">Approve</label>
								</div>
								<div>
									<input type=\"radio\" name=\"uid_".$user["uid"]."\" value=\"N\" ".(isset($_POST["uid_".$user["uid"]]) ? $_POST["uid_".$user["uid"]] == "N" ? "checked" : null : "")."><label onlick=\"\" class=\"tgglbtn\">Deny</label>
								</div>
							</div>
						</div>";
						
					}
					
					?>
					
					<input type="submit" name="modsApprove" value="Process" id="submit" />
					<input type="submit" name="modsApproveAll" value="Approve All" id="misc" />
					<input type="submit" name="modsDenyAll" value="Deny All" id="misc" />
					
				</form>	
			
			<?php
			
			} else {
				
				$_SESSION["caution"] = "There are no users waiting to be approved";
				errorhandler();
				
			}
			
		}
			
		?>
	
	</div>

</div>


<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
