<?php
	$hostName = "localhost";
	$username = "p1-admin";
	$password = "dummy123";
	$database = "db_arngren";

	// Create connection
	$conn = new mysqli($hostName, $username, $password, $database);

	// Check connection
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
	}
	else
	{
				/*
		function display_data()
		{
			global $conn;
			$sql = "SELECT * FROM user";
			$result = mysqli_query($conn, $sql);

			if ($result -> num_rows > 0)
			{
				while ($row = $result -> fetch_assoc())
				{
					echo "<tr>
							<td>".$row['userID']."</td>
							<td>".$row['firstName']."</td>
							<td>".$row['lastName']."</td>
							<td>".$row['email']."</td>
							<td>".$row['mobile']."</td>
							<td>".$row['password']."</td>
							<td>".$row['gender']."</td>
							<td>".$row['state']."</td>
						 </tr>";
				}
			}
		}
		
		function display_log()
		{
			global $conn;
			$sql = "SELECT * FROM myuserlog";
			$result = mysqli_query($conn, $sql);
			
			if ($result -> num_rows > 0)
			{
				while ($row = $result -> fetch_assoc())
				{
					echo "<tr>
							<td>".$row['userID']."</td>
							<td>".$row['loginDateTime']."</td>
							<td>".$row['logoutDateTime']."</td>
							<td>".$row['duration']."</td>
						 </tr>";
				}
			}
		}
		
		function display_join()
		{
			global $conn;
			$sql = "SELECT myuser.userID, myuser.email, myuserlog.loginDateTime, myuserlog.duration
					FROM myuser
					INNER JOIN myuserlog
					ON myuser.userID = myuserlog.userID";
					
			$result = mysqli_query($conn, $sql);
			
			if ($result -> num_rows > 0)
			{
				while ($row = $result -> fetch_assoc())
				{
					echo "<tr>
							<td>".$row['userID']."</td>
							<td>".$row['email']."</td>
							<td>".$row['loginDateTime']."</td>
							<td>".$row['duration']."</td>
						 </tr>";
				}
			}
		}	
		*/		
	}

	function addProduct($productName, $productQty, $productPrice)
	{
		global $conn;
		$sql = "INSERT INTO product(productName, productQty, productPrice)
				VALUES('$productName', '$productQty', '$productPrice')";

		if (mysqli_query($conn, $sql))
		{   
			$success = false;	    
		}
		else
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
?>