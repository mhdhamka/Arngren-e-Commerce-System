<?php
	include "dbConnection_arngren.php";	
	session_start();
		//if user is not logged//
		if (!isset($_SESSION["userID"])) {
			header("Location : index.php");
		}
		include 'logoutUser.php';

		if (isset($_POST["save"])) {
			$fullName = mysqli_real_escape_string($conn,$_POST["fullName"]);
			$oldpassword = mysqli_real_escape_string($conn,md5($_POST["oldpassword"]));
			$newpassword = mysqli_real_escape_string($conn,md5($_POST["newpassword"]));
			$cpassword = mysqli_real_escape_string($conn,md5($_POST["cpassword"]));
			$email = mysqli_real_escape_string($conn,$_POST["email"]);

			$number = "/[0-9]/";
			$namevalid = "/^[A-Z]+[a-z]*$/";
			$passwordvalid = "/^(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])(?=(.*\d){6})(?!.* )/";	
			
			global $conn;

			if (!preg_match("/^([A-Z]){1}([a-z]){1,}$/", trim($fullName))&&isset($_POST["fullName"])) {
            	$fullNameError = "*First Character must be Uppercase and Follow by Lowercase!*";
            	}
            	else{ 
            		if(preg_match($number,trim($fullName))) {
					$fullNameError = "*First Name cannot be Numbers!*";
            		}
            		else{
            			$sql = "UPDATE user SET fullName = '$fullName' WHERE userID = '{$_SESSION["userID"]}'";
            			$result = mysqli_query($conn,$sql);
            			if ($result) {
            				echo "<script>alert('Name has been updated successfully.');</script>";
            			}
							else {
								echo "<script>alert('Name has not updated successfully.');</script>";
								echo $conn->error;
							}
            			}
            		

            	if (!preg_match($_row['password'], $oldpassword)&&isset($_POST["password"])) {
            		$passwordError = "*Old password is not matched*";

            		if (!preg_match($passwordvalid, $password)) {
					$password2Error = "*Password must ontain ONE upppercase, ONE lowercase,\nONE special character, numbers and no space!*";

						if ($password2 != $cpassword) {
						$password3Error = "*Confirm Password does not match!*";
							} else{
									$sql = "UPDATE user SET phonenumber = '$phonenumber' WHERE userID = '{$_SESSION["userID"]}'";
			            			$result = mysqli_query($conn,$sql);
			            			if ($result) {
		            				echo "<script>alert('Password has been updated successfully');</script>";
		            				}
										else{
											echo "<script>alert('Password has not updated successfully');</script>";
											echo $conn->error;
											}
            						}

							}
						}


				if (!filter_var($email, FILTER_VALIDATE_EMAIL)&&isset($_POST["email"])) {
                $emailError = "*Invalid email Format!*";
            	}
            		else{
            			$sql = "UPDATE user SET email = '$email' WHERE userID = '{$_SESSION["userID"]}'";
            			$result = mysqli_query($conn,$sql);
            			if ($result) {
            				echo "<script>alert('Email has been updated successfully');</script>";
            			}
							else{
								echo "<script>alert('Email has not updated successfully');</script>";
								echo $conn->error;
							}
            			}
            	}
				
					
	}
?>
<!DOCTYPE HTML>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<title>Arngren | Edit Profile</title>
	<style type="text/css">
		*{
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		h1{
			position: absolute;
			left: 300px;
			top: 15px;
			font-size: 55px;
			color: white;
			font-family: Arial black;
		}

		.centernav{
			position: absolute;
			left: 300px;
			top: 25px;
			font-size: 14.5px;
			color: white;
			font-family: Helvetica;
		}

		.centernav ul li {
			display: inline-block;
			list-style-type: none;
		}

		.centernav a.active {
		  color: #ecd846;
		}

		.centernav a:hover:not(.active) {
		  color: #ecd846;
		}

		.topnav{
			display: flex;
			align-items: top;
			padding: 10px;
		}

		.topnav nav{
			flex: 1;
			position: absolute;
			right: 150px;
			text-align: right;
		}

		.topnav nav ul{
			display: inline-block;
			list-style-type: none;
		}

		.topnav li{
			color: white;
		}

		.topnav nav ul li{
			display: inline-block;
			margin-right: 20px;
		}

		.topnav a{
			text-decoration: none;
			font-family: Helvetica;
			color: white;
		}

		.headercontainer{
			max-width: 1300px;
			margin: auto;
			padding-left: 25px;
			padding-right: 25px;
		}

		.header{
			/*background-image: linear-gradient(#7a3835, #c45b56, #db7772);*/
			background: #c45b56;
		}


		.footer{
			text-align: center;
			font-family: Helvetica;
			color: lightgrey;
			font-size: 12px;
			border-top: 2px solid lightgrey;
			margin: 40px;
			padding: 20px;

		}

		

		.search{
			height: 50px;
			width: 850px;
			padding: 10px;
			margin-left: 200;
			margin-top: 30px;
			border: none;
			border-radius: 25px;
			outline: none;
		}

		.searchbutton{
			position: absolute;
			right: 400px;
			top: 90px;
			bottom: 50px;
			width: 100px;
			height: 40.5px;
			border: none;
			outline: none;
			padding: 10px;
			border-radius: 25px;
			background: #ecd846;
			font-size: 15px;
			text-align: center;
			font-family: Helvetica;
			color: #c45b56;
		}
			
		.searchbar input {
			position: absolute;
			right: 394px;
			top: 55px;
			bottom: 50px;
			border: black;
			font-size: 17px;
		}

		.cart{
			position: absolute;
			right: 300px;
			top: 95px;
		}

		.message{
			position: absolute;
			right: 250px;
			top: 97px;
		}

		.info{
			position: absolute;
			right:  200px;
			top: 97px;
		}
			
		.bottomnav {
		  width: 100%;
		  background-color: #c45b56;
		  border: white;
		  overflow: auto;
		}

		.bottomnav a {
		  float: left;
		  padding: 10px;
		  padding-left: 5px;
		  padding-right: 5px;
		  color: white;
		  border-top: 3px solid #ecd846;
		  /*border-bottom: 2px solid #ecd846;*/
		  text-decoration: none;
		  font-size: 15px;
		  width: 13.45%;
		  text-align: center;
		  font-family: Helvetica;
		}

		.bottomnav img{
		  float: left;
		  size: 10px;
		  padding: 10px;
		  color: white;
		  border-top: 3px solid #ecd846;
		  /*border-bottom: 2px solid #ecd846;*/
		  /*background: #7a3835;*/
		  width: 2.9%;
		}

		.bottomnav a.active {
		  background-color: #ecd846;
		  color: #c45b56;
		 
		}

		.bottomnav a:hover:not(.active) {
		  background-color: #ecd846;
		  color: #c45b56;
		 
		}

		.bottomnav img:hover:not(.active) {
		  background-color: #7a3835;
		}

		.content{
			background-color: rgba(245,245,245,255);
			padding: 20px;
		}

		.container{
			padding: 10px;
			background-color: white;
			width: 60%;
			margin-left: auto;
			margin-right:auto;
			padding: 20px;
			border: 3px solid rgb(237,237,237);
			font-family: Helvetica;
		}

		.header2 h2 {
			font-family: Helvetica;
			font-size: 25px;
		}

		.header2 p{
			font-size: 14px;
			color: rgb(157,157,157);
			padding: 3px;
		}

		.form{
			font-family: Helvetica;
		}

		.innerform{
			padding: 15px;
		}

		.innerform label{
				display: inline ;
				padding-left: 20px;
				font-size: 14px;
				color: rgb(157,157,157);

		}

		.innerform input{
				width: 10cm;
				border-radius: 10px;
				display: inline;
				font-size: 12px;
				padding: 10px ;
				position: absolute;
				right: 625px;
		}

		.formfooter input{
			width: 1.6cm;
			height: 0.9cm;
			background-color: rgb(238,77,45);
			color: white;
			border: 0px;
			margin-left: 180px;
			margin-top: 20px;
		}
	</style>
	<link rel="icon" type="image/x-icon" href="arngrenlogo.PNG">
</head>

<body>
	<div class = "header">
		<div class = "headercontainer">
			<div class = "topnav">
			<h1>www.ARNGREN.net</h1>
				<div class = "centernav">
					<ul>
						<li><a class="index.php" href="">Home</a></li>
						<li>|</li>
						<li><a class="productList.php" href="">Products</a></li>
						<li>|</li>
						<li><a>About</a></li>
					</ul>
				</div>
				
				<div class = "logo">
					<img src = "arngrenlogo.PNG" width = "125px">
				</div>
				
				<div class = "searchbar">
					<input type = "text" class = "search" placeholder = "Search for products..">
					<div class = "searchbutton">
						<p>Search</p> 
					</div>
					<div class = "cart">
	                  <i style = "color: white" class = "fa fa-shopping-cart fa-2x"></i>
                    </div>
                    <div class = "message">
	                  <i style = "color: white" class = "fa fa-envelope fa-2x"></i>
                    </div>
                    <div class = "info">
	                  <i style = "color: white" class = "fa fa-gear fa-2x"></i>
                    </div>
				</div>
				
				<nav>
					<ul>
						<?php
						   global $conn;
						   $sql = "SELECT fullName FROM user WHERE logStatus = 1;";
						   $result = mysqli_query($conn, $sql);
						
						   if ($result -> num_rows > 0)
						   {
							  while ($row = $result -> fetch_assoc())
							  {
								?>
									<li><a href = "editprofile.php">My Profile</a></li>
									<li>|</li>
									<li><a href = "logoutUser.php">Log Out</a></li>
									<?php
							  }
							}
							else
							{
								?>
									<li><a href = "registration.php">Sign Up</a></li>
									<li>|</li>
									<li><a href = "chooseLogin.php">Log In</a></li>
								<?php
							}
							?>
					</ul>
				</nav>
			</div>
		</div>
		
		<div class = "dashboard">
			<div class = "bottomnav">
				<img src = "arrowleft.png">
				<a>Scooter</a> 
				<a>Jeep</a> 
				<a>Electric Vehicles</a>
				<a>DVD-Player</a>
				<a>Go-Kart</a> 
				<a>Hobby & RC</a> 
				<a>Binoculars</a>
				<img src = "arrowright.png">
			</div>
		</div>
	</div>

	<div class="content">
		<div class="container">
			<div class="header2">
				<h2>My Profile</h2>
				<p>Account Setting</p>
				<hr>
			</div>
			<form class="form" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
				<?php
					global $conn;
					$sql = "SELECT * FROM user WHERE userID = 1014";
					$result = mysqli_query($conn,$sql);
					if (mysqli_num_rows($result)>0) {
						while  ($row = mysqli_fetch_assoc($result)){
				?>
				<div class="innerform">
					<label>Full Name</label>
					<input type="text" placeholder="<? php echo $_row['fullName'] ?>" id="name">
					<small class="error"> <?php echo $fullNameError;?></small>
				</div>
				<div class="innerform">
					<label>Email</label>
					<input type="email" placeholder="<? php echo $_row['email'] ?>" id="email">
					<small class="error"> <?php echo $emailError;?></small>
				</div>
				<div class="innerform">
					<label>Current Password</label>
					<input type="password" placeholder="" id="password">
					<small class="error"> <?php echo $passwordError;?></small>
				</div>
				<div class="innerform">
					<label>New Password</label>
					<input type="password" placeholder="" id="newpassword">
					<small class="error"> <?php echo $password2Error;?></small>
				</div>
				<div class="innerform">
					<label>Confirm Password</label>
					<input type="password" placeholder="" id="confirmpassword">
					<small class="error"> <?php echo $password3Error;?></small>
				</div>
				<?php 
						}
					}
				?>
				<div class="formfooter">
					<input type="submit" value="Save" class="save">
				</div>
			</form>
			</div>
		
	</div>
	
	

	<div class = "footer">
		<p>&copy; 2021 ARNGREN. ALL RIGHTS RESERVED</p>
	</div>

</body>
</html>