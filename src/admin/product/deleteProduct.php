<?php
	include("dbConnection_arngren.php");

	$deleteID = $_GET['deleteID'];
	global $conn;
	$sql = "DELETE FROM product WHERE productID = $deleteID";
	$result = mysqli_query($conn, $sql);

	header("Location:DashboardProducts.php");
?>