<?php 
    session_start();
    include ("../config/db_carngren.php");
        
    if (!isset($_SESSION["userID"])) {
        header("Location: index.php");
        exit();
    }
        
    if(isset($_POST['save']))
    {
        $userID = $_SESSION['userID'];

        $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        $currentPassword = $_POST['currentpassword'];
        $newPassword = $_POST['newpassword'];
        $confirmPassword = $_POST['confirmpassword'];

        // Update Name & Email
        mysqli_query($conn,"
            UPDATE user
            SET
                fullName='$fullName',
                email='$email'
            WHERE userID='$userID'
        ");

        // Update session so header shows latest name
        $_SESSION['fullName'] = $fullName;
        $_SESSION['email'] = $email;

        // Update password only if user entered something
        if(!empty($currentPassword))
        {
            $result = mysqli_query($conn,"
                SELECT password
                FROM user
                WHERE userID='$userID'
            ");

            $user = mysqli_fetch_assoc($result);

            if(password_verify($currentPassword,$user['password']))
            {
                if($newPassword == $confirmPassword)
                {
                    $newPassword = password_hash($newPassword,PASSWORD_DEFAULT);

                    mysqli_query($conn,"
                        UPDATE user
                        SET password='$newPassword'
                        WHERE userID='$userID'
                    ");

                    echo "<script>alert('Profile and password updated successfully.');</script>";
                }
                else
                {
                    echo "<script>alert('New password and confirm password do not match.');</script>";
                }
            }
            else
            {
                echo "<script>alert('Current password is incorrect.');</script>";
            }
        }
        else
        {
            echo "<script>alert('Profile updated successfully.');</script>";
        }
    }

    // Calculate dynamic cart item count
    $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | Edit Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
    <link rel="stylesheet" href="../../assets/css/profile.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.PNG">
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

                <h1><a href="../auth/index.php">ARNGREN</a></h1>

                <div class="centernav">
                    <ul>
                        <li><a href="../auth/index.php">Home</a></li>
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
                <div class="cart header-icon-wrap">
                    <a href="../user/shoppingCart.php">
                        <i style="color: white" class="fa fa-shopping-cart fa-2x"></i>
                        <?php if($cartCount > 0): ?>
                            <span class="badge-counter"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <!-- Interactive Message Notification Modal Toggle -->
                <div class="message header-icon-wrap" onclick="toggleModal('msgModal')">
                    <i style="color: white" class="fa fa-envelope fa-2x"></i>
                    <div id="msgModal" class="dropdown-modal">
                        <h4 style="margin: 0 0 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px;">Notifications</h4>
                        <p style="font-size: 12px; color: #666; margin: 0;">No new notifications right now.</p>
                    </div>
                </div>

                <!-- Interactive Settings Modal Toggle -->
                <div class="info header-icon-wrap" onclick="toggleModal('settingsModal')">
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
        
        <!-- Category Dashboard Bar -->
        <div class="dashboard">
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

    <div class="content">
        <div class="container">
            <div class="header2">
                <h2>My Profile</h2>
                <p>Manage and protect your account</p>
            </div>
            
            <form class="form" method="POST" action="">
                <?php
                $userID = $_SESSION['userID'];

                $sql = "SELECT * FROM user WHERE userID='$userID'";
                $result = mysqli_query($conn,$sql);
                $user = mysqli_fetch_assoc($result);
                ?>

                <div class="innerform">
                    <label>Full Name</label>
                    <input type="text" name="fullName" value="<?php echo htmlspecialchars($user['fullName']); ?>">
                    <small class="error">
                        <?php echo $fullNameError ?? ''; ?>
                    </small>
                </div>

                <div class="innerform">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                    <small class="error">
                        <?php echo $emailError ?? ''; ?>
                    </small>
                </div>

                <br>
                <hr style="border:0; border-top:1px solid #eee;">
                <br>

                <div class="header2">
                    <h3 style="font-size: 18px; margin-bottom: 5px;">Change Password</h3>
                    <p style="margin-bottom: 15px;">Ensure your account is using a secure password</p>
                </div>

                <div class="innerform">
                    <label>Current Password</label>
                    <input type="password" name="currentpassword" placeholder="Enter current password">
                    <small class="error">
                        <?php echo $passwordError ?? ''; ?>
                    </small>
                </div>

                <div class="innerform">
                    <label>New Password</label>
                    <input type="password" name="newpassword" placeholder="Enter new password">
                    <small class="error">
                        <?php echo $password2Error ?? ''; ?>
                    </small>
                </div>

                <div class="innerform">
                    <label>Confirm Password</label>
                    <input type="password" name="confirmpassword" placeholder="Confirm new password">
                    <small class="error">
                        <?php echo $password3Error ?? ''; ?>
                    </small>
                </div>

                <div class="formfooter">
                    <input type="submit" name="save" value="Save" class="button">
                </div>
            </form>
        </div>
    </div>
    
    <div class="footer">
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
        
        if(searchInput && searchResults) {
            searchInput.addEventListener('input', function() {
                let query = this.value.trim();
                if(query.length > 1) {
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
        }
    </script>
</body>
</html>