<?php 
session_start();
require '../config/config.php';
require '../config/common.php';

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('Location:login.php');
}

if($_SESSION['role']!=1){
  header('Location:login.php');
}
//this code is for sale order result
$stmt = $connection->prepare("SELECT * FROM sale_order_detail where id=:id");
$stmt->execute(
  array(':id'=>$_GET['id'])
);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$stmtProduct = $connection->prepare("SELECT * FROM products where id IN (SELECT product_id from sale_orders where id=:id)");
$stmtProduct->execute(
  array(':id'=>$_GET['id'])
);
$productResult = $stmtProduct->fetch(PDO::FETCH_ASSOC);

?>
<?php include ('header.php');?>
<hr><br>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12" style="display:flex;flex-direction: row;justify-content: center; ">
        <div class="card" style="width:300px;">
          <div class="card-header">
            <h3 class="card-title" >Order Detail</h3>
          </div>
          <div class="card-body">
          <ul class="list-group">
            <li class="list-group-item">Product Name :<?php echo ' '.escape($productResult['name'])  ?></li>
            <li class="list-group-item">Quantity :<?php echo ' '.escape($result['quantity'] )?></li>
            <li class="list-group-item">Price :<?php echo ' '.escape($result['price']) ?></li>
            <li class="list-group-item">date : <?php echo ' '.date('Y-m-d',strtotime($result['order_date']))?> </li>
          </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include('footer.html')?>