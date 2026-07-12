<?php
	include ("../config/db_carngren.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Arngren | Log In</title>
	<link rel="icon" type="image/x-icon" href="../../assets/images/logo.PNG">
	<link rel="stylesheet" href="../../assets/css/auth.css">
	
</head>
<body>
	<div class="header">
		<div class="logo">
			<img src = "../../assets/images/logo.PNG" alt="logo" >
		</div>
		<div class="logotext">
			<h1>www.Arngren.net</h1>
		</div>
		<div class="logintext"> 
			<h1>Log In</h1>
		</div>
	</div>
 	<div class="context">
		<div class="contextimg">
			<figure>
			<img src = "../../assets/images/logo.PNG" alt="arngenlogo">
			<figcaption><h3>www.Arngren.net</h3>Appliances and Gadgets Online Shopping Platform</figcaption>
			</figure>
		</div>
		<div class="container">
			<div class="formheader">
				<h3>Log In</h3>
			</div>
			<div class = "form">
				<br>
				<button><a href="../auth/loginUser.php">Log In as User</a></button>
				<br>
				<button><a href="../auth/loginAdmin.php">Log In as Admin</a></button>
				<br>
			</div>
		</div>
	</div>
	<div class="footer">
		<br>
		<hr>
		<p>&copy; 2021 ARNGREN. ALL RIGHTS RESERVED</p>
	</div>
</body>
</html>