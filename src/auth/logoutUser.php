<?php
	include ("dbConnection_arngren.php");
	session_destroy();
	global $conn;
	$sql = "UPDATE user SET logStatus = 0 WHERE logStatus = 1";
	$result = mysqli_query($conn, $sql);
	header("location: chooseLogin.php");
?>