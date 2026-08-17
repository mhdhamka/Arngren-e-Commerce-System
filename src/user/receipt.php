<?php
session_start();
include("../config/db_carngren.php");

// Load Composer's autoloader
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(!isset($_SESSION['userID'])) {
    header("Location: ../auth/login.php");
    exit();
}

if(!isset($_POST['insert_records'])) {
    header("Location: shoppingCart.php");
    exit();
}

$userID = $_SESSION['userID'];
$cartIDs = $_POST['cartIDs'];
$subTotal = $_POST['total'];
$fullName = $_POST['fullname'];
$email = $_POST['email'];
$address = $_POST['address'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$productNames = $_POST['productName'];

// Calculate total with 6% tax
$total = $subTotal * 1.06;

// Insert record matching your transaction table columns
$insertQuery = "INSERT INTO transaction (userID, orderDate, orderTime, subTotal, total, address, state, city, zip) 
                VALUES ('$userID', CURDATE(), CURTIME(), '$subTotal', '$total', '$address', '$state', '$city', '$zip')";
mysqli_query($conn, $insertQuery);

// Delete checked out items from the cart table
$cartIDArray = explode(",", $cartIDs);
$idList = implode(",", array_map('intval', $cartIDArray));

$deleteQuery = "DELETE FROM cart WHERE userID='$userID' AND cartID IN ($idList)";
mysqli_query($conn, $deleteQuery);

// Send Real Order Confirmation Email via PHPMailer & Gmail SMTP
$formattedTotal = "KR " . number_format($total, 2);
$fullAddress = "$address, $city, $state $zip";

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'm.hamka017@@gmail.com'; 
    $mail->Password   = 'cjak xqlb tigu ssqq';    
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('no-reply@arngren.com', 'Arngren Store');
    $mail->addAddress($email, $fullName);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Arngren - Order Confirmation & Receipt';
    $mail->Body    = "
    <html>
    <head>
        <title>Order Confirmation</title>
    </head>
    <body>
        <h2>Thank you for your order, {$fullName}!</h2>
        <p>Your payment has been successfully processed.</p>
        <h3>Order Details:</h3>
        <ul>
            <li><strong>Products:</strong> {$productNames}</li>
            <li><strong>Shipping Address:</strong> {$fullAddress}</li>
            <li><strong>Total Paid (incl. 6% tax):</strong> {$formattedTotal}</li>
        </ul>
        <p>Thank you for shopping with Arngren!</p>
    </body>
    </html>
    ";

    $mail->send();
    $emailStatus = "A confirmation email has been successfully sent to your inbox.";
} catch (Exception $e) {
    $emailStatus = "Order placed successfully, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | Receipt</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../assets/css/shoppingCart.css">
    <link rel="stylesheet" href="../../assets/css/payment.css">

    <!-- FontAwesome & Favicon -->
    <script src="https://use.fontawesome.com/59805f286a.js"></script>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.PNG">
</head>
<body>

    <!-- HEADER & NAVIGATION -->
    <div class="header">
        <div class="headercontainer">
            <div class="topnav">
                <div class="logo">
                    <a class="active" href="../auth/index.php">
                        <img src="../../assets/images/logo.PNG" alt="Arngren Logo" width="125px">
                    </a>
                </div>

                <h1><a href="../auth/index.php">ARNGREN</a></h1>

                <div class="centernav">
                    <ul>
                        <li><a href="../user/productList.php"><i class="fa fa-arrow-left"></i> Continue Browsing</a></li>
                    </ul>
                </div>

                <div class="title">
                    <h2>Order Confirmation <i class="fa fa-check-circle"></i></h2>
                </div>

                <div class="search-container">
                    <input type="text" placeholder="Search products, brands..." class="search">
                    <button class="searchbutton"><i class="fa fa-search"></i></button>
                </div>

                <nav class="user-nav">
                    <ul>
                        <?php if(isset($_SESSION['userID'])) { ?>
                            <li>
                                <span>Welcome, <?php echo htmlspecialchars($_SESSION['fullName']); ?></span>
                            </li>
                            <li>
                                <a href="../user/profile.php">My Profile</a>
                            </li>
                            <li>|</li>
                            <li>
                                <a href="../auth/logout.php">Log Out</a>
                            </li>
                        <?php } else { ?>
                            <li>
                                <a href="../auth/registration.php">Sign Up</a>
                            </li>
                            <li>|</li>
                            <li>
                                <a href="../auth/login.php">Log In</a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

<div class="content">
    <div class="checkout-card" style="width: 100%; max-width: 700px; margin: 0 auto; position: static;">
        <div style="text-align: center; margin-bottom: 20px; color: #ee4d2d; font-size: 50px;">
            <i class="fa fa-check-circle"></i>
        </div>
        <h3 style="text-align: center; font-size: 24px; margin-bottom: 10px;">Payment Successful!</h3>
        <p style="text-align: center; color: #666; font-size: 14px; margin-bottom: 25px;">
            Thank you for your purchase. Your order has been placed successfully. <br>
            <span style="color: #008000; font-weight: 500;"><?php echo htmlspecialchars($emailStatus); ?></span>
        </p>

        <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
            <div class="summary-row" style="display: flex; justify-content: space-between; margin: 12px 0; font-size: 14px;">
                <span style="color: #666; font-weight: 500;">Customer Name:</span>
                <span style="color: #222; font-weight: 600;"><?php echo htmlspecialchars($fullName); ?></span>
            </div>
            <div class="summary-row" style="display: flex; justify-content: space-between; margin: 12px 0; font-size: 14px;">
                <span style="color: #666; font-weight: 500;">Email:</span>
                <span style="color: #222; font-weight: 600;"><?php echo htmlspecialchars($email); ?></span>
            </div>
            <div class="summary-row" style="display: flex; justify-content: space-between; margin: 12px 0; font-size: 14px;">
                <span style="color: #666; font-weight: 500;">Shipping Address:</span>
                <span style="color: #222; font-weight: 600; text-align: right; max-width: 60%;"><?php echo htmlspecialchars("$address, $city, $state $zip"); ?></span>
            </div>
            <div class="summary-row" style="display: flex; justify-content: space-between; margin: 12px 0; font-size: 14px;">
                <span style="color: #666; font-weight: 500;">Products:</span>
                <span style="color: #222; font-weight: 600; text-align: right; max-width: 60%;"><?php echo htmlspecialchars($productNames); ?></span>
            </div>
            <div class="summary-total" style="display: flex; justify-content: space-between; margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 18px; font-weight: bold;">
                <span>Total Paid (incl. 6% tax):</span>
                <span style="color: #ee4d2d;">KR <?php echo number_format($total, 2); ?></span>
            </div>
        </div>

        <div style="display: flex; gap: 15px; margin-top: 25px;">
            <a href="shoppingCart.php" class="checkout-btn" style="background: #fff; color: #555; border: 1px solid #ddd; margin-top: 0; text-decoration: none; text-align: center;">Back to Cart</a>
            <a href="../auth/index.php" class="checkout-btn" style="margin-top: 0; text-decoration: none; text-align: center;">Continue Shopping</a>
        </div>
    </div>
</div>

    <!-- FOOTER -->
    <div class="footer">
        <p>&copy; 2026 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>

</body>
</html>