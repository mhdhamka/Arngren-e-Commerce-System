<?php
    include ("../config/db_carngren.php");

    session_start();
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = array();
    }
    unset($_SESSION['qty_array']);

    if(!isset($_SESSION['userID']))
    {
        header("Location: ../auth/login.php");
        exit();
    }
?>

<!DOCTYPE HTML>
<html lang = "en">

<head>
    <meta charset = "UTF-8">
    <title>Arngren | Products | Electric Vehicles</title>

    <!--- external CSS --->
    <link rel = "stylesheet" href = "style.css">
    <link rel="stylesheet" href="../../assets/css/index.css">

    <script src="https://use.fontawesome.com/59805f286a.js"></script>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.PNG">
    
</head>

<body>
    <div class = "header">
        <div class = "headercontainer">
            <div class = "topnav">
                <div class = "logo">
                    <a class = "active" href = "../auth/index.php">
                        <img src = "../../assets/images/logo.PNG" width = "95px" alt="Logo">
                    </a>
                </div>

                <h1><a href="../auth/index.php">ARNGREN</a></h1>
                
                <div class = "centernav">
                    <ul>
                        <li><a href="../auth/index.php">Home</a></li>
                        <li>|</li>
                        <li><a class="active" href="../user/productList.php">Products</a></li>
                        <li>|</li>
                        <li><a href="../user/aboutUs.php">About</a></li>
                    </ul>
                </div>
                
                <!-- Fixed: Wrapped search input and button in a form for actual functionality -->
                <form action="productList.php" method="GET" class="search-container" style="display: flex; align-items: center;">
                    <input type="text" name="search" class="search" placeholder="Search for products.." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="searchbutton">Search</button> 
                </form>

                <div class = "cart">
                    <a href = "../user/shoppingCart.php"><i style = "color: white" class = "fa fa-shopping-cart fa-2x"></i></a>
                </div>
                <div class = "message">
                    <i style = "color: white" class = "fa fa-envelope fa-2x"></i>
                </div>
                <div class = "info">
                    <i style = "color: white" class = "fa fa-gear fa-2x"></i>
                </div>
                
                <nav class="user-nav">
                    <ul>
                    <?php if(isset($_SESSION['userID'])) { ?>
                        <li>
                            <span>Welcome, <?php echo $_SESSION['fullName']; ?></span>
                        </li>
                        <li><a href="../user/profile.php">My Profile</a></li>
                        <li>|</li>
                        <li><a href="../auth/logout.php">Log Out</a></li>
                    <?php } else { ?>
                        <li><a href="../auth/registration.php">Sign Up</a></li>
                        <li>|</li>
                        <li><a href="../auth/login.php">Log In</a></li>
                    <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
        
        <div class = "dashboard">
            <div class="bottomnav-container">
                <button style="background:none; border:none; padding: 0 15px; cursor:pointer;" type="button">
                    <i style="color: #666;" class="fa fa-chevron-left"></i>
                </button>

                <div class="bottomnav" id="categoryNav">
                    <?php
                    $categories = [
                        "Scooter",
                        "Jeep",
                        "Electric Vehicles",
                        "DVD-Player",
                        "Go-Kart",
                        "Hobby & RC",
                        "Binoculars"
                    ];

                    $currentCategory = isset($_GET['category']) ? $_GET['category'] : "";

                    foreach($categories as $category)
                    {
                        $active = ($currentCategory == $category) ? "active" : "";

                        echo '
                        <a class="'.$active.'" href="productList.php?category='.urlencode($category).'">
                            '.$category.'
                        </a>';
                    }
                    ?>
                </div>
                <button style="background:none; border:none; padding: 0 15px; cursor:pointer;" type="button">
                    <i style="color: #666;" class="fa fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Shopee-style Product Grid Container -->
    <div class="featured">
        <div class="section-title">
            <?php 
                if(isset($_GET['search']) && !empty($_GET['search'])) {
                    echo "Search Results for: " . htmlspecialchars($_GET['search']);
                } else {
                    echo isset($_GET['category']) ? htmlspecialchars($_GET['category']) : "All Products"; 
                }
            ?>
        </div>
        <div class="featuredrow">
            <?php
                global $conn;
                
                // Fixed: Handle search queries alongside category filters
                if(isset($_GET['search']) && !empty($_GET['search']))
                {
                    $search = mysqli_real_escape_string($conn, $_GET['search']);
                    $sql = "SELECT * FROM product WHERE productName LIKE '%$search%'";
                }
                else if(isset($_GET['category']) && !empty($_GET['category']))
                {
                    $category = mysqli_real_escape_string($conn, $_GET['category']);
                    $sql = "SELECT * FROM product WHERE productCtgry='$category'";
                }
                else
                {
                    $sql = "SELECT * FROM product";
                }

                $result = mysqli_query($conn, $sql);
                
                if ($result && $result -> num_rows > 0)
                {
                    while ($row = $result -> fetch_assoc())
                    {
                        ?>
                            <div class="featuredcolumn">
                                <img src="<?php echo htmlspecialchars($row['productIMG']); ?>" alt="<?php echo htmlspecialchars($row['productName']); ?>">
                                <div style="font-size: 14px; color: #222; height: 38px; overflow: hidden; margin-bottom: 8px; line-height: 1.3;">
                                    <?php echo htmlspecialchars($row['productName']); ?>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                                    <span style="color: #ee4d2d; font-size: 16px; font-weight: 600;">
                                        <span style="font-size: 12px;">KR</span> <?php echo number_format($row['productPrice'], 2); ?>
                                    </span>
                                    <span style="font-size: 11px; color: #888;">Stock: <?php echo $row['productQty']; ?></span>
                                </div>
                                
                                <?php if ($row['productQty'] > 0) { ?>
                                    <a href="../user/addtoCart.php?addID=<?php echo $row['productID'];?>" class="shop-btn" style="display: block; text-align: center; width: 100%; padding: 6px 0;">
                                        <i class="fa fa-cart-plus"></i> Add to Cart
                                    </a>
                                <?php } else { ?>
                                    <span style="display: block; text-align: center; width: 100%; padding: 6px 0; background: #ccc; color: #666; font-size: 13px; font-weight: bold;">
                                        Out of Stock
                                    </span>
                                <?php } ?>
                            </div>
                        <?php
                    }
                }
                else
                {
                    echo '<p style="padding: 20px; color: #666;">No products found.</p>';
                }
            ?>
        </div>
    </div>

    <div class = "footer">
        <p>&copy; 2026 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>
</body>
</html>