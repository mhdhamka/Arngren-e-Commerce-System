<?php
    // Define BASE_URL constant
    if (!defined('BASE_URL')) {
        define('BASE_URL', 'http://localhost/arngren/src/');
    }

    $hostName = "localhost";
    $username = "p1_admin";
    $password = "dummy123";
    $database = "db_arngren";

    // Create connection
    $conn = new mysqli($hostName, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 1. Add Account Function
    if (!function_exists('addAccount')) {
        function addAccount($fullName, $email, $password) {
            global $conn;

            $sql = "
            INSERT INTO user(fullName, email, password, logStatus)
            VALUES(
                '$fullName',
                '$email',
                '$password',
                0
            )
            ";

            if (mysqli_query($conn, $sql)) {
                return true;
            } else {
                echo "Error: " . mysqli_error($conn);
                return false;
            }
        }
    }
    
    // 2. Add Product Function
    if (!function_exists('addProduct')) {
        function addProduct($productName, $productQty, $productPrice, $productIMG) {
            global $conn;
            $sql = "INSERT INTO product(productName, productQty, productPrice, productIMG)
                    VALUES('$productName', '$productQty', '$productPrice', '$productIMG')";

            if (mysqli_query($conn, $sql)) { 
                $success = false;       
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }
    
    // 3. Make Payment / Insert Transaction Function
    if (!function_exists('makePayment')) {
        function makePayment($orderID, $userID, $fullname, $email, $orderDate, $orderTime, $Qty, $productName, $total, $address, $state, $city, $zip) {
            global $conn;
            $sql = "INSERT INTO `transaction`(`orderID`, `userID`, `orderDate`, `orderTime`, `subTotal`, `total`, `address`, `state`, `city`, `zip`)
                    VALUES('$orderID', $userID, '$orderDate', '$orderTime', '$total', '$total', '$address', '$state', '$city', '$zip')";
            
            if (!mysqli_query($conn, $sql)) {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }

    // 4. Display Record Function with LEFT JOIN & Fallbacks
    if (!function_exists('displayRecord')) {
        function displayRecord() {
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
                cart.orderQty,
                product.productName
            FROM transaction
            LEFT JOIN user ON transaction.userID = user.userID
            LEFT JOIN cart ON transaction.userID = cart.userID
            LEFT JOIN product ON cart.productID = product.productID
            ";

            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $orderID     = $row['orderID'] ?? '';
                    $userID      = $row['userID'] ?? '';
                    $fullName    = $row['fullName'] ?? 'Guest User';
                    $email       = $row['email'] ?? 'N/A';
                    $orderDate   = $row['orderDate'] ?? '';
                    $orderTime   = $row['orderTime'] ?? '';
                    $qty         = $row['orderQty'] ?? '1';
                    $productName = $row['productName'] ?? 'Standard Product';
                    $total       = $row['total'] ?? 0;
                    $address     = $row['address'] ?? '';
                    $state       = $row['state'] ?? '';
                    $city        = $row['city'] ?? '';
                    $zip         = $row['zip'] ?? '';
                    ?>
                    <tr>
                        <td data-label="Order ID"><?php echo htmlspecialchars($orderID); ?></td>
                        <td data-label="User ID"><?php echo htmlspecialchars($userID); ?></td>
                        <td data-label="Customer Name"><?php echo htmlspecialchars($fullName); ?></td>
                        <td data-label="Customer Email"><?php echo htmlspecialchars($email); ?></td>
                        <td data-label="Order Date"><?php echo htmlspecialchars($orderDate); ?></td>
                        <td data-label="Order Time"><?php echo htmlspecialchars($orderTime); ?></td>
                        <td data-label="Product Quantity"><?php echo htmlspecialchars($qty); ?></td>
                        <td data-label="Product Name"><?php echo htmlspecialchars($productName); ?></td>
                        <td data-label="Total Price">KR <?php echo number_format((float)$total, 2); ?></td>
                        <td data-label="Customer Address"><?php echo htmlspecialchars($address); ?></td>
                        <td data-label="State"><?php echo htmlspecialchars($state); ?></td>
                        <td data-label="City"><?php echo htmlspecialchars($city); ?></td>
                        <td data-label="Zip"><?php echo htmlspecialchars($zip); ?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="13" style="text-align:center;">
                        No Transaction Records Found
                    </td>
                </tr>
                <?php
            }
        }
    }
?>