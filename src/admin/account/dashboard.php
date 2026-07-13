<?php include ("../../config/db_carngren.php");

	if(isset($_POST['addAccount'])){
		$fullName = $_POST['fullName'];
		$email = $_POST['email'];
		$password = $_POST['password'];

		insert_records($fullName, $email, $password);
	}

?>

<!DOCTYPE HTML>
<html lang = "en">

<head>
	<meta charset = "UTF-8">
	<title>Arngren | Home</title>

	<link rel="stylesheet" href="../../../assets/css/dashboard.css">
	
	<script src="https://use.fontawesome.com/59805f286a.js"></script>

	<!---favicon--->
	<link rel="icon" type="image/x-icon" href="../../../assets/images/logo.PNG">
</head>

<body>
	<div class = "sidebarcontainer">
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
						
			<div class = "display-accounts">
				<ul>
					<li>
						<a href = "../../admin/account/dashboard.php" style = "font-weight: bold;">Accounts</a>
					</li>
				</ul>
				
				<table>
					<thead>
						<tr>
							<th scope = "col">User ID</th>
							<th scope = "col">Username</th>
							<th scope = "col">Email</th>
							<th scope = "col">Password</th>
							<th scope = "col">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							global $conn;
							$sql = "SELECT * FROM user";
							$result = mysqli_query($conn, $sql);

							if ($result -> num_rows > 0)
							{
								while ($row = $result -> fetch_assoc())
								{
									echo "<tr style = 'text-align: center;'>
											<td>".$row['userID']."</td>
											<td>".$row['fullName']."</td>
											<td>".$row['email']."</td>
											<td>".$row['password']."</td>
											<td>
												<button><a href=\"../../admin/account/updateAccount.php?updateID=$row[userID]\">Update</a></button>
												<button><a href=\"../../admin/account/deleteAccount.php?deleteID=$row[userID]\">Delete</a></button>
											</td>
										 </tr>";
								}
							}
						?>
					</tbody>
				</table>
				
				<div class = "addbutton">
					<button><a href = "../../admin/account/addAccount.php">Add Account</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>