<?php
	include ("../../config/db_carngren.php");

	$deleteID = $_GET['deleteID'];
	global $conn;
	$sql = "DELETE FROM product WHERE productID = $deleteID";
	$result = mysqli_query($conn, $sql);

	header("Location:DashboardProducts.php");
?>