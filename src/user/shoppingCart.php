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
						<?php
						   global $conn;
						   $sql = "SELECT fullName FROM user WHERE logStatus = 1;";
						   $result = mysqli_query($conn, $sql);
						
						   if ($result -> num_rows > 0)
						   {
							  while ($row = $result -> fetch_assoc())
							  {
								?>
									<li><a href = "../user/profile.php">My Profile</a></li>
									<li>|</li>
									<li><a href = "../auth/logout.php">Log Out</a></li>
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
	</div>

	<div class = "content">
		<form>
			<table class="cart-table">
			<tr>
				<th></th>
				<th>Product</th>
				<th>Price</th>
				<th>Quantity</th>
				<th>Subtotal (KR)</th>
			</tr>

			<?php

			$total = 0;
			$sql = "

			SELECT

			cart.cartID,
			cart.orderQty,

			product.productName,
			product.productPrice,
			product.productIMG

			FROM cart

			INNER JOIN product

			ON cart.productID = product.productID

			WHERE cart.userID='$userID'

			";

			$result=mysqli_query($conn,$sql);

			if(mysqli_num_rows($result)>0)
			{
			while($row=mysqli_fetch_assoc($result))

			{

			$subtotal = $row['orderQty'] * $row['productPrice'];
			$total += $subtotal;

			?>

			<tr>


			<td>
			    <a href="removeCart.php?cartID=<?php echo $row['cartID']; ?>">
			        <i class="fa fa-minus-square"></i>
			    </a>
			</td>



			<td>
          		<img src="<?php echo $row['productIMG']; ?>" width="80">
			     <br>

			    <?php echo $row['productName']; ?>

			</td>



			<td>

			KR <?php echo number_format($row['productPrice'],2); ?>

			</td>




			<td>

			<form action="updateCart.php" method="POST">


			<input 

			type="number"

			name="qty"

			value="<?php echo $row['orderQty']; ?>"

			min="1">


			<input 

			type="hidden"

			name="cartID"

			value="<?php echo $row['cartID']; ?>">



			<button type="submit">

			Update

			</button>


			</form>


			</td>



			<td>

			KR <?php echo number_format($subtotal,2); ?>

			</td>


			</tr>


			<?php

			}

			?>


			<tr>

			<td colspan="4" align="right">

			<b>Total</b>

			</td>


			<td>

			<b>

			KR <?php echo number_format($total,2); ?>

			</b>

			</td>


			</tr>


			</table>



			<div class="btn">

			<a href="clearCart.php">

			<i class="fa fa-trash"></i>

			Clear Cart

			</a>



			<a href="payment.php">

			<i class="fa fa-credit-card"></i>

			Checkout

			</a>


			</div>



			<?php

			}

			else

			{

			?>


			<h3 style="text-align:center">

			Your cart is empty.

			</h3>


			<?php

			}

			?>


			</form>
        </div>
	<div class = "footer">
		<p>&copy; 2021 ARNGREN. ALL RIGHTS RESERVED</p>
	</div>

	
</body>
</html>