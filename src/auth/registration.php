<?php
	include ("../config/db_carngren.php");
	$fullName = $email = $password = $password2 = $fullNameError = $lnameError = $emailError = $phoneError = $passwordError = $password2Error ="";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if (isset($_POST['submit'])) {
		
		$fullName = $_POST['fullName'];
		$email = $_POST['email'];
		/*$phonenumber = $_POST['phonenumber'];*/
		$password = $_POST['password'];
		$password2 = $_POST['password2'];

		$number = "/[0-9]/";
		$namevalid = "/^[A-Z]+[a-z]*$/";
		/*$phonevalid = "/(\+?6?01)[02-46-9]-*[0-9]{7}$|^(\+?6?01)[1]-*[0-9]{8}$/";
		$passwordvalid = "~/^(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])(?=(.*\d){6})(?!.* )/~";*/

		if (ctype_space($fullName)) {
			$fullNameError = "*First Name cannot be Blank!*";
            }
            else{
            	if (!preg_match("/^([A-Z]){1}([a-z]){1,}$/", trim($fullName))) {
            	$fullNameError = "*First Character must be Uppercase and Follow by Lowercase!*";
            	}
            	if (preg_match($number,trim($fullName))) {
				$fullNameError = "*First Name cannot be Numbers!*";
            	}
            }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailError = "*Invalid email Format!*";
            }

        /*if(!preg_match($phonevalid, $phonenumber)){
        	$phoneError = "*Invalid Phone Number!*";
        }
		
		if(!preg_match($passwordvalid, $password)){
        	$passwordError = "*Password must contain ONE upppercase, ONE lowercase,\nONE special character, numbers and no space!*";
        }*/

		if ($password2 != $password) {
			$password2Error = "*Confirm Password does not match!*";
		}

		if (empty($fullNameError)&&empty($lnameError)&&empty($emailError)&&empty($phoneError)&&empty($passwordError)&&empty($password2Error)) {
				
				
				/*if($conn->connect_error){
				echo "$conn->connect_error";
				die("Connection Failed : ". $conn->connect_error);
				} else {*/
				addAccount($fullName, $email, $password);
				$container = "hidden";
				$after = "visible";
				header("location: loginUser.php");
		}
		}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Arngren | Registration</title>

    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.PNG">

    <link rel="stylesheet" href="../../assets/css/registration.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
			<h1>Registration</h1>
		</div>
	</div>
 	<div class="context">
 		<div class="contextimg">
			<figure>
			<img src = "../../assets/images/logo.PNG" alt="arngenlogo">
			<figcaption><h3>www.Arngren.net</h3>Appliances and Gadgets Online Shopping Platform</figcaption>
			</figure>
		</div>
		<div class="container" id="container">
			<div class="formheader">
				<h3>Registration</h3>
			</div>
			<form class="form" id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
				<div class="innerform">
				<input type="text" placeholder="Full Name" id="fullName" name="fullName" required>
				<div class="errorblock">
				<small class="error"> <?php echo $fullNameError;?></small>
				</div>
				</div>		
				<div class="innerform">
				<input type="email" placeholder="Email" id="email" name="email" required>
				<div class="errorblock">
				<small class="error"> <?php echo $emailError;?></small>
				</div>
				</div>
				<div class="innerform">
				<input type="text" placeholder="Password" id="password" name="password" required>
				<div class="errorblock">
				<small class="error"> <?php echo $passwordError;?></small>
				</div>
				</div>
				<div class="innerform">
				<input type="text" placeholder="Confirm Password" id="password2" name="password2" required>
				<div class="errorblock">
				<small class="error"> <?php echo $password2Error;?></small>
				</div>
				</div>
				<div class="footerform">
				<input type="submit" name="submit" value="Sign Up" class="submit">
				<input type="reset" value="Clear" class="reset">
				</div>
			</form>
			
			<div class="formfooter">
				<p>Already have an account?
				<a href="../auth/loginUser.php">Log In</a>
				</p>
			</div>
		</div>
		<div class="after" id="after">
				<div class="afterimg">
					<img src = "greentick.PNG" alt="greentick">
				</div>
				<div class="aftertext">Congratulations, your account has been successfully created.</div>
				<div class="afterinput">
					<input type="button" value="Log In" onclick="location.href='../auth/loginUser.php';">
					<br>
					<input type="button" value="Back to Homepage" onclick="location.href='../auth/index.php';">
				</div>

		</div>
	</div>
	<div class="footer">
		<br>
		<hr>
		<p>&copy; 2021 ARNGREN. ALL RIGHTS RESERVED</p>
	</div>
	<!-- <script src="registration.js"></script>  -->
</body>
</html>
