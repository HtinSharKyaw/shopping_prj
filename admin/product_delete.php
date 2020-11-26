<?php 
require '../config/config.php';
$stmt = $connection->prepare("DELETE from products where id=:id");
$stmt->execute(
    array(':id'=>$_GET['id'])
);
echo "<script>alert('successfully deleted');
window.location.href='index.php';</script>";
?>