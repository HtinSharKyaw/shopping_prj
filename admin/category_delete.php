<?php 
require '../config/config.php';
$stmt = $connection->prepare("DELETE FROM categories WHERE id=:id");
$result = $stmt->execute(
    array(':id'=>$_GET['id'])
);
if($result){
    echo "<script>alert('successfully deleted');
                window.location.href = 'category.php';
        </script>";
}
?>