<?php 
    session_start();
    include ("../config/db_carngren.php");

    // Handle form submissions if applicable on this page
    if(isset($_POST['submit'])){
        $fullName = $_POST['fullName'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        submit($fullName, $password, $email);
    }

    // Calculate dynamic cart item count
    $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE HTML>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <title>Arngren | Home</title>
    <link rel="stylesheet" href="../../assets/css/index.css">

    <script src="https://use.fontawesome.com/59805f286a.js"></script>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">

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
                        <li><a class="active" href="../auth/index.php">Home</a></li>
                        <li>|</li>
                        <li><a href="../user/productList.php">Products</a></li>
                        <li>|</li>
                        <li><a href="../user/aboutUs.php">About</a></li>
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
                <div class = "cart header-icon-wrap">
                    <a href = "../user/shoppingCart.php">
                        <i style = "color: white" class = "fa fa-shopping-cart fa-2x"></i>
                        <?php if($cartCount > 0): ?>
                            <span class="badge-counter"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <!-- Interactive Message Notification Modal Toggle -->
                <div class = "message header-icon-wrap" onclick="toggleModal('msgModal')">
                    <i style = "color: white" class = "fa fa-envelope fa-2x"></i>
                    <div id="msgModal" class="dropdown-modal">
                        <h4 style="margin: 0 0 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px;">Notifications</h4>
                        <p style="font-size: 12px; color: #666; margin: 0;">No new notifications right now.</p>
                    </div>
                </div>

                <!-- Interactive Settings Modal Toggle -->
                <div class = "info header-icon-wrap" onclick="toggleModal('settingsModal')">
                    <i style = "color: white" class = "fa fa-gear fa-2x"></i>
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
        
        <!-- Category Dashboard Bar -->
        <div class = "dashboard">
            <div class="bottomnav-container">
                <button id="leftBtn" style="background: none; border: none; padding: 0 15px; cursor: pointer; color: #555;">
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

                    foreach($categories as $category) {
                        $active = ($currentCategory == $category) ? "active" : "";
                        echo '
                        <a class="'.$active.'" href="../user/productList.php?category='.urlencode($category).'">
                            '.$category.'
                        </a>';
                    }
                    ?>
                </div>

                <button id="rightBtn" style="background: none; border: none; padding: 0 15px; cursor: pointer; color: #555;">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <div class = "content">

        <!-- Hero Slider Section -->
        <div class="hero-slider">
            <div class="slide active-slide">
                <div class="hero-text">
                    <h1>Electric ATV</h1>
                    <p>Explore powerful electric vehicles</p>
                    <a class="shop-btn" href="../user/productList.php?category=Electric%20Vehicles">Shop Now</a>
                </div>
                <img src="../../assets/images/FeaturedATV.PNG" alt="Electric ATV">
            </div>

            <div class="slide">
                <div class="hero-text">
                    <h1>Electric Go-Kart</h1>
                    <p>Fun and speed for everyone</p>
                    <a class="shop-btn" href="../user/productList.php?category=Go-Kart">Shop Now</a>
                </div>
                <img src="../../assets/images/gokart.JPG" alt="Go-Kart">
            </div>

            <div class="slide">
                <div class="hero-text">
                    <h1>Electric Jeep</h1>
                    <p>Premium electric vehicles</p>
                    <a class="shop-btn" href="../user/productList.php?category=Jeep">Shop Now</a>
                </div>
                <img src="../../assets/images/jeep.JPG" alt="Jeep">
            </div>

            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
        </div>

        <!-- Featured Products -->
        <div class = "featured">
            <h2 class="section-title">Featured Products</h2>
            <div class = "featuredrow">
                
                <!-- 1st Featured Product -->
                <div class = "featuredcolumn"> 
                    <a href = "../user/productList.php?category=Electric%20Vehicles">
                        <img src = "../../assets/images/ATV.PNG" alt="ATV">
                    </a>
                    <h4 style = "color: #ee4d2d">Electric ATV</h4>   
                    <div class = "featuredrating">
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                    </div>
                    <p>$ 1,670.97</p>
                    <a href="../user/productList.php?category=Electric%20Vehicles" class="read-btn" style="margin-top: 10px;">See more</a>
                </div>
                
                <!-- 2nd Featured Product -->
                <div class = "featuredcolumn"> 
                    <a href = "../user/productList.php?category=Go-Kart">
                        <img src = "../../assets/images/gokart.JPG" alt="Go-Kart">
                    </a>
                    <h4 style = "color: #ee4d2d">Electric Go-Kart</h4>
                    <div class = "featuredrating">
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                    </div>
                    <p>$ 1,367.18</p>
                    <a href="../user/productList.php?category=Go-Kart" class="read-btn" style="margin-top: 10px;">See more</a>
                </div>

                <!-- 3rd Featured Product -->
                <div class = "featuredcolumn"> 
                    <a href = "../user/productList.php?category=Jeep">
                        <img src = "../../assets/images/jeep.JPG" alt="Jeep">
                    </a>
                    <h4 style = "color: #ee4d2d">Electric Jeep & Golf Car</h4>
                    <div class = "featuredrating">
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star-half"></i>
                    </div>
                    <p>$ 13,674.55</p>
                    <a href="../user/productList.php?category=Jeep" class="read-btn" style="margin-top: 10px;">See more</a>
                </div>

                <!-- 4th Featured Product -->
                <div class = "featuredcolumn"> 
                    <a href = "../user/productList.php?category=Hobby+%26+RC">
                        <img src = "../../assets/images/hobby.JPG" alt="Hobby">
                    </a>
                    <h4 style = "color: #ee4d2d">Electric T-Truck with open box</h4>
                    <div class = "featuredrating">
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                        <i style = "color: #ecd846" class = "fa fa-star"></i>
                    </div>
                    <p>$ 18,233.16</p>
                    <a href="../user/productList.php?category=Hobby+%26+RC" class="read-btn" style="margin-top: 10px;">See more</a>
                </div>

            </div>
        </div>

        <!-- Featured Articles -->
        <div class="articles">
            <h2 class="section-title">Featured Articles</h2>
            <div class="articlerow">

                <!-- Article 1 -->
                <div class="articlecard">
                    <img src="../../assets/images/art1.jpg" alt="Electric Scooter">
                    <div class="articlecontent">
                        <h3>How to Choose the Right Electric Scooter</h3>
                        <p>
                            Learn what features to consider before purchasing an electric scooter,
                            including battery life, speed, safety, and overall value.
                        </p>
                        <a href="https://iscooterglobal.com.au/blogs/news/how-to-choose-the-right-electric-scooter-a-practical-buying-guide" class="read-btn">
                            Read More
                        </a>
                    </div>
                </div>

                <!-- Article 2 -->
                <div class="articlecard">
                    <img src="../../assets/images/art2.jpg" alt="Electric Vehicles">
                    <div class="articlecontent">
                        <h3>Top 5 Electric Vehicles for Beginners</h3>
                        <p>
                            Compare some of the best beginner-friendly electric vehicles available,
                            their specifications, pricing, and recommended uses.
                        </p>
                        <a href="https://www.topspeed.com/easiest-to-use-evs-for-beginners/" class="read-btn">
                            Read More
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div> 

    <div class = "footer">
        <p>&copy; 2026 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>

    <script src="../../assets/js/index.js"></script>
    <script>
        // Toggle dropdown modals safely
        function toggleModal(modalId) {
            event.stopPropagation();
            const modal = document.getElementById(modalId);
            // Close other open modals first
            document.querySelectorAll('.dropdown-modal').forEach(m => {
                if(m.id !== modalId) m.classList.remove('active');
            });
            modal.classList.toggle('active');
        }

        // Close dropdowns when clicking outside
        window.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-modal').forEach(m => m.classList.remove('active'));
        });

        // Simple live-search simulation hook for input UX
        const searchInput = document.getElementById('liveSearchInput');
        const searchResults = document.getElementById('searchResults');
        
        searchInput.addEventListener('input', function() {
            let query = this.value.trim();
            if(query.length > 1) {
                // Mock dynamic suggestion behavior
                searchResults.innerHTML = `<a href="../user/productList.php?search=${encodeURIComponent(query)}">Search results for "<strong>${query}</strong>"</a>`;
                searchResults.style.display = 'block';
            } else {
                searchResults.style.display = 'none';
            }
        });

        document.addEventListener('click', function(e) {
            if(!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    </script>
</body>
</html>