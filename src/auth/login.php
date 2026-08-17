<?php
    include ("../config/db_carngren.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | Log In</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.PNG">
    <link rel="stylesheet" href="../../assets/css/auth.css">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../../assets/images/logo.PNG" alt="logo">
        </div>
        <div class="logotext">
            <h1>ARNGREN</h1>
        </div>
        <div class="logintext"> 
            <h1>Log In</h1>
        </div>
    </div>

    <div class="context">
        <div class="contextimg">
            <figure>
                <img src="../../assets/images/logo.PNG" alt="arngenlogo">
                <figcaption>ARNGREN<br><span style="font-size: 14px; font-weight: normal; opacity: 0.9;">Appliances and Gadgets Online Shopping Platform</span></figcaption>
            </figure>
        </div>
        <div class="container">
            <div class="formheader">
                <h3>Log In</h3>
            </div>
            <div class="form">
                <br>
                <button type="button" onclick="location.href='../auth/loginUser.php'" style="margin-bottom: 15px; cursor: pointer;">Log In as User</button>
                <button type="button" onclick="location.href='../auth/loginAdmin.php'" style="cursor: pointer;">Log In as Admin</button>
                <br>
            </div>
        </div>
    </div>

    <div class="footer">
        <hr>
        <p>&copy; 2026 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>
</body>
</html>