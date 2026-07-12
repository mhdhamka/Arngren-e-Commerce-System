<?php include "dbConnection_arngren.php";

	if(isset($_POST['addProduct']))
	{
		$productName = $_POST['productName'];
		$productQty = $_POST['productQty'];
		$productPrice = $_POST['productPrice'];
		$productIMG = $_POST['productIMG'];

		addProduct($productName, $productQty, $productPrice, $productIMG);
	}
?>

<!DOCTYPE HTML>
<html lang = "en">

  <!---code for main page--->

<head>
	<meta charset = "UTF-8">
	<title>Arngren | Add Product</title>
	<script  type="text/javascript" src="AddProduct.js"></script>

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
		
		.main .form-control {
			padding: 20px;
		}
		
		.main ul {
			list-style-type: none;
			overflow: hidden;
			padding: 20px 20px 0 20px;
		}
		
		.main ul li {
			float: left;
			padding-right: 10px;
			color: #c45b56;
		}
		
		.main ul li a {
			display: block;
			color: #c45b56;
			text-decoration: none;
		}
		
		.main ul li p {
			display: block;
			color: #c45b56;
			text-decoration: none;
		}
		
		.form-control {
			padding-bottom: 20px;
		}
		
		.form-control i {
			color: #c45b56;
		}
		
		.form-control label {
			display: inline-block;
			margin-bottom: 5px;
		}
		
		.form-control input[type=text], input[type=number], input[type=float]{
			border: 2px solid darkgrey;
			border-radius: 5px;
			display: block;
			padding: 10px;
			width: 50%;
		}
		
		.form-control small{
			visibility: hidden;
		}
		
		.form-control input[type=submit] {
			background-color: #c45b56;
			padding: 5px;
			border-radius: 4px;
			border: 2px solid #7a3835;
			color:  #ecd846;
			width: 10%;
		}
		
		.form-control.error small{
			visibility: visible;
			color: #c45b56;
		}
		
		.form-control.success small{
			visibility: hidden;
		}
		
		.form-control.error input{
			border-color: #c45b56;
		}
		
		.form-control.success input{
			border-color: #ecd846;
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

			<ul>
				<li>
					<a href = "DashboardProducts.php">Products</a>
				</li>
				<li>
					<p> >> </p>
				</li>
				<li>
					<a href = "AddProduct.php" style = "font-weight: bold;">Add Product</a>
				</li>
			</ul>
			<form method = "POST" enctype="multipart/form-data">

				<div class = "form-control">
					<i class="fa fa-tag"></i>
					<label>Name: </label>
					<input type = "text" name = "productName" required>
					<small>Invalid</small>
				</div>

				<div class = "form-control">
					<i class="fa fa-sort"></i>
					<label>Quantity: </label>
					<input type = "number" name = "productQty" required>
					<small>Invalid</small>
				</div>

				<div class = "form-control">
					<i class="fa fa-dollar"></i>
					<label>Price: </label>
					<input type = "float" name = "productPrice" required>
					<small>Invalid</small>
				</div>
				
				<div class = "form-control">
					<i class = "fa fa-file-image-o"></i>
					<label>Image: </label>
					<input type = "text" name = "productIMG" placeholder = "Please enter the image path" required>
					<span style = "color: #c45b56;">*ONLY WEB IMAGES IN .jpg, .jpeg, .png AND .gif ARE ACCEPTED</span>
					<small>Invalid</small>
				</div>

				<div class = "form-control">
					<input type = "submit" name = "addProduct" value = "Submit">
				</div>
			</form>
		</div>
	</div>

	<script type = "text/JavaScript">
		const form = document.getElementById('form');
		const productName = document.getElementById('productName');
		const productQty = document.getElementById('productQty');
		const productPrice = document.getElementById('productPrice');

		form.addEventListener('submit', (e) => {
			e.preventDefault();
			validateInputs();
		});

		function sample() {
		  alert('Hello from formValidation.js!')
		}

		function validateInputs() {
			const productNameValue = productName.value.trim();
			const producQtyValue = productQty.value.trim();
			const productPriceValue = productPrice.value.trim();

	
			if (productNameValue === '' || productNameValue === null)
			{
				setInvalidFor(productName, 'Product name cannot be blank.');
			}
			else
			{
				setValidFor(productName);		
			}
			
			if (productQtyValue === '')
			{
				setInvalidFor(productQty, 'Quantity cannot be blank.');
			}
			else if (productQtyValue <= 0)
			{
				setInvalidFor(productQty, 'Quantity must be more than 0.');
			}
			else
			{
				setValidFor(productQty);
			}
			
			if (productPriceValue === '')
			{
				setInvalidFor(productPrice, 'Price cannot be blank.');
			}
			else if (productPriceValue.length <= 0)
			{
				setInvalidFor(productPrice, 'Price must be more than 0.');
				
			}
			else
			{
				setValidFor(productPrice);
			}
		}

		function setInvalidFor(input, message) {
			const formControl = input.parentElement;
			const small = formControl.querySelector('small');
			
			small.innerText = message;
			
			formControl.className = 'form-control error';
		}

		function setValidFor(input) {
			const formControl = input.parentElement;
			formControl.className = 'form-control success';
		}
	</script>
	
</body>
</html>