<?php
	include ("../config/db_carngren.php");

	if (isset($_POST['submit']))
	{
		global $conn;
		$loginid = $_POST['loginid'];
		$password = $_POST['password'];

		$sql = "UPDATE user SET logStatus = 1 WHERE email = '$loginid' AND password = '$password'";
		$result = mysqli_query($conn, $sql);
		
		$sql = "SELECT * FROM user WHERE email = '$loginid' AND password = '$password'";
		$result = mysqli_query($conn, $sql);

		if ($result !== false && $result->num_rows > 0)
		{
			while ($row = $result -> fetch_assoc())
			{
				session_start();
				echo "<script>alert('You have successfully Log In');</script>";
				//direct to registed member homepage
				header("Location: index.php");
			}
		}
		else
		{
			echo "<script>alert('Invalid Login ID or Password');</script>";
		}
	}
	
	if (isset($_POST['guest']))
	{
		global $conn;
		session_start();
		header("Location: index.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Arngren | Log In</title>
	<link rel="icon" type="image/x-icon" href="../../assets/images/logo.PNG">
	<link rel="stylesheet" href="../../assets/css/loginUser.css">
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
			<h1><span style = "color: #ecd846;">User </span>Log In</h1>
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
				<h3><span style = "color: #c45b56;">User </span>Log In</h3>
			</div>
			<form class="form" method="POST" action="loginUser.php">
				<br>
				<input type="" placeholder="Email" id="loginid" name="loginid" required>
				<br>
				<input type="password" placeholder="Password" id="password" name="password" required>
				<br>
				<input type="submit" value="LOG IN" class="submit" name="submit">
				<br>
			</form>
			<form class="form" method="POST">
				<input type="submit" value="CONTINUE AS GUEST" class="submit" name="guest">
			</form>
			<br>
			<button style = "background-color: #ecd846;"><a href="../auth/login.php">CHANGE USER TYPE</a></button>
			<br>
			<hr>
			
			<div class="formfooter">
				<p>New to www.Arngren.net?
				<a href="registration.php">Sign Up</a>
				</p>
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