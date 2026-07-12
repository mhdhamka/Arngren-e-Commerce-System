<?php

    include("../config/db_carngren.php");

    $cartID=$_GET['cartID'];

    mysqli_query($conn,

    "
    DELETE FROM cart
    WHERE cartID='$cartID'
    "

    );

    header("Location: shoppingCart.php");
?>