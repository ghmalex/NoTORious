<?php
//
// NoTORious
//
// Block TOR traffic on your website
// Version 1.0
// 

// Get all TOR IP addresses
function getTorExitNodes() {
	$url = 'https://check.torproject.org/torbulkexitlist';
	$exit_nodes = file_get_contents($url);
	return explode("\n", $exit_nodes);
}

// Check if IP address is from the TOR network
function isTorExitNode($ip, $tor_exit_nodes) {
	return in_array($ip, $tor_exit_nodes);
}

// Check IP address
function checkTOR($ip){
	// Check if user has the tor safe cookie to prevent longer loading times
	if(!isset($_COOKIE['tor_safe'])) {
		// Cookie is not set

		// Get the list of TOR exit node IP addresses
		$tor_exit_nodes = getTorExitNodes();

		// Check if user using the TOR network
		if(isTorExitNode($ip, $tor_exit_nodes)){
			// User is using the TOR network
			return true;
			
		}else{
			// Set cookie
			setcookie("tor_safe", 1, time() + (86400 * 30), "/");
			
			// No TOR network
			return false;
			
		}
	}
}

// Usage
// IP address
$visitor_ip = $_SERVER['REMOTE_ADDR'];

// Check if the visitor is using a TOR exit node
if (checkTOR($visitor_ip)) {
	// If the visitor is from a TOR exit node, block access
	http_response_code(403); // Forbidden
	echo "Access from TOR is not allowed.";
	exit;
}

?>
