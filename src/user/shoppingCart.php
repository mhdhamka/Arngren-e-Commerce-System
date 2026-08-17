<?php
session_start();
include("../config/db_carngren.php");

if(!isset($_SESSION['userID']) || $_SESSION['logStatus'] != 1) {
    header("Location: ../user/shoppingCart.php");
    exit();
}

$userID = $_SESSION['userID'];
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | Shopping Cart</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../assets/css/shoppingCart.css">

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
                    <h2>Shopping Cart <i class="fa fa-shopping-cart"></i></h2>
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

    <!-- MAIN CONTENT CONTAINER -->
    <div class="content">
        <div class="cart-container">
            
            <!-- CART PRODUCTS SECTION -->
            <div class="cart-products">
                <form id="deleteForm" action="clearCart.php" method="POST">
                    
                    <div class="select-card" style="background:#fff; padding:15px; border-radius:8px; margin-bottom:15px; display:flex; align-items:center; gap:10px; font-weight:600;">
                        <input type="checkbox" id="selectAll">
                        <label for="selectAll">Select All Products</label>
                    </div>

                    <?php
                    $total = 0;
                    $sql = "SELECT 
                                cart.cartID,
                                cart.orderQty,
                                product.productName,
                                product.productPrice,
                                product.productIMG,
                                product.productCtgry
                            FROM cart
                            INNER JOIN product
                            ON cart.productID = product.productID
                            WHERE cart.userID = '$userID'";

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            $subtotal = $row['orderQty'] * $row['productPrice'];
                            $total += $subtotal;
                    ?>

                    <div class="shop-card">
                        <div class="shop-header">
                            <span><i class="fa fa-store"></i> Arngren Official Store</span>
                        </div>

                        <!-- ALIGNED PRODUCT ROW -->
                        <div class="product-row">
                            <div class="product-info-group">
                                <input 
                                    type="checkbox"
                                    class="product-check"
                                    name="cartID[]"
                                    value="<?php echo $row['cartID']; ?>"
                                    data-price="<?php echo $row['productPrice']; ?>">
                                
                                <img src="<?php echo htmlspecialchars($row['productIMG']); ?>" alt="Product" class="product-image">

                                <div class="product-details">
                                    <h3><?php echo htmlspecialchars($row['productName']); ?></h3>
                                    <p><?php echo htmlspecialchars($row['productCtgry']); ?></p>
                                    <div class="price">
                                        KR <span class="product-price"><?php echo number_format($row['productPrice'], 2); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="cart-actions">
                                <!-- QUANTITY CONTROLS -->
                                <div class="quantity-area">
                                    <div class="qty-control" style="display:flex; align-items:center; border:1px solid #ddd; border-radius:4px;">
                                        <button type="button" style="background:#f9f9f9; border:none; padding:5px 10px; cursor:pointer;" onclick="updateQty(<?php echo $row['cartID']; ?>, 'minus')">−</button>
                                        <input type="number" class="qty-input" value="<?php echo $row['orderQty']; ?>" min="1" readonly style="width:40px; text-align:center; border:none;">
                                        <button type="button" style="background:#f9f9f9; border:none; padding:5px 10px; cursor:pointer;" onclick="updateQty(<?php echo $row['cartID']; ?>, 'plus')">+</button>
                                    </div>
                                </div>

                                <!-- DELETE BUTTON -->
                                <a href="removeCart.php?cartID=<?php echo $row['cartID']; ?>" class="delete-btn-modern">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php 
                        } 
                    } else {
                        echo '<div class="select-card" style="text-align: center; padding: 40px; background:#fff; border-radius:8px;">Your shopping cart is empty.</div>';
                    }
                    ?>
                </form>
            </div>

            <!-- ORDER SUMMARY SIDEBAR -->
            <div class="checkout-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); height: fit-content;">
                <h3 style="margin-top:0; font-size: 18px; color: #222;">Order Details</h3>

                <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #555;">
                    <span>Price Total</span>
                    <b id="priceTotal">KR <?php echo number_format($total, 2); ?></b>
                </div>

                <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 15px; color: #555;">
                    <span>Discount</span>
                    <b>KR 0.00</b>
                </div>

                <hr style="border:0; border-top:1px solid #eee; margin:15px 0;">

                <div class="summary-total" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 18px;">
                    <span style="font-weight: 600; color: #222;">Total</span>
                    <b id="grandTotal" style="color: #ee4d2d;">KR <?php echo number_format($total, 2); ?></b>
                </div>

                <form action="payment.php" method="POST" id="checkoutForm">
                    <input type="hidden" name="selectedCart" id="selectedCart">
                    <button type="submit" class="checkout-btn" onclick="return sendCheckout();">
                        CHECKOUT
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>&copy; 2026 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>

    <!-- External JavaScript -->
    <script src="../../assets/js/shoppingCart.js"></script>
</body>
</html>