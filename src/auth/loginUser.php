<?php

	include("../config/db_carngren.php");
	session_start();

	if(isset($_POST['submit']))
	{
		$loginid = mysqli_real_escape_string($conn,$_POST['loginid']);
		$password = $_POST['password'];

		$sql="
		SELECT *
		FROM user
		WHERE email='$loginid'
		";

		$result=mysqli_query($conn,$sql);

		if(mysqli_num_rows($result)==1)
		{
			$row=mysqli_fetch_assoc($result);
			// Verify hashed password
			if(password_verify($password,$row['password']))
			{
				$_SESSION['userID']=$row['userID'];
				$_SESSION['fullName']=$row['fullName'];
				$_SESSION['email']=$row['email'];
				$_SESSION['logStatus']=1;

				mysqli_query($conn,"
					UPDATE user
					SET logStatus=1
					WHERE userID='{$row['userID']}'
				");

				echo "
				<script>
				alert('You have successfully logged in');
				</script>
				";

				header("Location: ../auth/index.php");
				exit();
			}
			else
			{
				echo "
				<script>
				alert('Invalid Password');
				</script>
				";
			}
		}
		else
		{
			echo "
			<script>
			alert('Account does not exist');
			</script>
			";

		}

	}


	if(isset($_POST['guest']))
	{
		header("Location:../auth/index.php");
		exit();
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