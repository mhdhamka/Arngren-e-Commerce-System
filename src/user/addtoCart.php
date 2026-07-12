<?php

session_start();
include("../config/db_carngren.php");

if(!isset($_SESSION['userID']) || $_SESSION['logStatus'] != 1)
{
    header("Location: ../auth/login.php");
    exit();
}

$userID = $_SESSION['userID'];

$productID = intval($_GET['addID']);

// Get product information
$sql = "SELECT * FROM product WHERE productID='$productID'";
$result = mysqli_query($conn,$sql);

$product = mysqli_fetch_assoc($result);

$productName = $product['productName'];

// Check whether product already exists in user's cart
$check = mysqli_query($conn,"
SELECT *
FROM cart
WHERE userID='$userID'
AND productID='$productID'
");

if(mysqli_num_rows($check)>0)
{
    mysqli_query($conn,"
    UPDATE cart
    SET orderQty = orderQty + 1
    WHERE userID='$userID'
    AND productID='$productID'
    ");
}
else
{
    mysqli_query($conn,"
    INSERT INTO cart
    (userID,productID,productName,orderQty)
    VALUES
    ('$userID','$productID','$productName',1)
    ");
}

header("Location: shoppingCart.php");
exit();

?>