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

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'remove' && isset($_POST['cartID'])) {
            $result = removeFromCart($_POST['cartID'], $userID);
            $message = $result['message'];
        } elseif ($action == 'update' && isset($_POST['cartID']) && isset($_POST['quantity'])) {
            $result = updateCartQuantity($_POST['cartID'], $userID, $_POST['quantity']);
            $message = $result['message'];
        } elseif ($action == 'clear') {
            $result = clearCart($userID);
            $message = $result['message'];
        }
    }
}

$cartResult = getCartItems($userID);
$cartTotal = getCartTotal($userID);
$cartCount = getCartCount($userID);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | Shopping Cart</title>
    <link rel="stylesheet" href="../../assets/css/index.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .cart-empty {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .cart-empty h2 {
            color: #c45b56;
            margin-bottom: 20px;
        }
        
        .cart-empty a {
            background: #c45b56;
            color: white;
            padding: 10px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .cart-table th {
            background: #c45b56;
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        .cart-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .cart-table tr:hover {
            background: #f9f9f9;
        }
        
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .qty-input {
            width: 70px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        
        .btn-remove {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-remove:hover {
            background: #c82333;
        }
        
        .cart-summary {
            background: white;
            padding: 20px;
            border-radius: 5px;
            max-width: 400px;
            margin-left: auto;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-size: 18px;
            font-weight: bold;
            color: #c45b56;
        }
        
        .btn-checkout {
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: bold;
        }
        
        .btn-checkout:hover {
            background: #218838;
        }
        
        .btn-continue-shopping {
            width: 100%;
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
            text-align: center;
            display: block;
        }
        
        .btn-continue-shopping:hover {
            background: #5a6268;
        }
        
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
                
                <div class="searchbar">
                    <input type="text" class="search" placeholder="Search for products..">
                    <div class="searchbutton">
                        <p>Search</p>
                    </div>
                    <div class="cart">
                        <a href="shoppingCart.php"><i style="color: white" class="fa fa-shopping-cart fa-2x"></i></a>
                    </div>
                </div>
                
                <nav>
                    <ul>
                        <li>
                            <span>Welcome, <?php echo $_SESSION['fullName']; ?></span>
                        </li>
                        <li>
                            <a href="profile.php">My Profile</a>
                        </li>
                        <li>|</li>
                        <li>
                            <a href="../auth/logout.php">Log Out</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    
    <div class="cart-container">
        <h1>Shopping Cart</h1>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($cartCount == 0): ?>
            <div class="cart-empty">
                <h2>Your cart is empty</h2>
                <p>Add some products to get started!</p>
                <a href="productList.php">Continue Shopping</a>
            </div>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $cartResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['productName']); ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($item['productIMG']); ?>" class="product-img" alt="Product">
                            </td>
                            <td>$<?php echo number_format($item['productPrice'], 2); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="cartID" value="<?php echo $item['cartID']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['orderQty']; ?>" class="qty-input" min="1" onchange="this.form.submit();">
                                </form>
                            </td>
                            <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="cartID" value="<?php echo $item['cartID']; ?>">
                                    <button type="submit" class="btn-remove">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($cartTotal, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>$0.00 (Free)</span>
                </div>
                <div class="summary-row">
                    <span>Tax (10%):</span>
                    <span>$<?php echo number_format($cartTotal * 0.10, 2); ?></span>
                </div>
                <div class="summary-total">
                    <span>Total:</span>
                    <span>$<?php echo number_format($cartTotal * 1.10, 2); ?></span>
                </div>
                
                <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
                <a href="productList.php" class="btn-continue-shopping">Continue Shopping</a>
                
                <form method="POST" style="margin-top: 10px;">
                    <input type="hidden" name="action" value="clear">
                    <button type="submit" class="btn-continue-shopping" style="background: #dc3545; cursor: pointer;" onclick="return confirm('Clear entire cart?');">Clear Cart</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>
</body>
</html>