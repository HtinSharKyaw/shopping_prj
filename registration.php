<?php 
session_start();
require 'config/config.php';
require 'config/common.php';
if($_POST){
	if(empty($_POST['email'])||empty($_POST['name'])||empty($_POST['phone'])||
	empty($_POST['address'])||empty($_POST['password']) || strlen($_POST['password'])<4 ){
		if(empty($_POST['email'])){
			$emailError = "Email should be entered";
		}
		if(empty($_POST['name'])){
			$nameError = "Name should be entered";
		}
		if(empty($_POST['phone'])){
			$phoneError = "Phone Number should be entered";
		}
		if(empty($_POST['address'])){
			$addressError = "address should be entered";
		}
		$passwordError = (empty($_POST['password']))? "password should be entered":"Weak Password length";	
	}else{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password =password_hash($_POST['password'],PASSWORD_DEFAULT) ;
		$phone = $_POST['phone'];
		$address = $_POST['address'];
		$role = 0;

		$stmt = $connection->prepare("SELECT * FROM users where email=:email");
		$stmt->execute(array(':email'=>$email));
		$isUser = $stmt->fetch(PDO::FETCH_ASSOC); 		
		if($isUser){
			echo "<script>alert('This email is used');</script>";
		}else{
			$stmt = $connection->prepare("INSERT INTO users(name,email,password,phone,address,role) VALUES (:name,:email,:password,:phone,:address,:role)");
			$stmt->execute(
				array(
					':name'=>$name,
					':email'=>$email,
					':password'=>$password,
					':address'=>$address,
					':phone'=>$phone,
					':role'=>$role
				)
				);
			$newUser = $stmt->fetch(PDO::FETCH_ASSOC);
			if($newUser){
				unset($_SESSION['_token']);
				echo "<script>alert('successfully registered');
					window.location.href('login.php');
				</script>";
			}
		
		}
	}
}

?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>Shopping App | Registration</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.html">
						<h4>Shopping App</h4>
					</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse"
						data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
						aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse offset" id="navbarSupportedContent">
						<ul class="nav navbar-nav menu_nav ml-auto">
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<!-- <li class="nav-item"><a href="#" class="cart"><span class="ti-bag"></span></a></li>
							<li class="nav-item">
								<button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
							</li> -->
						</ul>
					</div>
				</div>
			</nav>
		</div>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between">
					<input type="text" class="form-control" id="search_input" placeholder="Search Here">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Register</h1>
					<nav class="d-flex align-items-center">
						<a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="category.html">Register</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="login_box_img">
						<img class="img-fluid" src="img/login.jpg" alt="">
						<div class="hover">
							<h4>Time to explore new products</h4>
							<p>Are you interested in shopping and exploring the best products of our websites</p>
							<a class="primary-btn" href="login.php">Login</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner">
						<h3>Create a new account</h3>
						<form class="row login_form" action="registration.php" method="post" id="contactForm"
							novalidate="novalidate">
							<input type="hidden" name="token" value="<?php echo $_SESSION['_token'];?>">
							<div class="col-md-12 form-group">
								
								<input type="email" class="form-control" id="email" name="email" placeholder="<?php if($_POST){echo empty($_POST['email'])? $emailError:$_POST['email'];}else{echo "Email";} ?>"
									onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'" value="<?php if($_POST){if(!empty($_POST['email']))$_POST['email'];} ?>">
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="name" name="name" placeholder="<?php if($_POST){echo empty($_POST['name'])? $nameError:$_POST['name'];}else{echo "Name";} ?>"
									onfocus="this.placeholder = ''" onblur="this.placeholder = 'Name'" value="<?php if($_POST){if(!empty($_POST['name']))$_POST['name'];} ?>">
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="phone" name="phone" placeholder="<?php if($_POST){echo empty($_POST['phone'])? $phoneError:$_POST['phone'];}else{echo "Phone";}?>"
									onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone'" value="<?php if($_POST){if(!empty($_POST['phone']))$_POST['phone'];} ?>">
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="address" name="address" placeholder="<?php if($_POST){echo empty($_POST['address'])? $addressError:$_POST['address'];}else{echo "Address";}?>"
									onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'" value="<?php if($_POST){if(!empty($_POST['address']))$_POST['address'];} ?>">
							</div>
							<div class="col-md-12 form-group">
								<input type="password" class="form-control" id="password" name="password" placeholder="<?php if($_POST){echo empty($_POST['password'])? $addressError:$_POST['password'];}else{echo "Password";}?>"
									onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" value="<?php if($_POST){if(!empty($_POST['password']))$_POST['password'];} ?>">
							</div>
							<div class="col-md-12 form-group">
								<!-- <div class="creat_account">
									<input type="checkbox" id="f-option2" name="selector">
									<label for="f-option2">Keep me logged in</label>
								</div> -->
							</div>
							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="primary-btn">Register</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

	<!-- start footer Area -->
	<footer class="footer-area section_gap">
		<div class="container">
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
				<p class="footer-text m-0">
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
					Copyright &copy;<script>
						document.write(new Date().getFullYear());
					</script>
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				</p>
			</div>
		</div>
	</footer>
	<!-- End footer Area -->


	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
		integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
	</script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>