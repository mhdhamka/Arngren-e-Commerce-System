<?php
session_start();
include("../../src/config/db_carngren.php");
include("../../src/config/cartFunctions.php");

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../auth/login.php");
    exit();
}

$userID = $_SESSION['userID'];
$message = "";
$addressError = $stateError = $cityError = $zipError = "";

// Get cart total and items
$cartResult = getCartItems($userID);
$cartTotal = getCartTotal($userID);
$cartCount = getCartCount($userID);

if ($cartCount == 0) {
    header("Location: shoppingCart.php");
    exit();
}

// Handle checkout submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $address = trim($_POST['address']);
    $state = trim($_POST['state']);
    $city = trim($_POST['city']);
    $zip = trim($_POST['zip']);
    
    // Validation
    if (empty($address)) {
        $addressError = "Address is required";
    }
    if (empty($state)) {
        $stateError = "State is required";
    }
    if (empty($city)) {
        $cityError = "City is required";
    }
    if (empty($zip) || !preg_match('/^[0-9]{5,6}$/', $zip)) {
        $zipError = "Valid zip code is required";
    }
    
    // If all validations pass, create order
    if (empty($addressError) && empty($stateError) && empty($cityError) && empty($zipError)) {
        // Generate unique order ID
        $orderID = 'ORD' . date('YmdHis') . rand(1000, 9999);
        $orderDate = date('Y-m-d');
        $orderTime = date('H:i:s');
        $total = $cartTotal * 1.10; // Include 10% tax
        
        // Insert transaction
        $stmt = $conn->prepare("
            INSERT INTO transaction (orderID, userID, fullname, email, orderDate, orderTime, total, address, state, city, zip, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ");
        
        $stmt->bind_param(
            "sissssdsssi",
            $orderID,
            $userID,
            $_SESSION['fullName'],
            $_SESSION['email'],
            $orderDate,
            $orderTime,
            $total,
            $address,
            $state,
            $city,
            $zip
        );
        
        if ($stmt->execute()) {
            // Clear cart
            clearCart($userID);
            
            // Redirect to receipt
            $_SESSION['orderID'] = $orderID;
            $_SESSION['orderTotal'] = $total;
            header("Location: receipt.php");
            exit();
        } else {
            $message = "Error processing order. Please try again.";
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | Checkout</title>
    <link rel="stylesheet" href="../../assets/css/index.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .checkout-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        .checkout-form {
            background: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-group textarea:focus,
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #c45b56;
            box-shadow: 0 0 5px rgba(196, 91, 86, 0.3);
        }
        
        .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .btn-submit {
            background: #28a745;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            margin-top: 20px;
        }
        
        .btn-submit:hover {
            background: #218838;
        }
        
        .order-summary {
            background: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .order-summary h3 {
            color: #c45b56;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        
        .order-total {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-size: 18px;
            font-weight: bold;
            color: #c45b56;
            margin-top: 10px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .summary-row.total {
            border: none;
            font-weight: bold;
            color: #c45b56;
            font-size: 16px;
            padding-top: 15px;
        }
        
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .checkout-wrapper {
                grid-template-columns: 1fr;
            }
            
            .order-summary {
                position: static;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="headercontainer">
            <div class="topnav">
                <h1>www.ARNGREN.net</h1>
                <div class="centernav">
                    <ul>
                        <li><a href="../auth/index.php">Home</a></li>
                        <li>|</li>
                        <li><a href="productList.php">Products</a></li>
                        <li>|</li>
                        <li><a href="aboutUs.php">About Us</a></li>
                    </ul>
                </div>
                
                <div class="logo">
                    <a href="../auth/index.php">
                        <img src="../../assets/images/logo.PNG" width="125px">
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="checkout-container">
        <h1>Checkout</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <div class="checkout-wrapper">
            <div class="checkout-form">
                <h2>Shipping Information</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($_SESSION['fullName']); ?>" disabled>
                        <small style="color: #666;">From your account</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" disabled>
                        <small style="color: #666;">From your account</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Address *</label>
                        <textarea name="address" placeholder="Enter your full address" required></textarea>
                        <?php if ($addressError): ?>
                            <div class="error"><?php echo $addressError; ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>State *</label>
                            <input type="text" name="state" placeholder="State" required>
                            <?php if ($stateError): ?>
                                <div class="error"><?php echo $stateError; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label>City *</label>
                            <input type="text" name="city" placeholder="City" required>
                            <?php if ($cityError): ?>
                                <div class="error"><?php echo $cityError; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Zip Code *</label>
                        <input type="text" name="zip" placeholder="Zip Code" pattern="[0-9]{5,6}" required>
                        <?php if ($zipError): ?>
                            <div class="error"><?php echo $zipError; ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" name="checkout" class="btn-submit">Complete Purchase</button>
                </form>
            </div>
            
            <div class="order-summary">
                <h3>Order Summary</h3>
                
                <?php 
                $cartResult = getCartItems($userID);
                while ($item = $cartResult->fetch_assoc()): 
                ?>
                    <div class="order-item">
                        <span><?php echo htmlspecialchars($item['productName']); ?> x <?php echo $item['orderQty']; ?></span>
                        <span>$<?php echo number_format($item['subtotal'], 2); ?></span>
                    </div>
                <?php endwhile; ?>
                
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($cartTotal, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                <div class="summary-row">
                    <span>Tax (10%):</span>
                    <span>$<?php echo number_format($cartTotal * 0.10, 2); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>$<?php echo number_format($cartTotal * 1.10, 2); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>
</body>
</html>