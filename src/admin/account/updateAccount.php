<?php
	include ("../../config/db_carngren.php");

	if(isset($_POST['updateAccount']))
	{	
		$fullName = ($_POST['fullName']);
		$email = ($_POST['email']);
		$password = ($_POST['password']);	
		
		// checking empty fields
		if(empty($fullName) || empty($email) || empty($password))
		{			
			if(empty($fullName))
			{
				echo "<font color='red'>Name field is empty.</font><br/>";
			}
			if(empty($email))
			{
				echo "<font color='red'>Email field is empty.</font><br/>";
			}
			if(empty($password))
			{
				echo "<font color='red'>Password field is empty.</font><br/>";
			}		
		}
		else
		{	
			//updating the table
			$updateID = $_GET['updateID'];
			global $conn;
			$sql = "UPDATE user SET fullName = '$fullName', email = '$email', password ='$password' WHERE userID = $updateID";
			$result = mysqli_query($conn, $sql);
			//redirectig to the display page. In our case, it is index.php
			header("Location:DashboardAccounts.php");
		}
	}
?>

<!DOCTYPE HTML>
<html lang = "en">

<!---code for admin page--->

<head>
	<meta charset = "UTF-8">
	<title>Arngren | Home</title>

	<link rel="stylesheet" href="../../../assets/css/updateAccount.css">
	
	<script src="https://use.fontawesome.com/59805f286a.js"></script>

	<!---favicon--->
	<link rel="icon" type="image/x-icon" href="../../../assets/images/logo.PNG">
</head>

<body>
	<div class = "container">
		<div class = "sidebar" id = "sidebar">
			<ul>
				<li>
					<a href = "">
						<span class = "icon"><img src = "../../../assets/images/logo.PNG" width = "50px"></span>
						<span class = "title"><h2>www.ARNGREN.net</h2></span>
					</a>
				</li>
				<li>
					<a class = "active" href = "../../admin/account/dashboard.php">
						<span class = "icon"><i class = "fa fa-users"></i></span>
						<span class = "title">Accounts</span>
					</a>
				</li>
				<li>
					<a href = "../../admin/product/product.php">
						<span class = "icon"><i class = "fa fa-shopping-cart"></i></span>
						<span class = "title">Products</span>
					</a>
				</li>
				<li>
					<a href = "../../admin/transaction/record.php">
						<span class = "icon"><i class = "fa fa-bar-chart"></i></span>
						<span class = "title">Transaction Record</span>
					</a>
				</li>
				<li>
					<a href = "../../admin/transaction/report.php">
						<span class = "icon"><i class = "fa fa-print"></i></span>
						<span class = "title">Transaction Report</span>
					</a>
				</li>
				<li>
					<a href = "../../auth/logout.php">
						<span class = "icon"><i class = "fa fa-sign-out"></i></span>
						<span class = "title">Log Out</span>
					</a>
				</li>
			</ul>
		</div>
		
		<div class = "main">
			<div class = "topbar">
				<div class = "admin">
					<i style = "color: #c45b56" class = "fa fa-user-circle"></i>
					<small>
						<?php
							global $conn;
							$sql = "SELECT adminUsername FROM admin WHERE logStatus = 1;";
							$result = mysqli_query($conn, $sql);
							
							if ($result -> num_rows > 0)
							{
								while ($row = $result -> fetch_assoc())
								{
									echo $row["adminUsername"];
								}
							}
						?>							
					</small>
				</div>
			</div>
			
			<ul>
				<li>
					<a href = "../../admin/account/dashboard.php">Accounts</a>
				</li>
				<li>
					<p> >> </p>
				</li>
				<li>
					<a style = "font-weight: bold;">Update Account</a>
				</li>
			</ul>
			
			<form method = "POST">
				<div class = "form-control">
					<i class="fa fa-user-circle"></i>
					<label>Name: </label>
					<input type = "text"
						   name = "fullName"
						   id = "fullName"
						   placeholder = "<?php $updateID = $_GET['updateID'];
												global $conn;
												$sql = "SELECT fullName FROM user WHERE userID = $updateID;";
												$result = mysqli_query($conn, $sql);
												
												while ($row = $result -> fetch_assoc())
												{
													echo $row["fullName"];
												}
										  ?>">
					<small>Invalid</small>
				</div>
				<div class = "form-control">
					<i class="fa fa-envelope"></i>
					<label>Email: </label>
					<input type = "email"
						   name = "email"
						   id = "email"
						   placeholder = "<?php $updateID = $_GET['updateID'];
												global $conn;
												$sql = "SELECT email FROM user WHERE userID = $updateID;";
												$result = mysqli_query($conn, $sql);
												
												while ($row = $result -> fetch_assoc())
												{
													echo $row["email"];
												}
										  ?>">
					<small>Invalid</small>
				</div>
				<div class = "form-control">
					<i class="fa fa-lock"></i>
					<label>Password: </label>
					<input type = "password"
						   name = "password"
						   id = "password"
						   placeholder = "<?php $updateID = $_GET['updateID'];
												global $conn;
												$sql = "SELECT password FROM user WHERE userID = $updateID;";
												$result = mysqli_query($conn, $sql);
												
												while ($row = $result -> fetch_assoc())
												{
													echo $row["password"];
												}
										  ?>">
					<small>Invalid</small>
				</div>
				<div class = "form-control">
					<input type = "submit" name = "../../admin/account/updateAccount.php" value = "Update"></input>
				</div>
			</form>
		</div>
	</div>
	
	<script src="../../../assets/js/updateAccount.js"></script>
</body>
</html>