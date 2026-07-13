<?php
session_start();
include("../config/db_carngren.php");

if(isset($_SESSION['adminID']))
{
    mysqli_query($conn,"
        UPDATE admin
        SET logStatus=0
        WHERE adminID='".$_SESSION['adminID']."'
    ");
}

if(isset($_SESSION['userID']))
{
    mysqli_query($conn,"
        UPDATE user
        SET logStatus=0
        WHERE userID='".$_SESSION['userID']."'
    ");
}

session_unset();
session_destroy();

header("Location: ../auth/index.php");
exit();
?>