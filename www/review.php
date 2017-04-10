<?php

// Load classes
$file = preg_replace('/\.php$/', '', basename(__FILE__));
include ("inc/classload.inc.php");

// Process
if (isset($_GET["type"]) && !empty($_GET["type"]) && 
	isset($_GET["id"]) && !empty($_GET["id"]) && 
	$_GET["type"] == "seller" || $_GET["type"] == "product") {
	$r = new review($db, $_GET["type"], $_GET["id"]);
	$reviews = $r->getSellerReviews();
	
	// Submit review
	if (isset($_POST["submitreview"])) {
		$r->submitReview();
		$r = new review($db, $_GET["type"], $_GET["id"]);
		$reviews = $r->getSellerReviews();
	}
	
} else {
	gotoPage("index.php");
}


// Include header template
include ("inc/header.inc.php");

?>


<div id = "maincontent">
	
	<div id = "productwindow">	
		
	<?php
	
	echo "<div id = \"item-title\">".($_GET["type"] == "seller" ? $r->getSellerName() : $r->getProductName())."&nbsp;".$r->printStars()."</div><br/>";
	
	if (login::loginCheck() && $r->reviewAllowed()) {
		
	?>
		
		<form action="review.php?<?php echo "type=".$_GET["type"]."&id=".$_GET["id"]; ?>" method="post" id = "">

			<input type="hidden" name="token" id = "csrftoken" value="<?php echo generateToken(30); ?>" />
			<input type="hidden" name="form" id = "csrfform" value="submitreview" />
			<input type="hidden" name="type" id = "" value="<?php echo $_GET["type"]; ?>" />
			
			​<textarea id="txtArea" name="review" style = ""></textarea>
			
			<br/>
			
			<span class="rating">
				<input type="radio" class="rating-input"
					id="rating-input-1-5" name="rating-input-1" value = "5">
				<label for="rating-input-1-5" class="rating-star"></label>
				<input type="radio" class="rating-input" value = "4"
					id="rating-input-1-4" name="rating-input-1">
				<label for="rating-input-1-4" class="rating-star"></label>
				<input type="radio" class="rating-input" value = "3"
					id="rating-input-1-3" name="rating-input-1">
				<label for="rating-input-1-3" class="rating-star"></label>
				<input type="radio" class="rating-input" value = "2"
					id="rating-input-1-2" name="rating-input-1">
				<label for="rating-input-1-2" class="rating-star"></label>
				<input type="radio" class="rating-input" value = "1"
					id="rating-input-1-1" name="rating-input-1">
				<label for="rating-input-1-1" class="rating-star"></label>
			</span>
			
			<br/>
			
			<input type="submit" id="submit" name="submitreview" value="Submit" />
		
		</form>
		
		<br/>
		
	<?php
	
	}

	if (count($reviews) > 0) {
		
		foreach($reviews as $rev) {		
		
			echo "<div id =\"reviewer\">".$rev["uname"]."</div>";
			echo "<div id =\"stars\">".$r->printStarsFromNum($rev["rating"])."</div>";
			echo "<div id =\"reviewtext\">".$rev["review"]."</div>";
			echo "<hr class = \"separator\">";
			
		}
		
	} else {
		
		echo "<div id =\"reviewer\">This ".$_GET["type"]." does not have any reviews yet.</div>";
		
	}	
	
	?>
	
	</div>
	
</div>

<?php

// Include footer template
include ("inc/footer.inc.php");
	
?>
