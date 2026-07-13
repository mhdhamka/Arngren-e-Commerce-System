<?php
session_start();
include("../../src/config/db_carngren.php");
include("../../src/config/cartFunctions.php");

$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
$message = "";

// Get category filter
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!$userID) {
        $message = "Please login to add items to cart";
    } else {
        $productID = $_POST['productID'];
        $productName = $_POST['productName'];
        $qty = $_POST['quantity'];
        
        $result = addToCart($userID, $productID, $productName, $qty);
        $message = $result['message'];
    }
}

// Build query
$query = "SELECT * FROM product WHERE 1=1";
$params = array();
$types = "";

if (!empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

if (!empty($search)) {
    $query .= " AND productName LIKE ?";
    $searchTerm = '%' . $search . '%';
    $params[] = $searchTerm;
    $types .= "s";
}

$query .= " ORDER BY productID DESC";

// Execute query
if (!empty($params)) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}

// Get unique categories
$categoriesQuery = "SELECT DISTINCT category FROM product ORDER BY category";
$categoriesResult = $conn->query($categoriesQuery);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | Products</title>
    <link rel="stylesheet" href="../../assets/css/index.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">
    <script src="https://use.fontawesome.com/59805f286a.js"></script>
    <style>
        .products-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-group label {
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        
        .filter-group input,
        .filter-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-filter {
            background: #c45b56;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .btn-filter:hover {
            background: #a84742;
        }
        
        .btn-reset {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-reset:hover {
            background: #5a6268;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .product-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f0f0f0;
        }
        
        .product-info {
            padding: 15px;
        }
        
        .product-category {
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .product-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin: 8px 0;
            min-height: 40px;
        }
        
        .product-price {
            font-size: 20px;
            font-weight: bold;
            color: #c45b56;
            margin: 10px 0;
        }
        
        .product-stock {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .product-stock.low {
            color: #dc3545;
            font-weight: bold;
        }
        
        .product-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }
        
        .qty-input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        
        .btn-add-cart {
            flex: 2;
            background: #28a745;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 13px;
        }
        
        .btn-add-cart:hover {
            background: #218838;
        }
        
        .btn-add-cart:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #666;
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
                        <li><a href="productList.php" class="active">Products</a></li>
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
                    <div class="cart">
                        <a href="shoppingCart.php"><i style="color: white" class="fa fa-shopping-cart fa-2x"></i></a>
                    </div>
                </div>
                
                <nav>
                    <ul>
                        <?php if ($userID): ?>
                            <li><span>Welcome, <?php echo $_SESSION['fullName']; ?></span></li>
                            <li><a href="profile.php">My Profile</a></li>
                            <li>|</li>
                            <li><a href="../auth/logout.php">Log Out</a></li>
                        <?php else: ?>
                            <li><a href="../auth/registration.php">Sign Up</a></li>
                            <li>|</li>
                            <li><a href="../auth/login.php">Log In</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    
    <div class="products-container">
        <h1>Our Products</h1>
        
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'error') !== false || strpos($message, 'Please') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="filter-section">
            <form method="GET" action="productList.php">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="category">Filter by Category</label>
                        <select name="category" id="category">
                            <option value="">All Categories</option>
                            <?php while ($cat = $categoriesResult->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                    <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['category']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="search">Search Products</label>
                        <input type="text" name="search" id="search" placeholder="Search by name..." 
                            value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    
                    <div class="filter-group" style="justify-content: flex-end;">
                        <label>&nbsp;</label>
                        <div class="filter-buttons">
                            <button type="submit" class="btn-filter">Apply Filter</button>
                            <a href="productList.php" class="btn-reset">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="products-grid">
                <?php while ($product = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['productIMG']); ?>" alt="<?php echo htmlspecialchars($product['productName']); ?>" class="product-image">
                        
                        <div class="product-info">
                            <div class="product-category"><?php echo htmlspecialchars($product['category']); ?></div>
                            <div class="product-name"><?php echo htmlspecialchars($product['productName']); ?></div>
                            <div class="product-price">$<?php echo number_format($product['productPrice'], 2); ?></div>
                            
                            <?php if ($product['productQty'] > 5): ?>
                                <div class="product-stock">✓ In Stock (<?php echo $product['productQty']; ?> available)</div>
                            <?php elseif ($product['productQty'] > 0): ?>
                                <div class="product-stock low">⚠ Low Stock (<?php echo $product['productQty']; ?> left)</div>
                            <?php else: ?>
                                <div class="product-stock low">Out of Stock</div>
                            <?php endif; ?>
                            
                            <?php if ($product['productQty'] > 0): ?>
                                <form method="POST" style="margin-top: 12px;">
                                    <input type="hidden" name="productID" value="<?php echo $product['productID']; ?>">
                                    <input type="hidden" name="productName" value="<?php echo htmlspecialchars($product['productName']); ?>">
                                    <input type="hidden" name="add_to_cart" value="1">
                                    
                                    <div class="product-actions">
                                        <input type="number" name="quantity" min="1" max="<?php echo $product['productQty']; ?>" value="1" class="qty-input">
                                        <button type="submit" class="btn-add-cart" <?php echo !$userID ? 'onclick="alert(\"Please login first\"); return false;"' : ''; ?>>
                                            <i class="fa fa-shopping-cart"></i> Add
                                        </button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <button class="btn-add-cart" disabled style="background: #ccc;">
                                    Out of Stock
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-products">
                <h2>No products found</h2>
                <p>Try adjusting your filters or search terms</p>
                <a href="productList.php" class="btn-filter" style="text-decoration: none; display: inline-block; margin-top: 15px;">View All Products</a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>
</body>
</html>