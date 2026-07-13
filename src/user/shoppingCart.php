<?php

session_start();

include("../config/db_carngren.php");


if(!isset($_SESSION['userID']) || $_SESSION['logStatus'] != 1)
{
    header("Location: ../user/shoppingCart.php");
    exit();
}

$userID = $_SESSION['userID'];

?>

<!DOCTYPE HTML>
<html lang = "en">

<head>
	<meta charset = "UTF-8">
	<title>Arngren | Shopping Cart</title>

	<!---external CSS--->
	<link rel = "stylesheet" href = "style.css">

	<link rel="stylesheet" href="../../assets/css/shoppingCart.css">

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
						<li><a href="../user/productList.php"><i class = "fa fa-arrow-left"></i> Continue Browsing</a></li>
					</ul>
				</div>
				
				<div class = "logo">
					<a class = "active" href = "../auth/index.php">
					    <img src = "../../assets/images/logo.PNG" width = "125px">
                    </a>
				</div>
				
				<div class = "title">
					<h2>Shopping Cart <i class = "fa fa-shopping-cart"></i></h2>
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
	</div>

	<div class="content">
		<div class="cart-container">
			<div class="cart-products">
				<form id="deleteForm" action="clearCart.php" method="POST">
					<div class="select-card">
						<input type="checkbox" id="selectAll">
						Select All Products
					</div>

					<?php

					$total = 0;
					$sql = "

					SELECT

					cart.cartID,
					cart.orderQty,

					product.productName,
					product.productPrice,
					product.productIMG,
					product.productCtgry

					FROM cart

					INNER JOIN product

					ON cart.productID = product.productID

					WHERE cart.userID='$userID'

					";


					$result=mysqli_query($conn,$sql);
					while($row=mysqli_fetch_assoc($result)){
					$subtotal = $row['orderQty'] * $row['productPrice'];
					$total += $subtotal;

					?>

					<div class="shop-card">
							<!-- STORE HEADER -->
							<div class="shop-header">
								
							</div>

					<!-- PRODUCT BODY -->

					<div class="product-box" data-price="<?php echo $row['productPrice']; ?>">
						<input 
							type="checkbox"
							class="product-check"
							name="cartID[]"
							value="<?php echo $row['cartID']; ?>"
							data-price="<?php echo $row['productPrice']; ?>">
						<img src="<?php echo $row['productIMG'];?>" class="product-image">

						<div class="product-details">

						<h3>
							<?php echo $row['productName'];?>
						</h3>

						<p>
							<?php echo $row['productCtgry'];?>
						</p>

						<div class="price">
							KR <span class="product-price">
							<?php echo number_format($row['productPrice'],2);?>
							</span>
						</div>
					</div>

					<!-- QUANTITY -->
					<div class="quantity-area">
						<form action="updateCart.php" method="POST">
						<input type="hidden" name="cartID" value="<?php echo $row['cartID'];?>">

						<div class="qty-control">
							<button type="submit" name="action" value="minus">
							−
							</button>

							<input type="number" class="qty-input" name="qty" value="<?php echo $row['orderQty'];?>" min="1">

							<button type="submit" name="action" value="plus">
							+
							</button>
					</div>
						</form>
					</div>

					<button type="button" class="delete-btn" onclick="deleteSelected()">
						<i class="fa fa-trash"></i>
						Delete
					</button>
				</form>
			</div>
		</div>

		<?php } ?>

		</div>


		<!-- ORDER SUMMARY -->
		<div class="checkout-card">
			<h3>
			Order Details
			</h3>

			<div class="summary-row">
				<span>
				Price Total
				</span>

				<b id="priceTotal">
				KR <?php echo number_format($total,2);?>
				</b>
			</div>

			<div class="summary-row">
				<span>
				Discount
				</span>

				<b>
				KR 0.00
				</b>
			</div>

			<hr>

			<div class="summary-total">
				<span>
				Total
				</span>

				<b id="grandTotal">
				KR <?php echo number_format($total,2);?>
				</b>
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


	<div class = "footer">
		<p>&copy; 2021 ARNGREN. ALL RIGHTS RESERVED</p>
	</div>

	<script src="../../assets/js/shoppingCart.js"></script>
	
</body>
</html>