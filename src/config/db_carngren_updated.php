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

	/**
	 * Add account with prepared statement (secure)
	 */
	function addAccount($fullName, $email, $password)
	{
		global $conn;

		$stmt = $conn->prepare("INSERT INTO user(fullName, email, password, logStatus) VALUES(?, ?, ?, 0)");
		
		if (!$stmt) {
			error_log("Prepare error: " . $conn->error);
			return false;
		}
		
		$stmt->bind_param("sss", $fullName, $email, $password);
		
		if($stmt->execute())
		{
			return true;
		}
		else
		{
			error_log("Insert error: " . $stmt->error);
			return false;
		}
	}
	
	/**
	 * Add product with prepared statement (secure)
	 */
	function addProduct($productName, $productQty, $productPrice, $productIMG)
	{
		global $conn;
		
		$stmt = $conn->prepare("INSERT INTO product(productName, productQty, productPrice, productIMG) VALUES(?, ?, ?, ?)");
		
		if (!$stmt) {
			error_log("Prepare error: " . $conn->error);
			return false;
		}
		
		$stmt->bind_param("sids", $productName, $productQty, $productPrice, $productIMG);
		
		if($stmt->execute())
		{
			return true;
		}
		else
		{
			error_log("Insert error: " . $stmt->error);
			return false;
		}
	}
	
	/**
	 * Make payment with prepared statement (secure)
	 */
	function makePayment($orderID, $userID, $fullname, $email, $orderDate, $orderTime, $Qty, $productName, $total, $address, $state, $city, $zip) {
		global $conn;
		
		$status = 'pending';
		
		$stmt = $conn->prepare("
			INSERT INTO transaction(orderID, userID, fullname, email, orderDate, orderTime, Qty, productName, total, address, state, city, zip, status)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		");
		
		if (!$stmt) {
			error_log("Prepare error: " . $conn->error);
			return false;
		}
		
		$stmt->bind_param("sisssisdsssss", $orderID, $userID, $fullname, $email, $orderDate, $orderTime, $Qty, $productName, $total, $address, $state, $city, $zip, $status);
		
		if($stmt->execute())
		{
			return true;
		}
		else
		{
			error_log("Insert error: " . $stmt->error);
			return false;
		}
	}
	
	/**
	 * Display transaction records
	 */
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
		transaction.total,
		transaction.address,
		transaction.state,
		transaction.city,
		transaction.zip,
		transaction.status
		FROM transaction
		INNER JOIN user ON transaction.userID = user.userID
		ORDER BY transaction.orderDate DESC
		";

		$result = mysqli_query($conn, $sql);

		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_assoc($result))
			{
				?>
				<tr>
					<td><?php echo htmlspecialchars($row['orderID']); ?></td>
					<td><?php echo htmlspecialchars($row['userID']); ?></td>
					<td><?php echo htmlspecialchars($row['fullName']); ?></td>
					<td><?php echo htmlspecialchars($row['email']); ?></td>
					<td><?php echo $row['orderDate']; ?></td>
					<td><?php echo $row['orderTime']; ?></td>
					<td>$<?php echo number_format($row['total'], 2); ?></td>
					<td><?php echo htmlspecialchars($row['address']); ?></td>
					<td><?php echo htmlspecialchars($row['city'] . ', ' . $row['state']); ?></td>
					<td><?php echo htmlspecialchars($row['zip']); ?></td>
					<td><span class="badge badge-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td colspan="12" style="text-align:center;">
					No Transaction Records Found
				</td>
			</tr>
			<?php
		}
	}
?>