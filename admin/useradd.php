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
    if (!hash_equals($_SESSION['_token'], $_POST['token'])) die();
    if(empty($_POST['name'])|| empty($_POST['email']) || empty($_POST['password']) || strlen(($_POST['password']))<=4){
    //     echo "Hello error";
        if(empty($_POST['name'])){
            $nameError = "Name can not be null";
        }
        if(empty($_POST['email'])){
            $emailError = "Email can not be null";
        }
        $passwordError = (empty($_POST['password']))? "Password should not be null ":"password length should be greater than 4"; 
    }else{
        unset($_SESSION['_token']);//That is for token deleting token and refreshing the token
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT) ;
        $role = empty($_POST['role'])? 0:1;
            
        $stmt = $connection->prepare("INSERT INTO users(name,password,email,role) VALUES (:name,:password,:email,:role)");
        $result = $stmt->execute(
            array(
                ':name' => $name,
                ':password' => $password,
                ':email' => $email,
                ':role' => $role
                )
            );
        if($result){
            echo "<script>alert('successfully data added')
                window.location.href = 'userview.php';
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
                                <input type="text" class="form-control" name="name" value="" >
                            </div>
                            <div class="form-group">
                                <label for="">Email</label><p style="color:red;"><?php echo empty($emailError)? '':'*'.$emailError?></p>
                                <input type="text" class="form-control" name="email" value="" >
                            </div>
                            <div class="form-group">
                                <label for="">Password</label><p style="color:red;"><?php echo empty($passwordError)? '':'*'.$passwordError?></p>
                                <input type="password" class="form-control" name="password" value="" >
                            </div>
                            <div class="form-group">
                                <label for="check">Admin</label>
                                <input type="checkbox" name="role" value="1" id="check">
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="Submit ">
                                <a href="userview.php" type="button" class="btn btn-primary">Back</a> 
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
       </div>
<?php include ('footer.html')?>
   