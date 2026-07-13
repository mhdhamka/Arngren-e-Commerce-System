<?php

    session_start();
	include ("../config/db_carngren.php");
		
		if (!isset($_SESSION["userID"])) {
			header("Location : index.php");
			exit();
		}
		

		if(isset($_POST['save']))
		{
			$userID = $_SESSION['userID'];

			$fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
			$email = mysqli_real_escape_string($conn, $_POST['email']);

			$currentPassword = $_POST['currentpassword'];
			$newPassword = $_POST['newpassword'];
			$confirmPassword = $_POST['confirmpassword'];

			// Update Name & Email
			mysqli_query($conn,"
				UPDATE user
				SET
					fullName='$fullName',
					email='$email'
				WHERE userID='$userID'
			");

			// Update session so header shows latest name
			$_SESSION['fullName'] = $fullName;
			$_SESSION['email'] = $email;

			// Update password only if user entered something
			if(!empty($currentPassword))
			{
				$result = mysqli_query($conn,"
					SELECT password
					FROM user
					WHERE userID='$userID'
				");

				$user = mysqli_fetch_assoc($result);

				// If your database stores MD5 passwords
				if(password_verify($currentPassword,$user['password']))
				{
					if($newPassword == $confirmPassword)
					{
						$newPassword = password_hash($newPassword,PASSWORD_DEFAULT);

						mysqli_query($conn,"
							UPDATE user
							SET password='$newPassword'
							WHERE userID='$userID'
						");

						echo "<script>alert('Profile and password updated successfully.');</script>";
					}
					else
					{
						echo "<script>alert('New password and confirm password do not match.');</script>";
					}
				}
				else
				{
					echo "<script>alert('Current password is incorrect.');</script>";
				}
			}
			else
			{
				echo "<script>alert('Profile updated successfully.');</script>";
			}
		}
?>

<!DOCTYPE HTML>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<title>Arngren | Edit Profile</title>
	<link rel="stylesheet" href="../../assets/css/profile.css">
	<link rel="icon" type="image/x-icon" href="../../assets/images/logo.PNG">
</head>

<body>
	<div class = "header">
		<div class = "headercontainer">
			<div class = "topnav">
			<h1>www.ARNGREN.net</h1>
				<div class = "centernav">
					<ul>
						<li><a href="../auth/index.php">Home</a></li>
						<li>|</li>
						<li><a href="../user/productList.php">Products</a></li>
						<li>|</li>
						<li><a href="../user/aboutUs.php">About Us</a></li>
					</ul>
				</div>
				
				<div class = "logo">
					<a class = "active" href = "../auth/index.php">
					    <img src = "../../assets/images/logo.PNG" width = "125px">
                    </a>
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

					<?php if(isset($_SESSION['userID'])) { ?>

					    <li>
							<span>
								Welcome,
								<?php echo $_SESSION['fullName']; ?>
							</span>
						</li>
						<li>
							<a href="../user/profile.php">
								My Profile
							</a>
						</li>
						<li>|</li>
						<li>
							<a href="../auth/logout.php">
								Log Out
							</a>
						</li>

					<?php } else { ?>

						<li>
							<a href="../auth/registration.php">
								Sign Up
							</a>
						</li>
						<li>|</li>
						<li>
							<a href="../auth/login.php">
								Log In
							</a>
						</li>

					<?php } ?>

					</ul>
				</nav>
			</div>
		</div>
		
		<div class = "dashboard">
			<div class="bottomnav-container">
				<button>
				    <i style="color: white" class="fa fa-chevron-left"></i>
				</button>

                <div class="bottomnav" id="categoryNav">
					<?php
					$categories = [
						"Scooter",
						"Jeep",
						"Electric Vehicles",
						"DVD-Player",
						"Go-Kart",
						"Hobby & RC",
						"Binoculars"
					];

					$currentCategory = isset($_GET['category']) ? $_GET['category'] : "";

					foreach($categories as $category)
					{
						$active = ($currentCategory == $category) ? "active" : "";

						echo '
						<a class="'.$active.'" href="productList.php?category='.urlencode($category).'">
							'.$category.'
						</a>';
					}

					?>
				</div>
				<button>
				    <i style="color: white" class="fa fa-chevron-right"></i>
				</button>
			</div>
		</div>
	</div>

	<div class="content">
		<div class="container">
			<div class="header2">
				<h2>My Profile</h2>
				<p>Bla bla bla</p>
				<hr>
			</div>
			<form method="POST" action="">
				<?php
				$userID = $_SESSION['userID'];

				$sql = "SELECT * FROM user WHERE userID='$userID'";
				$result = mysqli_query($conn,$sql);
				$user = mysqli_fetch_assoc($result);
				?>

				<div class="innerform">
					<label>Full Name</label>
					<input type="text" name="fullName" value="<?php echo htmlspecialchars($user['fullName']); ?>">
                    <small class="error">
						<?php echo $fullNameError ?? ''; ?>
					</small>
				</div>

				<div class="innerform">
					<label>Email</label>
					<input
						type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
					<small class="error">
						<?php echo $emailError ?? ''; ?>
					</small>
				</div>

				<br>
				<hr>
				<br>

				<h3>Change Password</h3>

				<div class="innerform">
					<label>Current Password</label>
					<input type="password" name="currentpassword">
					<small class="error">
						<?php echo $passwordError ?? ''; ?>
					</small>
				</div>

				<div class="innerform">
					<label>New Password</label>
					<input type="password" name="newpassword">
					<small class="error">
						<?php echo $password2Error ?? ''; ?>
					</small>
				</div>

				<div class="innerform">
					<label>Confirm Password</label>
					<input type="password" name="confirmpassword">
					<small class="error">
						<?php echo $password3Error ?? ''; ?>
					</small>
				</div>

				<div class="formfooter">
					<input type="submit" name="save" value="Save" class="save">
				</div>
				</form>
			</div>

	    </div>
	
	<div class = "footer">
		<p>&copy; 2021 ARNGREN. ALL RIGHTS RESERVED</p>
	</div>

</body>
</html>