<?php

session_start();

include("../config/db_carngren.php");


if(!isset($_SESSION['userID']))
{
    header("Location: ../auth/login.php");
    exit();
}


$userID = $_SESSION['userID'];


// Delete selected products

if(isset($_POST['cartID']))
{

    foreach($_POST['cartID'] as $cartID)
    {

        $sql="
        DELETE FROM cart
        WHERE cartID='$cartID'
        AND userID='$userID'
        ";

        mysqli_query($conn,$sql);

    }


}


header("Location: shoppingCart.php");
exit();


?>