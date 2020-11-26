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

    if(empty($_POST['name'])|| empty($_POST['description'])|| empty($_POST['quantity'])
    || empty($_POST['price'] || empty($_FILES['image']) || empty($_POST['category']))){
    //     echo "Hello error";
        if(empty($_POST['name'])){
            $nameError = "Input Product name";
        }
        if(empty($_POST['description'])){
            $descriptionError = "Please enter description";
        }
        if(empty($_POST['price'])){
            $priceError = "Please enter price";
        }      
        if(empty($_POST['quantity'])){
            $quantityError = "Please enter quantity";
        }  
        if(empty($_FILES['image']['name'])){
            $imageError = "image can not be null";
        }  
    }elseif(is_numeric($_POST['price'])!=1 || is_numeric($_POST['quantity']) != 1){
        $priceError =  is_numeric($_POST['price'])? '':'Please Enter Valid Price';
        $quantityError = is_numeric($_POST['quantity'])? '':'Please Enter Valid Quantity';
    }else{
        unset($_SESSION['_token']);//That is for token deleting token and refreshing the token

        $target_dir = "dist/img/";//creating a target dir
        $target_file = $target_dir.basename($_FILES["image"]["name"]);//get the path of the file to be upload
        $image_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));//hold the file extension in lower case

        if($image_type!="png" && $image_type!="jpg" && $image_type!="jpeg"){
            echo "<script>alert('we don't support your image type')</script>";
        }else{
            move_uploaded_file($_FILES['image']['tmp_name'],$target_file);
             $name = $_POST['name'];
             $description = $_POST['description'];
             $image = $_FILES['image']['name'];
             $categoryId =$_POST['category'];
             $quantity = $_POST['quantity'];
             $price = $_POST['price'];

             $stmt = $connection->prepare("INSERT INTO products(name,description,category_id,quantity,price,image) VALUES 
                    (:name,:description,:categoryId,:quantity,:price,:image);");
             $result = $stmt->execute(
                 array(
                     ':name'=>$name,
                     ':description'=>$description,
                     ':categoryId'=>$categoryId,
                     ':quantity'=>$quantity,
                     ':price'=>$price,
                     ':image'=>$image
                     )
                 );
             if($result){
                 echo "<script>alert('successfully added');
                     window.location.href = 'index.php';
                 </script>";
             }           
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
                            <?php 
                            // $optionStmt = $connection->prepare("SELECT DISTINCT id from categories");
                            //Writing the sub query in order to get category with unique id
                            $optionStmt = $connection->prepare("SELECT * FROM categories where id IN (SELECT DISTINCT id from categories)");
                            $optionStmt->execute();
                            $optionResult = $optionStmt->fetchAll();
                            ?>
                            <div class="form-group">
                                <label for="category">Category</label><p style="color:red;"><?php echo empty($categoryError)? '':'*'.$categoryError?></p>
                                <select name="category" id="category" class="form-control">
                                    <?php 
                                    foreach($optionResult as $value){
                                    ?>
                                     <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                                    <?php
                                    }
                                    ?>
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Quantity</label><p style="color:red;"><?php echo empty($quantityError)? '':'*'.$quantityError?></p>
                                <input type="number" class="form-control" name="quantity" value="<?php echo empty($_POST['quantity'])? '':$_POST['quantity']?>" >
                            </div>
                            <div class="form-group">
                                <label for="">Price</label><p style="color:red;"><?php echo empty($priceError)? '':'*'.$priceError?></p>
                                <input type="number" class="form-control" name="price" value="<?php echo empty($_POST['price'])? '':$_POST['price']?>" >
                            </div>
                            <div class="form-group">
                                <label for="">Image</label><p style="color:red;"><?php echo empty($imageError)? '':'*'.$imageError?></p>
                                <input type="file" name="image" value="" >
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
   