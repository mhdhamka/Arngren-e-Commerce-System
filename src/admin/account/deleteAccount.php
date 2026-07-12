<?php
	include("dbConnection_arngren.php");

	$deleteID = $_GET['deleteID'];
	global $conn;
	$sql = "DELETE FROM user WHERE userID = $deleteID";
	$result = mysqli_query($conn, $sql);

	header("Location:DashboardAccounts.php");
?>