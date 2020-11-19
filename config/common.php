<?php
//this code is for token generation
if (empty($_SESSION['_token'])) {
	if (function_exists('random_bytes')) {
		$_SESSION['_token'] = bin2hex(random_bytes(32));
	} else {
		$_SESSION['_token'] = bin2hex(openssl_random_pseudo_bytes(32));
	}
}
//this code for correct token or not that means to protect csrf
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	if(!hash_equals($_SESSION['_token'],$_POST['token'])){
		echo 'Invalid token'; 	
		die();
	}
}
//this code is for prevent xss attack
function escape($html) {
	return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}
?>