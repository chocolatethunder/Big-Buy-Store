<?php


function cleanDisplay($data) {
	$data = htmlentities($data, ENT_COMPAT | ENT_HTML401, 'UTF-8');
	$data = filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
	return $data;
}

function prep($data, $flag="an") {
	
	switch ($flag) {
		
		case "an":
		return preg_replace("/[^A-Za-z0-9\.]/", '', $data);
		break;
		
		case "anu":
		return preg_replace("/[^A-Za-z0-9_\.]/", '', $data);
		break;
		
		case "anud":
		return preg_replace("/[^A-Za-z0-9_-]/", '', $data);
		break;
		
		case "n":
		return preg_replace("/[^0-9\.]/", '', $data);
		break;
		
		case "a":
		return preg_replace("/[^A-Za-z]/", '', $data);
		break;
		
		default:
		return "no-match";
		break;
	}

}

function roundbalance($value, $decimals) {
	
	$multiplier = 1;
	
	for ($i=1; $i<=$decimals; $i++) {
		$multiplier = $multiplier * 10;
	}
	
	if (isset($value) && is_numeric($value)) {
		return floor($value * $multiplier) / $multiplier;
    	//return round($value * 1e8);
	}
}


function validateLength($data, $len) {
	if (strlen($data) < $len) {
		return false;
	} else {
		return true;
	}
}

function gotoPage($location) {
    header("Location: ". $location);
    exit;
}

function date_convert($dt, $tz1, $df1, $tz2, $df2) {
	$res = '';
	if(!in_array($tz1, timezone_identifiers_list())) { // check source timezone
		trigger_error(__FUNCTION__ . ': Invalid source timezone ' . $tz1, E_USER_ERROR);
	} elseif(!in_array($tz2, timezone_identifiers_list())) { // check destination timezone
		trigger_error(__FUNCTION__ . ': Invalid destination timezone ' . $tz2, E_USER_ERROR);
	} else {
		// create DateTime object
		$d = DateTime::createFromFormat($df1, $dt, new DateTimeZone($tz1));
		// check source datetime
		if($d && DateTime::getLastErrors()["warning_count"] == 0 && DateTime::getLastErrors()["error_count"] == 0) {
			// convert timezone
			$d->setTimeZone(new DateTimeZone($tz2));
			// convert dateformat
			$res = $d->format($df2);
		} else {
			trigger_error(__FUNCTION__ . ': Invalid source datetime ' . $dt . ', ' . $df1, E_USER_ERROR);
		}
	}
	return $res;
}

function toDate($sqldate, $notime=false) {

	$usertz = filter_input(INPUT_COOKIE, "user_tz", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	
	// detect user timezone
	if (!isset($usertz) || empty($usertz) || !in_array($usertz, timezone_identifiers_list()) == true) {
		$usertz = "America/Los_Angeles";
	}
	
	if ($notime == true) {
		$df2 = "M j, o";
	} else {
		$df2 = "M j o, g:i:s A";
	}
	
	return date_convert ($sqldate, "UTC", "Y-m-d H:i:s", $usertz, $df2);
}

function toSQLDate($unixtime) {
	return date('Y-m-d H:i:s', $unixtime);
}

function getStartEndTimes() {
	
	$date = time();
	$dotw = date("w", $date);
	
	$range["daily"]["start"] = strtotime("midnight", time());
	$range["daily"]["end"] 	= strtotime("tomorrow", time())-1;
	
	$range["weekly"]["end"]	= ($dotw == 6) ? (strtotime("midnight", $date)-1) : (strtotime("midnight next Sunday", $date)-1);
	$range["weekly"]["start"] = ($range["weekly"]["end"] - (7*24*60*60)+1);
	
	$range["monthly"]["start"] = strtotime("first day of", $range["daily"]["start"]);
	$range["monthly"]["end"] 	= strtotime("last day of", $range["daily"]["end"]);
	
	foreach ($range as $key => $value) {		
		foreach ($value as $label => $data) {			
			$range[$key][$label] = toSQLDate($data);			
		}		
	}

	return($range);
	
}

function generateRandString($len) {
	$randString = bin2hex(openssl_random_pseudo_bytes($len));
	return substr($randString, 0, $len);
}

function generateToken($len) {
	$token = bin2hex(openssl_random_pseudo_bytes($len));
	$_SESSION["token"] = $token;
	return $token;
}

function encrypt($message, $type) {
	
	switch($type) {
		
		case "info":
		$cipher = Crypto::Encrypt($message, SQLKEY_INFO);
		break;
		
		case "bank":
		$cipher = Crypto::Encrypt($message, SQLKEY_BANK);
		break;
	
	}	
	return bin2hex($cipher);
	
}

function decrypt($cipher, $type) {

	$cipher = hex2bin($cipher);
	
	switch($type) {
		
		case "info":
		$message = Crypto::Decrypt($cipher, SQLKEY_INFO);
		break;
		
		case "bank":
		$message = Crypto::Decrypt($cipher, SQLKEY_BANK);
		break;
	
	}	
	return $message;	
}

function hideAccountInfo($str){
    $len = strlen($str);
    return str_repeat('*',4) . substr($str, $len - 3 ,3);
}

function truncatestr($str, $len){
	if ($len < strlen($str)) {
		return substr($str, 0, $len).str_repeat('.',3);
	}
	return substr($str, 0, $len);
}

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}

function errorhandler(){
	
	global $error;
	
	echo (isset($_SESSION["success"]) ? "<div id=\"divcontent\"><div id = \"complete\">".$_SESSION["success"]."</div></div>" : null);
	echo (isset($_SESSION["caution"]) ? "<div id=\"divcontent\"><div id = \"incomplete\">".$_SESSION["caution"]."</div></div>" : null);
	echo (isset($_SESSION["failed"]) ? "<div id=\"divcontent\"><div id = \"failed\">".$_SESSION["failed"]."</div></div>" : null);
	echo (isset($error["form"]) ? "<div id=\"divcontent\"><div id = \"failed\">".$error["form"]."</div></div>" : null);
}

function clearerror() {
	unset($_SESSION["success"]);
	unset($_SESSION["caution"]);
	unset($_SESSION["failed"]);
}

function get_ip_address(){
	
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
		if (array_key_exists($key, $_SERVER) === true){

            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe
				
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){                    
					return $ip;
                }
            }
        }
    }
	return false;
}


function curlit($apiurl, $opts=null) {
	
	global $session;

	$curl_session 	= curl_init($apiurl);
	$curl_options 	= array(
		CURLOPT_HTTPHEADER		=> array('Content-type: application/json'),
		CURLOPT_RETURNTRANSFER	=> 1,
		CURLOPT_SSL_VERIFYPEER	=> true,
		CURLOPT_SSL_VERIFYHOST	=> 2
	);
	
	if ($opts != null) {
		
		foreach ($opts as $option => $value) {

			if ($option == CURLOPT_HTTPHEADER) {
				foreach ($value as $item) {
					array_push($curl_options[CURLOPT_HTTPHEADER], $item);
				}
			} else if (array_key_exists($option, $curl_options)) {
				$curl_options[$option] = $value;
			} else {
				array_push($curl_options, $value);
			}
		}
		
	}
	
	curl_setopt_array($curl_session, $curl_options);

	$rawresponse 	= curl_exec($curl_session);
    $status 		= curl_getinfo($curl_session, CURLINFO_HTTP_CODE);
	$curl_error 	= curl_error($curl_session);        
	
	curl_close($curl_session);
	
	$response		= json_decode($rawresponse, true);
	
	if ($curl_error) {
		return $curl_error;
	}
	return $response;
}

function simplecss ($html, $css=array()) {
	
	if (!empty($html) && sizeof($css) > 0) {
		
		foreach ($css as $label => $property) {
			
			$firstchar = substr($label, 0, 1);
			
			if ($firstchar == ".") {
				$capsule = "class = \"".substr($label,1)."\"";
				$replace = "style=\"".$property."\"";
			}
			
			if ($firstchar == "#") {
				$capsule = "id = \"".substr($label,1)."\"";				
				$replace = "style=\"".$property."\"";
			}
			
			if ($firstchar != "." && $firstchar != "#") {
				$capsule = "<".$label.">";
				$replace = "<".$label." style=\"".$property."\">";
			}
			
			$html = str_replace($capsule, $replace, $html);
		}
		
		return $html;
		
	}
	
	return false;
	
}

function boxify ($text) {
	
	$strlen = strlen($text);
	$reply = "";
	
	for ($i=0; $i<$strlen; $i++) {
		$reply .= "<div id = \"boxit\">".substr($text, $i, 1)."</div>";
	}
	
	return $reply;
	
}

function debugarray($array) {
	
	echo "<pre>";
	echo print_r($array);
	echo "</pre>";
	
}

function dashnav ($acc) {
	
	if ($acc == 1) {
		echo "<div id = \"upgrade\"><a href=\"upgrade.php\">Become a seller!</a></div><br/>";
	}
	
	$user["dashboard.php"] 	= "Orders";
	$user["address.php"] 	= "Change Address";
	$user["wishlist.php"] 	= "Wishlist";
	$user["password.php"] 	= "Change Password";
	
	$seller["pendingorders.php"] 	= "Pending Orders";
	$seller["listings.php"] 		= "Listings";
	$seller["addproduct.php"] 		= "Add Product";
	
	$mod["approve.php?type=sellers"] 	= "Approve sellers";
	$mod["approve.php?type=mods"] 		= "Approve moderators";
	
	// Buyer level
	if ($acc >= 1) {

		echo "<div id = \"class_user\"><ul>";

		foreach ($user as $k => $v) {					
			echo "<li class = \"".$k."\"><a href = \"".$k."\">".$v."</a></li>";					
		}

		echo "</ul></div>";
	}
	
	// Seller level
	if ($acc >= 2) {
		
		echo "<div id = \"class_seller\"><ul>";

		foreach ($seller as $k => $v) {					
			echo "<li class = \"".$k."\"><a href = \"".$k."\">".$v."</a></li>";					
		}

		echo "</ul></div>";
	}
	
	// Admin Level
	if ($acc >= 3) {
		
		echo "<div id = \"class_mods\"><ul>";

		foreach ($mod as $k => $v) {					
			echo "<li class = \"".$k."\"><a href = \"".$k."\">".$v."</a></li>";					
		}

		echo "</ul></div>";
	}
	
}


?>