<?php
	include ("../../config/db_carngren.php");

	$deleteID = $_GET['deleteID'];
	global $conn;
	$sql = "DELETE FROM user WHERE userID = $deleteID";
	$result = mysqli_query($conn, $sql);

	header("Location:../../admin/account/dashboard.php");
?>