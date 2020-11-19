<?php
if (empty($_SESSION['_token'])) {
	if (function_exists('random_bytes')) {
		$_SESSION['_token'] = bin2hex(random_bytes(32));
	} else {
		$_SESSION['_token'] = bin2hex(openssl_random_pseudo_bytes(32));
	}
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	if(!hash_equals($_SESSION['_token'],$_POST['token'])){
		echo 'Invalid token'; 	
		die();
	}
}
function escape($html) {
	return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}
?>