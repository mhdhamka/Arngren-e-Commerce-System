<?php 
    session_start();
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
    <link rel="stylesheet" href="../../assets/css/index.css">

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
						<li><a class="active" href="../auth/index.php">Home</a></li>
						<li>|</li>
						<li><a class="" href="../user/productList.php">Products</a></li>
						<li>|</li>
						<li><a class="" href="../user/aboutUs.php">About Us</a></li>
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
						<a href = "../user/shoppingCart.php"><i style = "color: white" class = "fa fa-shopping-cart fa-2x"></i></a>
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

					<?php if(isset($_SESSION['userID'])) { ?>

					    <li>
							<span>
								Welcome,
								<?php echo $_SESSION['fullName']; ?>
							</span>
						</li>
						<li>
							<a href="../user/profile.php">
								My Profile
							</a>
						</li>
						<li>|</li>
						<li>
							<a href="../auth/logout.php">
								Log Out
							</a>
						</li>

					<?php } else { ?>

						<li>
							<a href="../auth/registration.php">
								Sign Up
							</a>
						</li>
						<li>|</li>
						<li>
							<a href="../auth/login.php">
								Log In
							</a>
						</li>

					<?php } ?>

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

	<div class = "content">

		<div class="hero-slider">

		<div class="slide active-slide">
			<div class="hero-text">
				<h1>Electric ATV</h1>
				<p>Explore powerful electric vehicles</p>
			</div>

			<img src="../../assets/images/FeaturedATV.PNG">

			<a class="shop-btn" href="../user/productList.php?category=Electric%20Vehicles">
				Shop Now
			</a>

		</div>


		<div class="slide active-slide">
			<div class="hero-text">
				<h1>Electric Go-Kart</h1>
				<p>Fun and speed for everyone</p>
			</div>

				<img src="../../assets/images/gokart.JPG">
				<a class="shop-btn" href="../user/productList.php?category=Go-Kart">
					Shop Now
				</a>
		</div>


		<div class="slide">
			<div class="hero-text">
				<h1>Electric Jeep</h1>
				<p>Premium electric vehicles</p>
			</div>

				<img src="../../assets/images/jeep.JPG">
				<a class="shop-btn" href="../user/productList.php?category=Jeep">
					Shop Now
				</a>
		</div>


		<button class="prev">&#10094;</button>
		<button class="next">&#10095;</button>

	</div>

	
	    <!---Featured Products--->
		<div class = "featured">
			<h2 class="section-title">Featured Products</h2>
			<div class = "featuredrow">
				<!---1st Featured Product--->
				<div class = "featuredcolumn"> 
					<a class = "active" href = "../user/productList.php?category=Electric%20Vehicles">
					<img src = "../../assets/images/ATV.PNG">
				    </a>
					<h4 style = "color: #c45b56">Electric ATV<h4>	
					<div class = "featuredrating">
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
					</div>
					<p>$ 1,670.97</p>
		            <a href="../user/productList.php?category=Electric%20Vehicles">See more<br></a>
		            <br><br>
				</div>
                
                <!---2nd Featured Product--->
				<div class = "featuredcolumn"> 
					<a class = "active" href = "../user/productList.php?category=Go-Kart">
					<img src = "../../assets/images/gokart.JPG">
					</a>
					<h4 style = "color: #c45b56">Electric Go-Kart<h4>
					<div class = "featuredrating">
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
					</div>
					<p>$ 1,367.18</p>
					<a href="../user/productList.php?category=Go-Kart">See more<br></a>
		            <br><br>
				</div>

				<!---3rd Featured Product--->
				<div class = "featuredcolumn"> 
					<a class = "active" href = "../user/productList.php?category=Jeep">
					<img src = "../../assets/images/jeep.JPG">
				    </a>
					<h4 style = "color: #c45b56">Electric Jeep & Golf Car<h4>
					<div class = "featuredrating">
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star-half"></i>
					</div>
					<p>$ 13,674.55</p>
					<a href="../user/productList.php?category=Jeep">See more<br></a>
		            <br><br>
				</div>

				<!---4th Featured Product--->
				<div class = "featuredcolumn"> 
					<a class = "active" href = "../user/productList.php?category=Hobby+%26+RC">
					<img src = "../../assets/images/hobby.JPG">
				    </a>
					<h4 style = "color: #c45b56">Electric T-Truck with open box <h4>
					<div class = "featuredrating">
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
						<i style = "color: #ecd846" class = "fa fa-star"></i>
					</div>
					<p>$ 18,233.16</p>
					<a href="../user/productList.php?category=Hobby+%26+RC">See more<br></a>
		            <br><br>
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

		<br>

	</div> 

	<div class = "footer">
		<p>&copy; 2021 ARNGREN. ALL RIGHTS RESERVED</p>
	</div>

	<script src="../../assets/js/index.js"></script>

</body>
</html>