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

        

		function insert_records($orderID, $userID, $fullname, $email, $orderDate, $orderTime, $Qty, $productName, $total, $address, $state, $city, $zip){
			global $conn;
				$sql = "INSERT INTO `transaction`(`orderID`,`userID`, `fullname`, `email`, `orderDate`, `orderTime`, `Qty`, `productName`, `total`, `address`, `state`, `city`, `zip`)
						VALUES('$orderID','NULL', '$fullname','$email','$orderDate','$orderTime', '$Qty', '$productName','$total','$address','$state', '$city', '$zip')";
if (mysqli_query($conn, $sql)) {
} else {
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
	}	


	
        //To display the transaction record from table `transaction` to Transaction Record (ADMIN) page
		function display_data()
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




		




	}
?>