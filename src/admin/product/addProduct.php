<?php 
    include ("../../config/db_carngren.php");

    if (isset($_POST['addProduct'])) {
        $productName = $_POST['productName'];
        $productQty = $_POST['productQty'];
        $productPrice = $_POST['productPrice'];
        $productIMG = $_POST['productIMG'];

        addProduct($productName, $productQty, $productPrice, $productIMG);
    }
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Arngren | Add Product</title>

    <link rel="stylesheet" href="../../../assets/css/dashProduct.css">
    
    <!-- Modern FontAwesome & Google Fonts -->
    <script src="https://use.fontawesome.com/59805f286a.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!--- Favicon --->
    <link rel="icon" type="image/x-icon" href="../../../assets/images/logo.PNG">

</head>

<body>
    <div class="container" style="display: flex;">
        <div class="sidebar" id="sidebar">
            <ul>
                <li>
                    <a href="">
                        <span class="icon"><img src="../../../assets/images/logo.PNG" width="50px"></span>
                        <span class="title"><h2>ARNGREN</h2></span>
                    </a>
                </li>
                <li>
                    <a href="../../admin/account/dashboard.php">
                        <span class="icon"><i class="fa fa-users"></i></span>
                        <span class="title">Accounts</span>
                    </a>
                </li>
                <li>
                    <a class="active" href="../../admin/product/product.php">
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
        
        <div class="main" style="flex: 1;">
            <div class="topbar">
                <div class="admin">
                    <i style="color: #c45b56" class="fa fa-user-circle"></i>
                    <small>
                        <?php
                            global $conn;
                            $sql = "SELECT adminUsername FROM admin WHERE logStatus = 1;";
                            $result = mysqli_query($conn, $sql);
                            
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo htmlspecialchars($row["adminUsername"]);
                                }
                            }
                        ?>                        
                    </small>
                </div>
            </div>
            
            <div class="display-accounts" style="padding: 30px;">
                <ul class="breadcrumb-nav">
                    <li><a href="product.php">Products</a></li>
                    <li><span>/</span></li>
                    <li><a style="font-weight: 600; color: #333333; cursor: default;">Add Product</a></li>
                </ul>

                <div class="form-container-card">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-control">
                            <label><i class="fa fa-tag"></i> Product Name</label>
                            <input type="text" name="productName" placeholder="Enter product name" required>
                            <small>Invalid input</small>
                        </div>

                        <div class="form-control">
                            <label><i class="fa fa-sort"></i> Quantity</label>
                            <input type="number" name="productQty" placeholder="Enter quantity" required>
                            <small>Invalid input</small>
                        </div>

                        <div class="form-control">
                            <label><i class="fa fa-dollar"></i> Price</label>
                            <input type="text" name="productPrice" placeholder="Enter price" required>
                            <small>Invalid input</small>
                        </div>
                        
                        <div class="form-control">
                            <label><i class="fa fa-file-image-o"></i> Image Path/URL</label>
                            <input type="text" name="productIMG" placeholder="Please enter the image path" required>
                            <span style="color: #c45b56; font-size: 12px;">*ONLY WEB IMAGES IN .jpg, .jpeg, .png AND .gif ARE ACCEPTED</span>
                            <small>Invalid input</small>
                        </div>

                        <div style="margin-top: 25px;">
                            <button type="submit" name="addProduct" class="btn-submit">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../../assets/js/addProduct.js"></script>
</body>
</html>