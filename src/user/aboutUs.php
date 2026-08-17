<?php 
    session_start();
    include ("../config/db_carngren.php");

    // Fetch cart count if user session is active (adjust based on your cart table/logic if needed)
    $cartCount = 0;
    if(isset($_SESSION['userID'])) {
        $uID = $_SESSION['userID'];
        $cartSql = "SELECT SUM(orderQty) as totalQty FROM cart WHERE userID = '$uID'";
        $cartRes = mysqli_query($conn, $cartSql);
        if($cartRes && $row = mysqli_fetch_assoc($cartRes)){
            $cartCount = $row['totalQty'] ? $row['totalQty'] : 0;
        }
    }
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | About Us</title>
    <link rel="stylesheet" href="../../assets/css/aboutUs.css">

    <script src="https://use.fontawesome.com/59805f286a.js"></script>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">
</head>

<body>
    <div class="header">
        <div class="headercontainer">
            <div class="topnav">
                <div class="logo">
                    <a class="active" href="../auth/index.php">
                        <img src="../../assets/images/logo.PNG" width="95px" alt="Logo">
                    </a>
                </div>

                <h1><a href="../auth/index.php" style="color: inherit; text-decoration: none;">ARNGREN</a></h1>
                
                <div class="centernav">
                    <ul>
                        <li><a href="../auth/index.php">Home</a></li>
                        <li>|</li>
                        <li><a href="../user/productList.php">Products</a></li>
                        <li>|</li>
                        <li><a class="active" href="../user/aboutUs.php">About</a></li>
                    </ul>
                </div>
                
                <!-- Modernized Search Form with Live Query Container -->
                <div style="position: relative; display: inline-block;">
                    <form action="../user/productList.php" method="GET" class="search-container" style="display: flex; align-items: center; margin: 0;">
                        <input type="text" name="search" id="liveSearchInput" class="search" placeholder="Search for products.." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" autocomplete="off">
                        <button type="submit" class="searchbutton">Search</button> 
                    </form>
                    <div id="searchResults" class="search-results-dropdown"></div>
                </div>

                <!-- Interactive Cart with Live Badge Counter -->
                <div class="cart header-icon-wrap">
                    <a href="../user/shoppingCart.php">
                        <i style="color: white" class="fa fa-shopping-cart fa-2x"></i>
                        <?php if($cartCount > 0): ?>
                            <span class="badge-counter"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <!-- Interactive Message Notification Modal Toggle -->
                <div class="message header-icon-wrap" onclick="toggleModal('msgModal')" style="cursor: pointer;">
                    <i style="color: white" class="fa fa-envelope fa-2x"></i>
                    <div id="msgModal" class="dropdown-modal">
                        <h4 style="margin: 0 0 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px;">Notifications</h4>
                        <p style="font-size: 12px; color: #666; margin: 0;">No new notifications right now.</p>
                    </div>
                </div>

                <!-- Interactive Settings Modal Toggle -->
                <div class="info header-icon-wrap" onclick="toggleModal('settingsModal')" style="cursor: pointer;">
                    <i style="color: white" class="fa fa-gear fa-2x"></i>
                    <div id="settingsModal" class="dropdown-modal">
                        <h4 style="margin: 0 0 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px;">Quick Settings</h4>
                        <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px;">
                            <li style="padding: 5px 0;"><a href="../user/profile.php" style="color:#333; text-decoration:none;">Account Settings</a></li>
                            <li style="padding: 5px 0;"><a href="../user/aboutUs.php" style="color:#333; text-decoration:none;">Help & Support</a></li>
                        </ul>
                    </div>
                </div>
                
                <nav class="user-nav">
                    <ul>
                    <?php if(isset($_SESSION['userID'])) { ?>
                        <li>
                            <span>Welcome, <?php echo htmlspecialchars($_SESSION['fullName']); ?></span>
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
        
        <div class="dashboard">
            <div class="bottomnav-container">
                <button id="leftBtn">
                    <i class="fa fa-chevron-left"></i>
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
                        <a class="'.$active.'" href="../user/productList.php?category='.urlencode($category).'">
                            '.$category.'
                        </a>';
                    }
                    ?>
                </div>
                <button id="rightBtn">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- REDESIGNED CONTENT SECTION START -->
    <div class="content" style="background-color: #f8f9fa; padding-bottom: 60px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
        
        <!-- Modern Hero Banner -->
        <div class="about-banner" style="position: relative; overflow: hidden; border-bottom: 4px solid #ee4d2d; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <img src="../../assets/images/background.JPG" alt="About Arngren" style="width: 100%; height: 420px; object-fit: cover; filter: brightness(0.85);">
            <div class="banner-title" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; width: 100%; padding: 0 20px;">
                <span style="background-color: #ee4d2d; color: white; padding: 6px 16px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; border-radius: 20px; display: inline-block; margin-bottom: 12px;">Established Marketplace</span>
                <h2 style="font-size: 48px; color: white; font-weight: 800; text-shadow: 0 3px 6px rgba(0,0,0,0.4); margin: 0;">About Arngren</h2>
                <p style="font-size: 18px; color: #f1f1f1; margin-top: 10px; font-weight: 500; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Norway's Premier Marketplace for Unique & Specialty Products</p>
            </div>
        </div>

        <div style="max-width: 1150px; margin: 0 auto; padding: 0 20px;">

            <!-- Who We Are (Redesigned Split Layout) -->
            <div class="about-section" style="background: white; border-radius: 12px; padding: 50px; margin-top: -50px; position: relative; z-index: 10; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eaeaea; text-align: left;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                    <div style="width: 6px; height: 28px; background-color: #ee4d2d; border-radius: 3px;"></div>
                    <h2 style="font-size: 28px; color: #222; font-weight: 700; margin: 0;">Who We Are</h2>
                </div>
                <p style="font-size: 16px; line-height: 1.8; color: #555; margin: 0;">
                    Arngren is an online marketplace specializing in a wide range of unique products, including cutting-edge electric vehicles, scooters, high-performance hobby and RC equipment, DVD players, premium binoculars, and much more. Our ultimate goal is to provide customers with innovative, hard-to-find products at highly competitive prices while delivering a smooth, secure, and enjoyable online shopping experience from start to finish.
                </p>
            </div>

            <!-- Mission Vision Cards (Redesigned Grid) -->
            <div class="mission-row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; margin-top: 40px;">
                <div class="mission-card" style="background: white; padding: 40px 30px; border-radius: 12px; border: 1px solid #eaeaea; box-shadow: 0 6px 20px rgba(0,0,0,0.03); text-align: center; transition: all 0.3s ease;">
                    <div style="width: 70px; height: 70px; background: #fff5f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fa fa-bullseye fa-2x" style="color: #ee4d2d;"></i>
                    </div>
                    <h3 style="font-size: 20px; color: #222; font-weight: 700; margin-bottom: 12px;">Our Mission</h3>
                    <p style="font-size: 14px; color: #666; line-height: 1.6; margin: 0;">
                        To provide customers with innovative and affordable products while maintaining exceptional standards of customer service and reliability.
                    </p>
                </div>

                <div class="mission-card" style="background: white; padding: 40px 30px; border-radius: 12px; border: 1px solid #eaeaea; box-shadow: 0 6px 20px rgba(0,0,0,0.03); text-align: center; transition: all 0.3s ease;">
                    <div style="width: 70px; height: 70px; background: #fff5f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fa fa-eye fa-2x" style="color: #ee4d2d;"></i>
                    </div>
                    <h3 style="font-size: 20px; color: #222; font-weight: 700; margin-bottom: 12px;">Our Vision</h3>
                    <p style="font-size: 14px; color: #666; line-height: 1.6; margin: 0;">
                        To become one of the world's leading and most trusted online marketplaces for specialty, niche, and electric mobility products.
                    </p>
                </div>

                <div class="mission-card" style="background: white; padding: 40px 30px; border-radius: 12px; border: 1px solid #eaeaea; box-shadow: 0 6px 20px rgba(0,0,0,0.03); text-align: center; transition: all 0.3s ease;">
                    <div style="width: 70px; height: 70px; background: #fff5f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fa fa-heart fa-2x" style="color: #ee4d2d;"></i>
                    </div>
                    <h3 style="font-size: 20px; color: #222; font-weight: 700; margin-bottom: 12px;">Our Values</h3>
                    <p style="font-size: 14px; color: #666; line-height: 1.6; margin: 0;">
                        Driven by Innovation, superior Quality, absolute Trust, stellar Customer Satisfaction, and Continuous Improvement.
                    </p>
                </div>
            </div>

            <!-- Why Choose Us -->
            <div class="why-us" style="background: linear-gradient(135deg, #fff5f2 0%, #ffece7 100%); border: 1px solid #ffd8d1; border-radius: 12px; padding: 40px; margin-top: 40px; box-shadow: 0 6px 20px rgba(238,77,45,0.04);">
                <h2 style="font-size: 24px; color: #222; font-weight: 700; margin-bottom: 25px; text-align: center;">Why Choose Arngren?</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
                    <div style="background: white; padding: 18px 20px; border-radius: 8px; display: flex; align-items: center; gap: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                        <i class="fa fa-check-circle" style="color: #ee4d2d; font-size: 20px;"></i>
                        <span style="font-size: 14px; font-weight: 600; color: #333;">Thousands of Unique Products</span>
                    </div>
                    <div style="background: white; padding: 18px 20px; border-radius: 8px; display: flex; align-items: center; gap: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                        <i class="fa fa-check-circle" style="color: #ee4d2d; font-size: 20px;"></i>
                        <span style="font-size: 14px; font-weight: 600; color: #333;">Competitive & Affordable Prices</span>
                    </div>
                    <div style="background: white; padding: 18px 20px; border-radius: 8px; display: flex; align-items: center; gap: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                        <i class="fa fa-check-circle" style="color: #ee4d2d; font-size: 20px;"></i>
                        <span style="font-size: 14px; font-weight: 600; color: #333;">Secure Shopping Experience</span>
                    </div>
                    <div style="background: white; padding: 18px 20px; border-radius: 8px; display: flex; align-items: center; gap: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                        <i class="fa fa-check-circle" style="color: #ee4d2d; font-size: 20px;"></i>
                        <span style="font-size: 14px; font-weight: 600; color: #333;">Trusted Customer Support</span>
                    </div>
                </div>
            </div>

            <!-- Categories Showcase -->
            <div class="categories" style="margin-top: 50px;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <h2 style="font-size: 26px; color: #222; font-weight: 700; margin-bottom: 8px;">Explore Our Core Categories</h2>
                    <p style="font-size: 14px; color: #666; margin: 0;">Discover top-tier equipment curated specifically for enthusiasts</p>
                </div>
                <div class="category-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 20px;">
                    
                    <a href="../user/productList.php?category=Electric+Vehicles" style="text-decoration: none;">
                        <div class="category-box" style="background: white; padding: 25px 15px; text-align: center; border-radius: 10px; border: 1px solid #eaeaea; transition: all 0.25s ease; box-shadow: 0 3px 10px rgba(0,0,0,0.02);">
                            <i class="fa fa-car fa-2x" style="color: #ee4d2d; margin-bottom: 12px;"></i>
                            <h4 style="font-size: 14px; color: #333; font-weight: 600; margin: 0;">Electric Vehicles</h4>
                        </div>
                    </a>

                    <a href="../user/productList.php?category=Scooter" style="text-decoration: none;">
                        <div class="category-box" style="background: white; padding: 25px 15px; text-align: center; border-radius: 10px; border: 1px solid #eaeaea; transition: all 0.25s ease; box-shadow: 0 3px 10px rgba(0,0,0,0.02);">
                            <i class="fa fa-bicycle fa-2x" style="color: #ee4d2d; margin-bottom: 12px;"></i>
                            <h4 style="font-size: 14px; color: #333; font-weight: 600; margin: 0;">Scooters</h4>
                        </div>
                    </a>

                    <a href="../user/productList.php?category=Hobby+%26+RC" style="text-decoration: none;">
                        <div class="category-box" style="background: white; padding: 25px 15px; text-align: center; border-radius: 10px; border: 1px solid #eaeaea; transition: all 0.25s ease; box-shadow: 0 3px 10px rgba(0,0,0,0.02);">
                            <i class="fa fa-gamepad fa-2x" style="color: #ee4d2d; margin-bottom: 12px;"></i>
                            <h4 style="font-size: 14px; color: #333; font-weight: 600; margin: 0;">Hobby & RC</h4>
                        </div>
                    </a>

                    <a href="../user/productList.php?category=Go-Kart" style="text-decoration: none;">
                        <div class="category-box" style="background: white; padding: 25px 15px; text-align: center; border-radius: 10px; border: 1px solid #eaeaea; transition: all 0.25s ease; box-shadow: 0 3px 10px rgba(0,0,0,0.02);">
                            <i class="fa fa-truck fa-2x" style="color: #ee4d2d; margin-bottom: 12px;"></i>
                            <h4 style="font-size: 14px; color: #333; font-weight: 600; margin: 0;">Go-Karts</h4>
                        </div>
                    </a>

                    <a href="../user/productList.php?category=DVD-Player" style="text-decoration: none;">
                        <div class="category-box" style="background: white; padding: 25px 15px; text-align: center; border-radius: 10px; border: 1px solid #eaeaea; transition: all 0.25s ease; box-shadow: 0 3px 10px rgba(0,0,0,0.02);">
                            <i class="fa fa-film fa-2x" style="color: #ee4d2d; margin-bottom: 12px;"></i>
                            <h4 style="font-size: 14px; color: #333; font-weight: 600; margin: 0;">DVD Players</h4>
                        </div>
                    </a>

                    <a href="../user/productList.php?category=Binoculars" style="text-decoration: none;">
                        <div class="category-box" style="background: white; padding: 25px 15px; text-align: center; border-radius: 10px; border: 1px solid #eaeaea; transition: all 0.25s ease; box-shadow: 0 3px 10px rgba(0,0,0,0.02);">
                            <i class="fa fa-binoculars fa-2x" style="color: #ee4d2d; margin-bottom: 12px;"></i>
                            <h4 style="font-size: 14px; color: #333; font-weight: 600; margin: 0;">Binoculars</h4>
                        </div>
                    </a>

                </div>
            </div>

            <!-- Contact Section -->
            <div class="contact-about" style="background: white; margin-top: 50px; padding: 40px; border-radius: 12px; border: 1px solid #eaeaea; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <h2 style="font-size: 24px; color: #222; font-weight: 700; margin-bottom: 20px;">Get in Touch With Us</h2>
                <div style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;">
                    <p style="margin: 0; color: #555; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa fa-envelope" style="color: #ee4d2d;"></i> support@arngren.net
                    </p>
                    <p style="margin: 0; color: #555; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa fa-phone" style="color: #ee4d2d;"></i> +47 123 456 789
                    </p>
                    <p style="margin: 0; color: #555; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa fa-map-marker" style="color: #ee4d2d;"></i> Norway
                    </p>
                </div>
            </div>

        </div>
    </div>
    <!-- REDESIGNED CONTENT SECTION END -->

    <div class="footer">
        <p>&copy; 2026 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>

    <script src="../../assets/js/index.js"></script>
</body>
</html>