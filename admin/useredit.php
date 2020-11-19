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
   
        if(empty($_POST['name'])){
            $nameError = "Name can not be null";
        }
        if(empty($_POST['email'])){
            $emailError = "Email can not be null";
        }
        $passwordError = (empty($_POST['password']))? "Password should not be null ":"password length should be greater than 4"; 
    }else{
        unset($_SESSION['_token']);//That is for token deleting token and refreshing the token
        $id = $_POST["hiddenId"];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password =password_hash( $_POST['password'], PASSWORD_DEFAULT);
        $role = empty($_POST['role'])? '0':'1' ;
        $stmt = $connection-> prepare("UPDATE users SET name=:name,email=:email,password=:password,role=:role WHERE id =:id");
        $result = $stmt->execute(
            array(
                ':name'=>$name,
                ':email'=>$email,
                ':password' => $password,
                ':role' =>$role,
                ':id'=>$id
               )
            );
        if($result){
            echo "<script>alert('successfully updated');window.location.href = 'userview.php'</script> ";    
        }
    }
}  
    $stmt = $connection-> prepare("SELECT * FROM users WHERE id = ".$_GET['id']);
    $stmt->execute();
    $result = $stmt->fetchAll();
    //    print "<pre>";
    //    print_r($result[0]['image']);

?>
<?php include ('header.php');?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" class="" method="POST" enctype="multipart/form-data">
                            <input name="token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                            <input type="hidden" name="hiddenId" value="<?php echo $result[0]['id'] ?>">
                            <div class="form-group">
                                <label for="">Name</label><p style="color:red;"><?php echo empty($nameError)? '':'*'.$nameError?></p>
                                <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name'] )?>" required>
                            </div>
                            <div class="form-group"><p style="color:red;"><?php echo empty($emailError)? '':'*'.$emailError?></p>
                                <label for="">Email</label><p style="color:red">  
                                <input type="text" name="email" class="form-control" value="<?php echo escape($result[0]['email'])?>">
                            </div>
                            <div class="form-group"><p style="color:red;"><?php echo empty($passwordError)? '':'*'.$passwordError?></p>
                                <label for="">Password</label><p style="color:red"> 
                                <span style="font-size: 10px;">*This user already had an password</span>  
                                <input type="text" name="password" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="check">Admin</label>
                                <input type="checkbox" name="role" value="1" id="check" <?php echo $result[0]['role']==1? 'checked':''?>>
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
  