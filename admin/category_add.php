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

if($_POST){
    //start this code is for preventing csrf attack
    if (!hash_equals($_SESSION['_token'], $_POST['token'])) die();
    //end

    if(empty($_POST['name'])|| empty($_POST['description'])){
    //     echo "Hello error";
        if(empty($_POST['name'])){
            $nameError = "Input Category name";
        }
        if(empty($_POST['description'])){
            $descriptionError = "Please enter description";
        }
    }else{
        unset($_SESSION['_token']);//That is for token deleting token and refreshing the token
        $name = $_POST['name'];
        $description = $_POST['description'];
            
        $stmt = $connection->prepare("INSERT INTO categories(name,description) VALUES (:name,:description);");
        $result = $stmt->execute(
            array(
                ':name'=>$name,
                ':description'=>$description
                )
            );
        if($result){
            echo "<script>alert('successfully added');
                window.location.href = 'category.php';
            </script>";
        }           
    }
}
?>
<?php include ('header.php');?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="#" class="" method="POST" enctype="multipart/form-data">
                            <input name="token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                            <div class="form-group">
                                <label for="">Name</label><p style="color:red;"><?php echo empty($nameError)? '':'*'.$nameError?></p>
                                <input type="text" class="form-control" name="name" value="<?php echo empty($_POST['name'])? '':$_POST['name']?>">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label><p style="color:red;"><?php echo empty($descriptionError)? '':'*'.$descriptionError?></p>
                                <input type="text" class="form-control" name="description" value="<?php echo empty($_POST['description'])? '':$_POST['description']?>" >
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="Submit ">
                                <a href="category.php" type="button" class="btn btn-primary">Back</a> 
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
       </div>
<?php include ('footer.html')?>
   