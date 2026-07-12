<?php include "dbConnection_arngren.php";

	if(isset($_POST['addProduct'])){
		$productName = $_POST['productName'];
		$productQty = $_POST['productQty'];
		$productPrice = $_POST['productPrice'];

		addProduct($productName, $productQty, $productPrice);
	}

?>

<!DOCTYPE HTML>
<html lang = "en">

  <!---G03-Lowkey Mockup code for main page--->
  <!---1. Mohammad Hamka Izzuddin Bin Mohamad Yahya (73571)--->
  <!---2. Harith Zakwan Bin Zakaria (73484)------------------->
  <!---3. Iman Tarmizi Rosalina (73496)----------------------->
  <!---4. Lai Weng Hong (75351)------------------------------->

<head>
	<meta charset = "UTF-8">
	<title>Arngren | Home</title>

	<style>
		*{
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: Helvetica;
		}
		
		body {
			overflow-x: hidden;
		}
		
		table tr th {
			background-color: #7a3835;
		}
		
		.sidebarcontainer {
			position: relative;
			width: 100%;
		}
		
		.sidebar {
			position: fixed;
			width: 325px;
			height: 100%;
			background: #c45b56;
			transition: 0.5s;
			overflow: hidden;
		}
		
		.sidebar a.active {
			background:  #ecd846;
			color: #ecd846;
		}
		
		.sidebar ul li a.active .icon .fa {
			color: #c45b56;
			font-size: 24px;
		}
		
		.sidebar ul li a.active .title {
			position: relative;
			display: block;
			padding: 0 10px;
			height: 60px;
			line-height: 60px;
			color: #c45b56;
			white-space: nowrap;
		}
		
		
		.sidebar ul {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
		}
		
		.sidebar ul li {
			position: relative;
			width: 100%;
			list-style: none;
		}

		
		.sidebar ul li:hover {
			background: #7a3835;
		}
		
		.sidebar ul li:nth-child(1) {
			margin-bottom: 10px;
		}
		
		.sidebar ul li:nth-child(1):hover {
			background: transparent;
		}
		
		.sidebar ul li a {
			position: relative;
			display: block;
			width: 100%;
			display: flex;
			text-decoration: none;
		}
		
		.sidebar ul li a .icon { 
			position: relative;
			display: block;
			min-width: 60px;
			height: 60px;
			line-height: 60px;
			text-align: center;
		}
		
		.sidebar ul li a .icon .fa {
			color: #ecd846;
			font-size: 24px;
		}
		
		.sidebar ul li a .title {
			position: relative;
			display: block;
			padding: 0 10px;
			height: 60px;
			line-height: 60px;
			color: #ecd846;
			white-space: nowrap;
		}
		
		.main {
			position: absolute;
			width: calc(100% - 325px);
			left: 325px;
			min-height: 100vh;
			background: lightgrey;
		}
		
		.main .topbar {
			width: 100%;
			background: white;
			height: 60px;
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 20px;
		}
		
		.main .topbar i {
			font-size: 25px;
		}
		
		.main .topbar small {
			visibility: visible;
			padding: 10px;
		}
		
		.main .display-products {
			padding: 20px;
		}
		
		.main .display-products ul {
			list-style: none;
			padding: 0 0 20px 0;
		}
		
		.main .display-products a {
			text-decoration: none;
			color: #c45b56; 
		}
		
		.main .display-products .addbutton {
			padding: 20px 0 0 0;
		}
		
		.main .display-products .addbutton button {
			padding: 5px;
			border-radius: 5px;
			border: 2px solid #ecd846;
			background: #c45b56;
		}
		
		.main .display-products .addbutton button a {
			color: #ecd846;
		}
		
		.main table {
			width: 100%;
			border: 1px solid black;
		}
		
		.main thead, tbody, th, tr {
			border: 1px solid black;
			background: #c45b56; 
			color: #ecd846;
		}
	</style>
	
	<script src="https://use.fontawesome.com/59805f286a.js"></script>

	<!---favicon--->
	<link rel="icon" type="image/x-icon" href="arngrenlogo.PNG">
</head>

<body>
	<div class = "sidebarcontainer">
		<div class = "sidebar" id = "sidebar">
			<ul>
				<li>
					<a href = "">
						<span class = "icon"><img src = "arngrenlogo.PNG" width = "50px"></span>
						<span class = "title"><h2>www.ARNGREN.net</h2></span>
					</a>
				</li>
				<li>
					<a href = "DashboardAccounts.php">
						<span class = "icon"><i class = "fa fa-users"></i></span>
						<span class = "title">Accounts</span>
					</a>
				</li>
				<li>
					<a class = "active" href = "DashboardProducts.php">
						<span class = "icon"><i class = "fa fa-shopping-cart"></i></span>
						<span class = "title">Products</span>
					</a>
				</li>
				<li>
					<a href = "DashboardTransactionRecord.php">
						<span class = "icon"><i class = "fa fa-bar-chart"></i></span>
						<span class = "title">Transaction Record</span>
					</a>
				</li>
				<li>
					<a href = "DashboardTransactionReport.php">
						<span class = "icon"><i class = "fa fa-print"></i></span>
						<span class = "title">Transaction Report</span>
					</a>
				</li>
				<li>
					<a href = "logoutAdmin.php">
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
			
			<div class = "display-products">
				<ul>
					<li>
						<a href = "DashboardProducts.php" style = "font-weight: bold;">Products</a>
					</li>
				</ul>
				
				<table>
					<thead>
						<tr>
							<th scope = "col">Product ID</th>
							<th scope = "col">Name</th>
							<th scope = "col">Image</th>
							<th scope = "col">Quantity</th>
							<th scope = "col">Price</th>
							<th scope = "col">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							global $conn;
							$sql = "SELECT * FROM product";
							$result = mysqli_query($conn, $sql);

							if ($result -> num_rows > 0)
							{
								while ($row = $result -> fetch_assoc())
								{
									echo "<tr style = 'text-align: center;'>
											<td>".$row['productID']."</td>
											<td>".$row['productName']."</td>
											<td><img src = '".$row['productIMG']."' style = 'width: 100px;'></td>
											<td>".$row['productQty']."</td>
											<td>".$row['productPrice']."</td>
											<td>
												<button><a href=\"updateProduct.php?updateID=$row[productID]\">Update</a></button>
												<button><a href=\"deleteProduct.php?deleteID=$row[productID]\">Delete</a></button>
											</td>
										 </tr>";
								}
							}
						?>
					</tbody>
				</table>
				
				<div class = "addbutton">
					<button><a href = "AddProduct.php">Add Product</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>