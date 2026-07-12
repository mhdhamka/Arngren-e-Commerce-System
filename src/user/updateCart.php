<?php

        session_start();
        include("../config/db_carngren.php");

        if(isset($_POST['cartID']))

        {

        $cartID=$_POST['cartID'];

        $qty=$_POST['qty'];

        mysqli_query($conn,

        "
        UPDATE cart
        SET orderQty='$qty'
        WHERE cartID='$cartID'

        "

        );

        }

        header("Location: shoppingCart.php");

?>