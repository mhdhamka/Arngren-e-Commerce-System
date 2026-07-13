<?php include ("../../config/db_carngren.php");

	if(isset($_POST['insert'])){
    $orderID = $_POST['orderID'];
	$userID = $_POST['userID'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $orderDate = $_POST['orderDate'];
    $orderTime = $_POST['orderTime'];
    $productName = $_POST['productName'];
    $Qty = $_POST['Qty'];
	$total = $_POST['total'];
	$address = $_POST['address'];
    $state = $_POST['state'];
	$city = $_POST['city'];
    $zip = $_POST['zip'];
		
        


		makePayment($orderID, $userID, $fullname, $email, $orderDate, $orderTime, $productName, $Qty, $total, $address, $state, $city, $zip);
	}
?>

<!DOCTYPE HTML>
<html lang = "en">

<head>
	<meta charset = "UTF-8">
	<title>Arngren | Home</title>

	<link rel="stylesheet" href="../../../assets/css/record.css">
	
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
					<a href = "../../admin/account/dashboard.php">
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
					<a class = "active" href = "../../admin/transaction/record.php">
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
			<div class="table-container" style = "padding: 20px;">
				<h1 class="heading">Transaction Records</h1>
				<table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Order Date</th>
                    <th>Order Time</th>
                    <th>Product Quantity</th>
                    <th>Product Name</th>
                    <th>Total Price(excl. 6% Tax)</th>
                    <th>Customer Address</th>
                    <th>State/County/District</th>
                    <th>City</th>
                    <th>Zip</th>
                </tr>
            </thead>
			<tbody>
			<?php

			displayRecord();

			?>

			</tbody>
            <?php
				displayRecord();
            ?>
        </table>
			</div>
			
		
		</div>
	</div>
</body>
</html>