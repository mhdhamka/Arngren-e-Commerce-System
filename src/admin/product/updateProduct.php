<?php
	include ("../../config/db_carngren.php");


	if(isset($_POST['updateAccount']))
	{	
		$productName = ($_POST['productName']);
		$productQty = ($_POST['productQty']);	
		$productPrice = ($_POST['productPrice']);	
		$productIMG = $_POST['productIMG'];
		
		// checking empty fields
		if(empty($productName) || empty($productQty) || empty($productPrice))
		{			
			if(empty($productName))
			{
				echo "<font color='red'>Product Name field is empty.</font><br/>";
			}
			if(empty($productQty))
			{
				echo "<font color='red'>Product Quantity field is empty.</font><br/>";
			}		
			if(empty($productPrice))
			{
				echo "<font color='red'>Product Price field is empty.</font><br/>";
			}		
		}
		else
		{	
			//updating the table
			$updateID = $_GET['updateID'];
			global $conn;
			$sql = "UPDATE product SET productName = '$productName', productQty = '$productQty', productPrice = '$productPrice' productIMG = '$productIMG'
					WHERE productID = $updateID";
			$result = mysqli_query($conn, $sql);
			//redirectig to the display page. In our case, it is index.php
			header("Location:DashboardProducts.php");
		}
	}
?>

<!DOCTYPE HTML>
<html lang = "en">

<head>
	<meta charset = "UTF-8">
	<title>Arngren | Home</title>

	<link rel="stylesheet" href="../../../assets/css/updateProduct.css">
	
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
					<a href = "../../admin/account/dashboard.php">
						<span class = "icon"><i class = "fa fa-users"></i></span>
						<span class = "title">Accounts</span>
					</a>
				</li>
				<li>
					<a class = "active" href = "../../admin/product/product.php">
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
					<a href = "../../admin/product/product.php">Products</a>
				</li>
				<li>
					<p> >> </p>
				</li>
				<li>
					<a style = "font-weight: bold;">Update Product</a>
				</li>
			</ul>
			
			<form method = "POST">
				<div class = "form-control">
					<i class = "fa fa-tag"></i>
					<label>Name: </label>
					<input type = "text"
						   name = "productName"
						   id = "productName"
						   placeholder = "<?php $updateID = $_GET['updateID'];
												global $conn;
												$sql = "SELECT productName FROM product WHERE productID = $updateID;";
												$result = mysqli_query($conn, $sql);
												
												while ($row = $result -> fetch_assoc())
												{
													echo $row["productName"];
												}
										  ?>">
					<small>Invalid</small>
				</div>

				<div class = "form-control">
					<i class = "fa fa-sort"></i>
					<label>Quantity: </label>
					<input type = "number"
						   name = "productQty"
						   id = "productQty"
						   placeholder = "<?php $updateID = $_GET['updateID'];
												global $conn;
												$sql = "SELECT productQty FROM product WHERE productID = $updateID;";
												$result = mysqli_query($conn, $sql);
												
												while ($row = $result -> fetch_assoc())
												{
													echo $row["productQty"];
												}
										  ?>">
					<small>Invalid</small>
				</div>

				<div class = "form-control">
					<i class = "fa fa-dollar"></i>
					<label>Price: </label>
					<input type = "float"
						   name = "productPrice"
						   id = "productPrice"
						   placeholder = "<?php $updateID = $_GET['updateID'];
												global $conn;
												$sql = "SELECT productPrice FROM product WHERE productID = $updateID;";
												$result = mysqli_query($conn, $sql);
												
												while ($row = $result -> fetch_assoc())
												{
													echo $row["productPrice"];
												}
										  ?>">
					<small>Invalid</small>
				</div>
				
				<div class = "form-control">
					<i class = "fa fa-file-image-o"></i>
					<label>Image: </label>
					<input type = "text" name = "productIMG" placeholder = "<?php $updateID = $_GET['updateID'];
												global $conn;
												$sql = "SELECT productIMG FROM product WHERE productID = $updateID;";
												$result = mysqli_query($conn, $sql);
												
												while ($row = $result -> fetch_assoc())
												{
													echo $row["productIMG"];
												}
										  ?>">
					<span style = "color: #c45b56;">*ONLY WEB IMAGES IN .jpg, .jpeg, .png AND .gif ARE ACCEPTED</span>
					<small>Invalid</small>
				</div>

				<div class = "form-control">
					<input type = "submit" name = "updateAccount" value = "Update"></input>
				</div>
			</form>
		</div>
	</div>
	
	<script src="../../../assets/js/updateProduct.js"></script>
</body>
</html>