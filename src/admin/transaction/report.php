<?php
include("../../config/db_carngren.php");

/* ===============================
   Monthly Sales Report
================================= */

$months = [];
$sales = [];

$sql = "
SELECT
    DATE_FORMAT(transaction.orderDate,'%b') AS monthName,
    MONTH(transaction.orderDate) AS monthNo,
    SUM(transaction.total) AS totalSales
FROM transaction
GROUP BY MONTH(transaction.orderDate)
ORDER BY MONTH(transaction.orderDate)
";

$result = mysqli_query($conn,$sql);

while($row=mysqli_fetch_assoc($result))
{
    $months[] = $row['monthName'];
    $sales[]  = $row['totalSales'];
}


/* ===============================
   Product Sales Report
================================= */

$productNames = [];
$productQty = [];

$sql2 = "
SELECT
    product.productName,
    SUM(cart.orderQty) AS totalQty

FROM transaction

INNER JOIN cart
ON transaction.userID = cart.userID

INNER JOIN product
ON cart.productID = product.productID

GROUP BY product.productID
ORDER BY totalQty DESC
";

$result2 = mysqli_query($conn,$sql2);

while($row=mysqli_fetch_assoc($result2))
{
    $productNames[] = $row['productName'];
    $productQty[]   = $row['totalQty'];
}
?>

<!DOCTYPE HTML>
<html lang = "en">

<head>
	<meta charset = "UTF-8">
	<title>Arngren | Home</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

	<link rel="stylesheet" href="../../../assets/css/report.css">
	
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
					<a href = "../../admin/transaction/record.php">
						<span class = "icon"><i class = "fa fa-bar-chart"></i></span>
						<span class = "title">Transaction Record</span>
					</a>
				</li>
				<li>
					<a class = "active" href = "../../admin/transaction/report.php">
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
			<div class="container">
				<h2 style = "color: #c45b56;"><i class="fa fa-design"></i> Sales Report</h2>

				<div class="dropdowndaily">
					<button class="dropdailybtn">Daily</button>
					<div class="dropdowndaily-content">
						<a href="#">Monday</a>
						<a href="#">Tuesday</a>
						<a href="#">Wednesday</a>
						<a href="#">Thursday</a>
						<a href="#">Friday</a>
						<a href="#">Saturday</a>
						<a href="#">Sunday</a>
					</div>
				</div>

				<div class="dropdownweekly">
					<button class="dropweeklybtn">Weekly</button>
					<div class="dropdownweekly-content">
						<a href="#">Week 1</a>
						<a href="#">Week 2</a>
						<a href="#">Week 3</a>
						<a href="#">Week 4</a>
					</div>
				</div>

				<div class="dropdownmonthly">
					<button class="dropmonthlybtn">Monthly</button>
					<div class="dropdownmonthly-content">
						<a href="#">January</a>
						<a href="#">February</a>
						<a href="#"> March</a>
						<a href="#">April</a>
						<a href="#">May</a>
						<a href="#">June</a>
						<a href="#">July</a>
						<a href="#">August</a>
						<a href="#">September</a>
						<a href="#">October</a>
						<a href="#">November</a>
						<a href="#">December</a>
					</div>
				</div>

				<br>

				<div class="chart">

					<h3>Monthly Sales (KR)</h3>

					<canvas id="salesChart"></canvas>

					<br><br>

					<h3>Most Purchased Products</h3>

					<canvas id="productChart"></canvas>

				</div>
			</div>
		</div>
	</div>

	<script src="../../../assets/js/report.js"></script>
</body>
</html>