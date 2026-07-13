<?php

session_start();

include("../config/db_carngren.php");


if(!isset($_SESSION['userID']))
{
    header("Location: ../auth/login.php");
    exit();
}


$userID = $_SESSION['userID'];



if(!isset($_POST['selectedCart']))
{
    header("Location: shoppingCart.php");
    exit();
}



$cartIDs = $_POST['selectedCart'];

$cartIDs = explode(",", $cartIDs);



$idList = implode(",", $cartIDs);



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

AND cart.cartID IN ($idList)

";



$result=mysqli_query($conn,$sql);



$total = 0;

$productList=[];


while($row=mysqli_fetch_assoc($result))
{

    $subtotal =
    $row['orderQty'] *
    $row['productPrice'];


    $total += $subtotal;


    $productList[]=$row['productName'];

}



if($total==0)
{
    header("Location: shoppingCart.php");
    exit();
}


?>



<!DOCTYPE HTML>
<html>

<head>

<title>
Arngren | Payment
</title>


<link rel="stylesheet" href="../../assets/css/payment.css">

</head>


<body>


<div class="payment-container">


<h2>
Billing & Payment
</h2>



<form action="receipt.php" method="POST">



<h3>
Billing Address
</h3>


<input 
type="hidden"
name="cartIDs"
value="<?php echo implode(",",$cartIDs); ?>">


<input 
type="hidden"
name="total"
value="<?php echo $total; ?>">


<input 
type="hidden"
name="productName"
value="<?php echo implode(",",$productList); ?>">



<label>
Full Name
</label>


<input 
type="text"
name="fullname"
value="<?php echo $_SESSION['fullName']; ?>"
required>




<label>
Email
</label>


<input 
type="email"
name="email"
value="<?php echo $_SESSION['email']; ?>"
required>




<label>
Address
</label>


<input 
type="text"
name="address"
required>




<label>
City
</label>


<input 
type="text"
name="city"
required>




<label>
State
</label>


<input 
type="text"
name="state"
required>




<label>
Zip Code
</label>


<input 
type="text"
name="zip"
required>





<h3>
Payment
</h3>


<label>
Card Number
</label>


<input 
type="text"
name="cardnumber"
placeholder="1111-2222-3333-4444"
required>



<label>
Expiry
</label>


<input 
type="text"
name="expiry"
required>



<label>
CVV
</label>


<input 
type="text"
name="cvv"
required>




<hr>


<h3>
Order Summary
</h3>


<p>
Product Total:
<b>
KR <?php echo number_format($total,2);?>
</b>
</p>



<p>
Tax (6%):
<b>
KR <?php echo number_format($total*0.06,2);?>
</b>
</p>



<h2>

Total:

KR <?php echo number_format($total*1.06,2);?>

</h2>



<button 
type="submit"
name="insert_records">

Pay Now

</button>



</form>



</div>



</body>

</html>
