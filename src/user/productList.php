<?php include ("../config/db_carngren.php");

	session_start();
	//initialize cart if not set or is unset
	if(!isset($_SESSION['cart'])){
		$_SESSION['cart'] = array();
	}
	//unset qunatity
	unset($_SESSION['qty_array']);
?>

<!DOCTYPE HTML>
<html lang = "en">

  <!---Code for other page (electric vehicles)--->

<head>
	<meta charset = "UTF-8">
	<title>Arngren | Products | Electric Vehicles</title>

	<!---external CSS--->
	<link rel = "stylesheet" href = "style.css">

	<link rel="stylesheet" href="../../assets/css/index.css">

	<script src="https://use.fontawesome.com/59805f286a.js"></script>
	<link rel="icon" type="image/x-icon" href="../../assets/images/logo.PNG">
	
</head>

<body>
<div class = "header">
		<div class = "headercontainer">
			<div class = "topnav">
			<h1>www.ARNGREN.net</h1>
				<div class = "centernav">
					<ul>
						<li><a href="../auth/index.php">Home</a></li>
						<li>|</li>
						<li><a class="active" href="../user/productList.php">Products</a></li>
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
			<div class="bottomnav">
				<i style="color: white" class="fa fa-chevron-left"></i>

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
				<i style="color: white" class="fa fa-chevron-right"></i>
			</div>
		</div>
	</div>

	<div class = "content">
		<div class="row">
		<div class="leftcolumn">
			<?php
				global $conn;
				if(isset($_GET['category']))
				{
					$category = $_GET['category'];

					$sql = "SELECT * FROM product 
							WHERE productCtgry='$category'";
				}
				else
				{
					$sql = "SELECT * FROM product";
				}

				$result = mysqli_query($conn, $sql);
				
				if ($result -> num_rows > 0)
				{
					while ($row = $result -> fetch_assoc())
					{
						?>
							
							<div class = "card">
								<h2 style = "font-family: Helvetica;"><?php echo $row['productName']; ?></h2> <br><br>
								<img src="<?php echo $row['productIMG']?>" width="40%" style = "border: 5px solid #c45b56; "></td> <br><br>
								<span style = "font-weight: bold;">Price (KR): </span><?php echo $row['productPrice']; ?> <br><br>
								<span style = "font-weight: bold;">Stock: </span><?php echo $row['productQty']; ?> <br><br>
								<button style = "background: #c45b56; padding: 4px;"><a href="../user/addtoCart.php?addID=<?php echo $row['productID'];?>" style = "text-decoration: none; color: #ecd846;"><i class = "fa fa-cart-plus"></i> Add to Cart</a></button> <br><br>
							</div>
							
					<?php
					}
					
				}
			?>
		</div>
		</div>

		
		</div>
	</div>

	<div class = "footer">
		<p>&copy; 2021 ARNGREN. ALL RIGHTS RESERVED</p>
	</div>
</body>
</html>