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

		$sql = "
		INSERT INTO user(fullName,email,password,logStatus)
		VALUES(
			'$fullName',
			'$email',
			'$password',
			0
		)
		";

		if(mysqli_query($conn,$sql))
		{
			return true;
		}
		else
		{
			echo "Error: ".mysqli_error($conn);
			return false;
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

	function displayRecord()
	{
		global $conn;

		$sql = "

		SELECT

		transaction.orderID,
		transaction.userID,

		user.fullName,
		user.email,

		transaction.orderDate,
		transaction.orderTime,

		cart.orderQty,
		product.productName,

		transaction.subTotal,

		transaction.address,
		transaction.state,
		transaction.city,
		transaction.zip


		FROM transaction


		INNER JOIN user

		ON transaction.userID = user.userID


		INNER JOIN cart

		ON transaction.userID = cart.userID


		INNER JOIN product

		ON cart.productID = product.productID

		";


		$result = mysqli_query($conn,$sql);


		if(mysqli_num_rows($result)>0)
		{

			while($row=mysqli_fetch_assoc($result))
			{

	?>

	<tr>

	<td data-label="Order ID">
	<?php echo $row['orderID']; ?>
	</td>


	<td data-label="User ID">
	<?php echo $row['userID']; ?>
	</td>


	<td data-label="Customer Name">
	<?php echo $row['fullName']; ?>
	</td>


	<td data-label="Customer Email">
	<?php echo $row['email']; ?>
	</td>


	<td data-label="Order Date">
	<?php echo $row['orderDate']; ?>
	</td>


	<td data-label="Order Time">
	<?php echo $row['orderTime']; ?>
	</td>


	<td data-label="Product Quantity">
	<?php echo $row['orderQty']; ?>
	</td>


	<td data-label="Product Name">
	<?php echo $row['productName']; ?>
	</td>


	<td data-label="Total Price">
	KR <?php echo number_format($row['subTotal'],2); ?>
	</td>


	<td data-label="Customer Address">
	<?php echo $row['address']; ?>
	</td>


	<td data-label="State">
	<?php echo $row['state']; ?>
	</td>


	<td data-label="City">
	<?php echo $row['city']; ?>
	</td>


	<td data-label="Zip">
	<?php echo $row['zip']; ?>
	</td>


	</tr>


	<?php

			}

		}

		else
		{

	?>

	<tr>
	<td colspan="13" style="text-align:center;">
	No Transaction Records Found
	</td>
	</tr>

	<?php

		}

	}
?>