<?php 
session_start();
require '../config/common.php';
require '../config/config.php';

if(empty($_SESSION['user_id'])&& empty($_SESSION['logged_in'])){
    header('Location:login.php');
}
if($_SESSION['role']!=1){
    header('Location:login.php');
}

if($_POST){
    if(!hash_equals($_SESSION['_token'],$_POST['token'])) die();
    if(empty($_POST['name'])|| empty($_POST['description'])|| empty($_POST['quantity'])
    || empty($_POST['price'] || empty($_POST['category']))){

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
    else if((is_numeric($_POST['quantity']))!=1 || (is_numeric($_POST['price']))!=1){
        $priceError = (is_numeric($_POST['quantity']))? '':'Please Enter valid price';
        $quantityError = (is_numeric($_POST['price']))? '':'Please Enter valid quantity';
    }
    }else{
        unset($_SESSION['_token']);//That is for token deleting token and refreshing the token
        $id = $_POST["hiddenId"];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];
        $categoryId =$_POST['category'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        // print '<pre>';

        // echo $name.$description.$categoryId.$quantity.$price;

        if($_FILES['image']['name']){
            $target_dir = "dist/img/";//creating target dir
            $target_file = $target_dir.basename($_FILES['image']['name']);
            $image_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
            if($image_type!="png" && $image_type!="jpg" && $image_type!="jpeg"){
                echo "<script>alert('we don't support your image type')</script>";
            }else{
               move_uploaded_file($_FILES['image']['tmp_name'],$target_file);
               $image = $_FILES['image']['name'];
               $stmt = $connection-> prepare("UPDATE products SET name ='$name',description='$description',quantity='$quantity',category_id='$categoryId',price='$price',image='$image' WHERE id = '$id'");
               $result = $stmt->execute();
               if($result){
                   echo "<script>alert('successfully updated');window.location.href = 'index.php'</script> ";
               }
            }
        }else{
            $stmt = $connection-> prepare("UPDATE products SET name=:name,description=:description,quantity=:quantity,category_id=:category_Id,price=:price WHERE id =:id");
            $result = $stmt->execute(
                array(
                    ':name'=>$name,
                    ':description'=>$description,
                    ':quantity'=>$quantity,
                    ':category_Id'=>$categoryId,
                    ':price'=>$price,
                    ':id'=>$id
                )
            );
            // print '<pre>';
            // print_r($result);
   
            if($result){
                echo "<script>alert('successfully updated');window.location.href = 'index.php'</script> ";    
            }
        }
    }
}
$stmt = $connection->prepare("SELECT * FROM products where id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
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
                            <input type="hidden" name="hiddenId" value="<?php echo $result['id'] ?>">
                            <div class="form-group">
                                <label for="">Name</label><p style="color:red;"><?php echo empty($nameError)? '':'*'.$nameError?></p>
                                <input type="text" class="form-control" name="name" value="<?php echo escape($result['name']);?>">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label><p style="color:red;"><?php echo empty($descriptionError)? '':'*'.$descriptionError?></p>
                                <input type="text" class="form-control" name="description" value="<?php echo escape($result['description']);?>" >
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
                                     <option value="<?php echo $value['id']?>" <?php echo ($value['id']==$result['category_id'])? 'selected':''?>><?php echo $value['name']?></option>
                                    <?php
                                    }
                                    ?>  
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Quantity</label><p style="color:red;"><?php echo empty($quantityError)? '':'*'.$quantityError?></p>
                                <input type="number" class="form-control" name="quantity" value="<?php echo escape($result['quantity']);?>" >
                            </div>
                            <div class="form-group">
                                <label for="">Price</label><p style="color:red;"><?php echo empty($priceError)? '':'*'.$priceError?></p>
                                <input type="number" class="form-control" name="price" value="<?php echo escape($result['price']);?>" >
                            </div>
                            <div class="form-group">
                                <label for="">Image</label><p style="color:red;"><?php echo empty($imageError)? '':'*'.$imageError?></p>
                                <img src="dist/img/<?php echo $result['image'] ?>" alt="this is a photo" width="250px" height="175px"><br><br>
                                <input type="file" name="image" value="" >
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="Submit ">
                                <a href="index.php" type="button" class="btn btn-primary">Back</a> 
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
       </div>
<?php include ('footer.html')?>
   