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
?>

<?php include ('header.php');?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Order List</h3>
              </div>
              <!-- php code for getting data -->
              <?php 
                $stmt = $connection->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
                $stmt->execute();
                $rawResult = $stmt->fetchAll();

                if($_GET){
                  $pageNo = $_GET['pageNo'];
                }else{
                  $pageNo = 1;
                }
                $numberOfRecords = 5;
                $offset = ($pageNo - 1)*$numberOfRecords;
                

                  $totalPages = ceil(count($rawResult)/$numberOfRecords);
                  $stmt = $connection->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$numberOfRecords");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
              ?>

              <!-- /.card-header -->
              <div class="card-body">
                <div class="">
                    <!-- <a href="category_add.php" type="button" class="btn btn-success">Create Category</a> -->
                </div><br>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">Id</th>
                      <th>Customer Name</th>
                      <th>Product Name</th>
                      <th>Total Price</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!--php code-> for each start-->
                  <?php if($result) { 
                    $i = 1;
                    foreach($result as $value){
                        $userStmt = $connection->prepare("SELECT * FROM users where id=".$value['user_id']);
                        $userStmt->execute();
                        $userResult = $userStmt->fetch(PDO::FETCH_ASSOC);

                        $productStmt = $connection->prepare("SELECT * FROM products where id=".$value['product_id']);
                        $productStmt->execute();
                        $productResult = $productStmt->fetch(PDO::FETCH_ASSOC);
                  ?>
                  <tr>
                      <td><?php echo $i?></td> 
                      <td><?php echo escape($userResult['name'])?></td>
                      <td><?php echo escape($productResult['name'])?></td>
                      <td><?php echo escape($value['total_price'])?></td>
                      <td>
                        <div class="btn-group">
                          <div class="container">
                            <a href="order_detail.php?id=<?php echo $value['id']?>" type="button" class="btn btn-primary" >View</a>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <!-- php code fore each end -->
                  <?php 
                  $i++;
                }
                }?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              
            </div>
          <!-- /.col-md-6 --> 
          <nav aria-label="Page navigation example" style="float: right;">
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="?pageNo=1" >First</a> </li>
                  <li class="page-item <?php if($pageNo <= 1){ echo 'disabled';}?>">
                    <a class="page-link" href="<?php  
                    if($pageNo <= 1){
                      echo "#";
                    }else {
                      echo "?pageNo=".($pageNo-1);
                    }?>" >Previous</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#" ><?php echo $pageNo;?></a></li>
                  <li class="page-item <?php if($pageNo == $totalPages) {
                    echo "disabled";
                    }?>"><a class="page-link" href="<?php 
                    if($pageNo>= $totalPages){
                      echo "#";
                    }else{
                      echo "?pageNo=".($pageNo+1);
                    }
                    ?>" >Next</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="?pageNo=<?php echo $totalPages?>" >Last</a></li>
                </ul>
              </nav>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
   
<?php include ('footer.html')?>
  