<?php 
require '../config/config.php';
$stmt = $connection->prepare("DELETE FROM users WHERE id=".$_GET['id']);
$stmt->execute();
header("Location:userview.php");
?>