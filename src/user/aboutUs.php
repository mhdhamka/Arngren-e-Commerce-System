<?php 
    include ("../config/db_carngren.php");

	if(isset($_POST['submit'])){
		$fullName = $_POST['fullName'];
		$password = $_POST['password'];
		$email = $_POST['email'];

		submit($fullName, $password, $email);
	}
?>

<!DOCTYPE HTML>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<title>Arngren | Home</title>
    <link rel="stylesheet" href="../../assets/css/aboutUs.css">

	<script src="https://use.fontawesome.com/59805f286a.js"></script>

	<link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">

</head>

<body>
	<div class = "header">
		<div class = "headercontainer">
			<div class = "topnav">
			<h1>www.ARNGREN.net</h1>
				<div class = "centernav">
					<ul>
						<li><a class="" href="../auth/index.php">Home</a></li>
						<li>|</li>
						<li><a class="" href="../user/productList.php">Products</a></li>
						<li>|</li>
						<li><a class="active" href="../user/aboutUs.php">About Us</a></li>
					</ul>
				</div>
				
				<div class = "logo">
					<a class = "active" href = "../auth/index.php">
					    <img src = "../../assets/images/logo.PNG" width = "125px">
                    </a>
				</div>
				
				<div class = "searchbar">
					<input type = "text" class = "search" placeholder = "Search for products..">
					<div class = "searchbutton">
						<p>Search</p> 
					</div>
					<div class = "cart">
						<a href = "shoppingCart.php"><i style = "color: white" class = "fa fa-shopping-cart fa-2x"></i></a>
                    </div>
                    <div class = "message">
						<i style = "color: white" class = "fa fa-envelope fa-2x"></i>
                    </div>
                    <div class = "info">
						<i style = "color: white" class = "fa fa-gear fa-2x"></i>
                    </div>
				</div>
				
				<nav>
					<ul>
						<?php
						   global $conn;
						   $sql = "SELECT fullName FROM user WHERE logStatus = 1;";
						   $result = mysqli_query($conn, $sql);
						
						   if ($result -> num_rows > 0)
						   {
							  while ($row = $result -> fetch_assoc())
							  {
								?>
									<li><a href = "editprofile.php">My Profile</a></li>
									<li>|</li>
									<li><a href = "logoutUser.php">Log Out</a></li>
									<?php
							  }
							}
							else
							{
								?>
									<li><a href = "../auth/registration.php">Sign Up</a></li>
									<li>|</li>
									<li><a href = "../auth/login.php">Log In</a></li>
								<?php
							}
							?>
					</ul>
				</nav>
			</div>
		</div>
		
		<div class = "dashboard">
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

	<div class="content">
        <!-- About Banner -->
        <div class="about-banner">

            <img src="../../assets/images/background.JPG" alt="About Arngren">

            <div class="banner-title">
                <br>
                <h2>About Arngren</h2>
                <p>Norway's Marketplace for Unique Products</p>
            </div>

        </div>


        <!-- Who We Are -->

        <div class="about-section">
            <h2>Who We Are</h2>
            <p>
                Arngren is an online marketplace specializing in a wide range of unique products,
                including electric vehicles, scooters, hobby and RC equipment, DVD players,
                binoculars, and many more.

                Our goal is to provide customers with innovative products at competitive prices
                while delivering a smooth and enjoyable shopping experience.
            </p>
        </div>



        <!-- Mission Vision -->

        <div class="mission-row">
            <div class="mission-card">
                <i class="fa fa-bullseye fa-3x"></i>
                <h3>Our Mission</h3>

                <p>
                    To provide customers with innovative and affordable products while maintaining
                    excellent customer service.
                </p>
            </div>

            <div class="mission-card">
                <i class="fa fa-eye fa-3x"></i>
                <h3>Our Vision</h3>

                <p>
                    To become one of the world's leading online marketplaces for specialty
                    and electric products.
                </p>
            </div>

            <div class="mission-card">
                <i class="fa fa-heart fa-3x"></i>
                <h3>Our Values</h3>

                <p>
                    Innovation, Quality, Trust, Customer Satisfaction, and Continuous Improvement.
                </p>
            </div>
        </div>

        <!-- Why Choose -->

        <div class="why-us">

            <h2>Why Choose Arngren?</h2>

            <ul>

                <li>✔ Thousands of Unique Products</li>

                <li>✔ Affordable Prices</li>

                <li>✔ Secure Shopping Experience</li>

                <li>✔ Trusted Customer Support</li>

                <li>✔ Wide Product Categories</li>

            </ul>

        </div>



        <!-- Categories -->

        <div class="categories">

            <h2>Explore Our Categories</h2>

            <div class="category-grid">

                <div class="category-box">
                    <i class="fa fa-car fa-3x"></i>
                    <h4>Electric Vehicles</h4>
                </div>

                <div class="category-box">
                    <i class="fa fa-bicycle fa-3x"></i>
                    <h4>Scooters</h4>
                </div>

                <div class="category-box">
                    <i class="fa fa-gamepad fa-3x"></i>
                    <h4>Hobby & RC</h4>
                </div>

                <div class="category-box">
                    <i class="fa fa-truck fa-3x"></i>
                    <h4>Go-Karts</h4>
                </div>

                <div class="category-box">
                    <i class="fa fa-film fa-3x"></i>
                    <h4>DVD Players</h4>
                </div>

                <div class="category-box">
                    <i class="fa fa-binoculars fa-3x"></i>
                    <h4>Binoculars</h4>
                </div>

            </div>

        </div>



        <!-- Contact -->

        <div class="contact-about">
            <h2>Contact Us</h2>
            <p><i class="fa fa-envelope"></i> support@arngren.net</p>
            <p><i class="fa fa-phone"></i> +47 123 456 789</p>
            <p><i class="fa fa-map-marker"></i> Norway</p>
        </div>

        <br>
    </div>

	</div> 

	<div class = "footer">
		<p>&copy; 2021 ARNGREN. ALL RIGHTS RESERVED</p>
	</div>

	<script src="../../assets/js/index.js"></script>

</body>
</html>