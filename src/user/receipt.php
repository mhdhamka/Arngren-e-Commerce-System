<?php
session_start();
include("../../src/config/db_carngren.php");

// Check if user is logged in
if (!isset($_SESSION['userID']) || !isset($_SESSION['orderID'])) {
    header("Location: ../auth/index.php");
    exit();
}

$userID = $_SESSION['userID'];
$orderID = $_SESSION['orderID'];

// Get order details
$stmt = $conn->prepare("
    SELECT t.*, GROUP_CONCAT(CONCAT(c.productName, ' x ', c.orderQty) SEPARATOR ', ') as items
    FROM transaction t
    LEFT JOIN cart c ON t.userID = c.userID
    WHERE t.orderID = ? AND t.userID = ?
    GROUP BY t.orderID
");
$stmt->bind_param("si", $orderID, $userID);
$stmt->execute();
$orderResult = $stmt->get_result();

if ($orderResult->num_rows == 0) {
    header("Location: ../auth/index.php");
    exit();
}

$order = $orderResult->fetch_assoc();
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | Order Receipt</title>
    <link rel="stylesheet" href="../../assets/css/index.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">
    <style>
        .receipt-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .receipt {
            background: white;
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-top: 5px solid #28a745;
        }
        
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        
        .receipt-header h1 {
            color: #28a745;
            margin: 10px 0;
        }
        
        .receipt-header p {
            color: #666;
            margin: 5px 0;
        }
        
        .receipt-section {
            margin-bottom: 30px;
        }
        
        .receipt-section h3 {
            color: #c45b56;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .receipt-row.total {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            padding: 15px 0;
            margin-top: 15px;
        }
        
        .status-badge {
            display: inline-block;
            background: #ffc107;
            color: #333;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            margin-top: 10px;
        }
        
        .status-badge.completed {
            background: #28a745;
            color: white;
        }
        
        .action-buttons {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #28a745;
            color: white;
        }
        
        .btn-primary:hover {
            background: #218838;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .print-note {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 20px;
        }
        
        @media print {
            .action-buttons, .print-note {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="headercontainer">
            <div class="topnav">
                <h1>www.ARNGREN.net</h1>
                <div class="logo">
                    <a href="../auth/index.php">
                        <img src="../../assets/images/logo.PNG" width="125px">
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="receipt-container">
        <div class="receipt">
            <div class="receipt-header">
                <h1>✓ Order Confirmed</h1>
                <p>Thank you for your purchase!</p>
                <p style="color: #28a745; font-weight: bold; font-size: 14px;">Your order has been received</p>
            </div>
            
            <div class="receipt-section">
                <h3>Order Information</h3>
                <div class="receipt-row">
                    <span>Order Number:</span>
                    <span style="font-weight: bold;"><?php echo htmlspecialchars($order['orderID']); ?></span>
                </div>
                <div class="receipt-row">
                    <span>Order Date:</span>
                    <span><?php echo $order['orderDate'] . ' ' . $order['orderTime']; ?></span>
                </div>
                <div class="receipt-row">
                    <span>Status:</span>
                    <span>
                        <span class="status-badge">Processing</span>
                    </span>
                </div>
            </div>
            
            <div class="receipt-section">
                <h3>Billing & Shipping Address</h3>
                <div class="receipt-row">
                    <span>Name:</span>
                    <span><?php echo htmlspecialchars($order['fullname']); ?></span>
                </div>
                <div class="receipt-row">
                    <span>Email:</span>
                    <span><?php echo htmlspecialchars($order['email']); ?></span>
                </div>
                <div class="receipt-row">
                    <span>Address:</span>
                    <span><?php echo htmlspecialchars($order['address']); ?></span>
                </div>
                <div class="receipt-row">
                    <span>City, State:</span>
                    <span><?php echo htmlspecialchars($order['city'] . ', ' . $order['state']); ?></span>
                </div>
                <div class="receipt-row">
                    <span>Zip Code:</span>
                    <span><?php echo htmlspecialchars($order['zip']); ?></span>
                </div>
            </div>
            
            <div class="receipt-section">
                <h3>Order Total</h3>
                <div class="receipt-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($order['total'] / 1.10, 2); ?></span>
                </div>
                <div class="receipt-row">
                    <span>Tax (10%):</span>
                    <span>$<?php echo number_format(($order['total'] / 1.10) * 0.10, 2); ?></span>
                </div>
                <div class="receipt-row total">
                    <span>Total Amount:</span>
                    <span>$<?php echo number_format($order['total'], 2); ?></span>
                </div>
            </div>
            
            <div class="receipt-section">
                <h3>What's Next?</h3>
                <p style="color: #666; line-height: 1.6;">
                    Your order is being processed. You will receive a shipping confirmation email shortly with tracking information.
                    If you have any questions, please contact our customer service team.
                </p>
            </div>
            
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="window.print();">Print Receipt</button>
                <a href="../auth/index.php" class="btn btn-secondary">Back to Home</a>
            </div>
            
            <div class="print-note">
                💡 Tip: You can print this receipt for your records. A copy has been sent to your email.
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>
</body>
</html>