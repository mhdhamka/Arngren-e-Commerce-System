<?php
    include ("../../config/db_carngren.php");

    $updateID = isset($_GET['updateID']) ? intval($_GET['updateID']) : 0;
    $errorMsg = "";

    if (isset($_POST['updateAccount'])) {
        $fullName = $_POST['fullName'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Checking empty fields
        if (empty($fullName) || empty($email) || empty($password)) {
            if (empty($fullName)) { $errorMsg .= "Name field is empty.<br/>"; }
            if (empty($email)) { $errorMsg .= "Email field is empty.<br/>"; }
            if (empty($password)) { $errorMsg .= "Password field is empty.<br/>"; }
        } else {
            global $conn;
            $sql = "UPDATE user SET fullName = '$fullName', email = '$email', password = '$password' WHERE userID = $updateID";
            $result = mysqli_query($conn, $sql);
            header("Location: dashboard.php");
            exit();
        }
    }

    // Fetch existing values for placeholders/values
    global $conn;
    $sql = "SELECT fullName, email, password FROM user WHERE userID = $updateID;";
    $result = mysqli_query($conn, $sql);
    $currentUser = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : ['fullName' => '', 'email' => '', 'password' => ''];
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Arngren | Update Account</title>

    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    
    <!-- Modern FontAwesome & Google Fonts -->
    <script src="https://use.fontawesome.com/59805f286a.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!--- Favicon --->
    <link rel="icon" type="image/x-icon" href="../../../assets/images/logo.PNG">

</head>

<body>
    <div class="container">
        <div class="sidebar" id="sidebar">
            <ul>
                <li>
                    <a href="">
                        <span class="icon"><img src="../../../assets/images/logo.PNG" width="50px"></span>
                        <span class="title"><h2>ARNGREN</h2></span>
                    </a>
                </li>
                <li>
                    <a class="active" href="../../admin/account/dashboard.php">
                        <span class="icon"><i class="fa fa-users"></i></span>
                        <span class="title">Accounts</span>
                    </a>
                </li>
                <li>
                    <a href="../../admin/product/product.php">
                        <span class="icon"><i class="fa fa-shopping-cart"></i></span>
                        <span class="title">Products</span>
                    </a>
                </li>
                <li>
                    <a href="../../admin/transaction/record.php">
                        <span class="icon"><i class="fa fa-bar-chart"></i></span>
                        <span class="title">Record</span>
                    </a>
                </li>
                <li>
                    <a href="../../admin/transaction/report.php">
                        <span class="icon"><i class="fa fa-print"></i></span>
                        <span class="title">Report</span>
                    </a>
                </li>
                <li>
                    <a href="../../auth/logout.php">
                        <span class="icon"><i class="fa fa-sign-out"></i></span>
                        <span class="title">Log Out</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="main">
            <div class="topbar">
                <div class="admin">
                    <i style="color: #c45b56" class="fa fa-user-circle"></i>
                    <small>
                        <?php
                            global $conn;
                            $admin_sql = "SELECT adminUsername FROM admin WHERE logStatus = 1;";
                            $admin_result = mysqli_query($conn, $admin_sql);
                            
                            if ($admin_result && $admin_result->num_rows > 0) {
                                while ($row = $admin_result->fetch_assoc()) {
                                    echo $row["adminUsername"];
                                }
                            }
                        ?>                        
                    </small>
                </div>
            </div>
            
            <div class="display-accounts">
                <ul class="breadcrumb-nav">
                    <li><a href="../../admin/account/dashboard.php">Accounts</a></li>
                    <li><span>/</span></li>
                    <li><a style="font-weight: 600; color: #333333; cursor: default;">Update Account</a></li>
                </ul>

                <div class="form-container-card">
                    <?php if (!empty($errorMsg)) { echo "<div class='error-alert'>$errorMsg</div>"; } ?>
                    
                    <form method="POST">
                        <div class="form-control">
                            <label><i class="fa fa-user-circle"></i> Full Name</label>
                            <input type="text" name="fullName" id="fullName" value="<?php echo htmlspecialchars($currentUser['fullName']); ?>" required>
                            <small>Invalid input</small>
                        </div>
                        <div class="form-control">
                            <label><i class="fa fa-envelope"></i> Email Address</label>
                            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($currentUser['email']); ?>" required>
                            <small>Invalid input</small>
                        </div>
                        <div class="form-control">
                            <label><i class="fa fa-lock"></i> Password</label>
                            <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($currentUser['password']); ?>" required>
                            <small>Invalid input</small>
                        </div>
                        <div style="margin-top: 25px;">
                            <button type="submit" name="updateAccount" class="btn-submit">Update Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../../../assets/js/updateAccount.js"></script>
</body>
</html>