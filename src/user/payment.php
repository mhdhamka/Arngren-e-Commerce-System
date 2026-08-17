<?php

session_start();

include("../config/db_carngren.php");


if(!isset($_SESSION['userID']))
{
    header("Location: ../auth/login.php");
    exit();
}


$userID = $_SESSION['userID'];



if(!isset($_POST['selectedCart']))
{
    header("Location: shoppingCart.php");
    exit();
}



$cartIDs = $_POST['selectedCart'];

$cartIDs = explode(",", $cartIDs);



$idList = implode(",", $cartIDs);



$sql = "

SELECT

cart.cartID,
cart.orderQty,

product.productName,
product.productPrice,
product.productIMG

FROM cart

INNER JOIN product

ON cart.productID = product.productID

WHERE cart.userID='$userID'

AND cart.cartID IN ($idList)

";



$result=mysqli_query($conn,$sql);



$total = 0;

$productList=[];


while($row=mysqli_fetch_assoc($result))
{

    $subtotal =
    $row['orderQty'] *
    $row['productPrice'];


    $total += $subtotal;


    $productList[]=$row['productName'];

}



if($total==0)
{
    header("Location: shoppingCart.php");
    exit();
}


?>



<!DOCTYPE HTML>
<html>

<head>

    <title>
        Arngren | Payment
    </title>

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
                <a href="../auth/index.php">
                    <img src="../../assets/images/logo.PNG" alt="Arngren Logo">
                </a>
            </div>

            <h1><a href="../auth/index.php">ARNGREN</a></h1>

            <div>
                <a href="shoppingCart.php"><i class="fa fa-arrow-left"></i> Back to Cart</a>
            </div>

            <div class="title">
                <h2>Checkout <i class="fa fa-credit-card"></i></h2>
            </div>

            <div class="search-container">
                <input type="text" placeholder="Search products..." class="search">
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
    <div class="payment-container">

        <h2 class="page-title">
            Billing & Payment
        </h2>

        <form action="receipt.php" method="POST">

            <h3>
                Billing Address
            </h3>

            <input type="hidden" name="cartIDs" value="<?php echo implode(",",$cartIDs); ?>">

            <input type="hidden" name="total" value="<?php echo $total; ?>">

            <input type="hidden" name="productName" value="<?php echo implode(",",$productList); ?>">

            <label>
                Full Name
            </label>


            <input type="text" name="fullname" value="<?php echo $_SESSION['fullName']; ?>" required>

            <label>
                Email
            </label>

            <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>" required>

            <label>
                Address
            </label>

            <input type="text" name="address" placeholder="Street address" required>

            <div class="form-row">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" required>
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state" required>
                </div>
                <div class="form-group">
                    <label>Zip Code</label>
                    <input type="text" name="zip" required>
                </div>
            </div>

            <h3>
                Payment Information
            </h3>

            <label>
                Card Number
            </label>

            <input type="text" name="cardnumber" placeholder="1111-2222-3333-4444" required>

            <div class="form-row">
                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="text" name="expiry" placeholder="MM/YY" required>
                </div>
                <div class="form-group">
                    <label>CVV</label>
                    <input type="text" name="cvv" placeholder="123" required>
                </div>
            </div>

            <hr>

            <h3>
                Order Summary
            </h3>

            <div class="summary-box">
                <div class="summary-row">
                    <span>Product Total:</span>
                    <b>KR <?php echo number_format($total,2);?></b>
                </div>

                <div class="summary-row">
                    <span>Tax (6%):</span>
                    <b>KR <?php echo number_format($total*0.06,2);?></b>
                </div>

                <div class="summary-total">
                    <span>Total Payment:</span>
                    <span>KR <?php echo number_format($total*1.06,2);?></span>
                </div>
            </div>

            <button type="submit" name="insert_records" class="checkout-btn">
                Pay Now 
            </button>

        </form>

    </div>
</div>

</body>

</html>