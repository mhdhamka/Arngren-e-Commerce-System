<?php
	$hostName = "localhost";
	$username = "p1_admin";
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

	function addAccount($fullName, $email, $password)
	{
		global $conn;
		$sql = "INSERT INTO user(fullName, email, password)
				VALUES('$fullName', '$email', '$password')";

		if (mysqli_query($conn, $sql))
		{   
			$success = false;	    
		}
		else
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	
	function addProduct($productName, $productQty, $productPrice, $productIMG)
	{
		global $conn;
		$sql = "INSERT INTO product(productName, productQty, productPrice, productIMG)
				VALUES('$productName', '$productQty', '$productPrice', '$productIMG')";

		if (mysqli_query($conn, $sql))
		{   
			$success = false;	    
		}
		else
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	
	function makePayment($orderID, $userID, $fullname, $email, $orderDate, $orderTime, $Qty, $productName, $total, $address, $state, $city, $zip) {
		global $conn;
		$sql = "INSERT INTO `transaction`(`orderID`,`userID`, `fullname`, `email`, `orderDate`, `orderTime`, `Qty`, `productName`, `total`, `address`, `state`, `city`, `zip`)
				VALUES('$orderID','NULL', '$fullname','$email','$orderDate','$orderTime', '$Qty', '$productName','$total','$address','$state', '$city', '$zip')";
		if (mysqli_query($conn, $sql)){
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	
	function displayRecord()
	{
		global $conn;
		$sql = "SELECT * FROM `transaction`";
		$result = mysqli_query($conn, $sql);

		if ($result !== false && $result->num_rows > 0)
		{
			while ($row = $result -> fetch_assoc())
			{
				echo "<tr>
						<td>".$row['orderID']."</td>
						<td>".$row['userID']."</td>
						<td>".$row['fullname']."</td>
						<td>".$row['email']."</td>
						<td>".$row['orderDate']."</td>
						<td>".$row['orderTime']."</td>
						<td>".$row['Qty']."</td>
						<td>".$row['productName']."</td>
						<td>".$row['total']."</td>
						<td>".$row['address']."</td>
						<td>".$row['state']."</td>
						<td>".$row['city']."</td>
						<td>".$row['zip']."</td>
					 </tr>";
			}
		}
	}
	
	/*function makePayment($total, $address, $state, $city, $zip){
		global $conn;
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$orderDate = date("Y-m-d");
		$orderTime = date("H:i:s");
		
		$sql = "INSERT INTO transaction(userID)
				SELECT userID FROM user
				WHERE logStatus = 1";
				
		if (mysqli_query($conn, $sql)) {
			$sql = "UPDATE transaction (orderDate, orderTime, total, address, state, city, zip)
					SET orderDate = '$orderDate',
						orderTime = '$orderTime',
						total = '$total',
						address = '$address',
						state = '$state',
						city = '$city',
						zip = '$zip'
					WHERE userID = 1014";
			if (mysqli_query($conn, $sql))
			{
				header("location: receipt.php");
			}
			
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}*/
	
	/*function performPayment($address, $state, $city, $zip)
	{
		global $conn;
		$sql = "INSERT INTO transaction(userID)
				SELECT userID FROM user
				WHERE logStatus = 1;"
				
				
				
		$sql = "INSERT INTO transaction(subTotal)
				SELECT subTotal FROM cart
				WHERE userID = $userID;"
				
		$sql = "INSERT INTO transaction(address, state, city, zip)
				VALUES('$address', '$state', '$city', '$zip')";
				$orderDate = date("Y-m-d");
				$orderTime = date("H:i:s");  	
		
		$sql = "INSERT INTO transaction(address, state, city, zip)
				VALUES('$address', '$state', '$city', '$zip')";
				$orderDate = date("Y-m-d");
				$orderTime = date("H:i:s");  						
									 
	$sql = "INSERT INTO transaction (userID, loginDateTime)VALUES('$userID', '$loginDateTime')";
	if ($conn->query($sql) === TRUE) {
		echo "New record created successfully";
	  } else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	  }
	}*/
?>