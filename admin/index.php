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
                <h3 class="card-title">Products</h3>
              </div>
              <!-- php code for getting data -->
              <?php 
                $stmt = $connection->prepare("SELECT * FROM products ORDER BY id DESC");
                $stmt->execute();
                $rawResult = $stmt->fetchAll();

                if($_GET){
                  $pageNo = $_GET['pageNo'];
                }else{
                  $pageNo = 1;
                }
                $numberOfRecords = 5;
                $offset = ($pageNo - 1)*$numberOfRecords;
                
                if($_POST){//this line solve the problem of unknown searchName error in first landing page after login
                  $searchKey = $_POST['searchName'];
                  $stmt = $connection->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                  $totalPages = ceil(count($result)/$numberOfRecords);
                }else{
                  $totalPages = ceil(count($rawResult)/$numberOfRecords);
                  $stmt = $connection->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numberOfRecords ");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
                
              ?>

              <!-- /.card-header -->
              <div class="card-body">
                <div class="">
                    <a href="add.php" type="button" class="btn btn-success">New Product</a>
                </div><br>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">id</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Category</th>
                      <th>In Stock</th>
                      <th>price</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!--php code-> for each start-->
                  <?php if($result) { 
                    $i = 1;
                    foreach($result as $value){
                  ?>
                  <tr>
                      <td><?php echo $i?></td> 
                      <td><?php echo escape($value['name'])?></td>
                      <td><?php echo substr(escape($value['description']),0,50)?></td><!--This is substring function for too much content-->
                      <td><?php 
                        $nameStmt = $connection->prepare("SELECT * FROM categories WHERE id = ".$value['category_id']);
                        $nameStmt->execute();
                        $nameResult = $nameStmt->fetch(PDO::FETCH_ASSOC);
                        if($nameResult){
                          echo escape($nameResult['name']);
                        }
                      ?></td>
                      <td><?php echo escape($value['quantity'])?></td>
                      <td><?php echo escape($value['price'])?></td>
                      <td>
                        <div class="btn-group">
                          <div class="container">
                            <a href="edit.php?id=<?php echo $value['id']?>" type="button" class="btn btn-primary" >edit</a>
                          </div>
                          <div class="container">
                            <a href="delete.php?id=<?php echo $value['id']?>" type="button" class="btn btn-warning" onclick="return confirm('Are you sure you want to delete')">Delete</a> 
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
  