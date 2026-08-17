<?php include ("../../config/db_carngren.php");

    if(isset($_POST['addProduct'])){
        $productName = $_POST['productName'];
        $productQty = $_POST['productQty'];
        $productPrice = $_POST['productPrice'];

        addProduct($productName, $productQty, $productPrice);
    }

    // Pagination Configuration
    global $conn;
    $results_per_page = 5; // Change this number to adjust how many products show per page

    // Find the total number of products
    $sql_count = "SELECT COUNT(productID) AS total FROM product";
    $result_count = mysqli_query($conn, $sql_count);
    $row_count = mysqli_fetch_assoc($result_count);
    $total_pages = ceil($row_count['total'] / $results_per_page);

    // Determine which page number visitor is currently on
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

    // Ensure page value is within valid bounds
    if ($page < 1) {
        $page = 1;
    } elseif ($page > $total_pages && $total_pages > 0) {
        $page = $total_pages;
    }

    // Determine the SQL LIMIT starting number for the querying results
    $this_page_first_result = ($page - 1) * $results_per_page;
?>

<!DOCTYPE HTML>
<html lang = "en">

<head>
    <meta charset = "UTF-8">
    <title>Arngren | Products</title>

    <link rel="stylesheet" href="../../../assets/css/dashProduct.css">
    
    <script src="https://use.fontawesome.com/59805f286a.js"></script>

    <!---favicon--->
    <link rel="icon" type="image/x-icon" href="../../../assets/images/logo.PNG">

</head>

<body>
    <div class = "sidebarcontainer">
        <div class = "sidebar" id = "sidebar">
            <ul>
                <li>
                    <a href = "">
                        <span class = "icon"><img src = "../../../assets/images/logo.PNG" width = "50px"></span>
                        <span class = "title"><h2>ARNGREN</h2></span>
                    </a>
                </li>
                <li>
                    <a href = "../../admin/account/dashboard.php">
                        <span class = "icon"><i class = "fa fa-users"></i></span>
                        <span class = "title">Accounts</span>
                    </a>
                </li>
                <li>
                    <a class = "active" href = "../../admin/product/product.php">
                        <span class = "icon"><i class = "fa fa-shopping-cart"></i></span>
                        <span class = "title">Products</span>
                    </a>
                </li>
                <li>
                    <a href = "../../admin/transaction/record.php">
                        <span class = "icon"><i class = "fa fa-bar-chart"></i></span>
                        <span class = "title">Record</span>
                    </a>
                </li>
                <li>
                    <a href = "../../admin/transaction/report.php">
                        <span class = "icon"><i class = "fa fa-print"></i></span>
                        <span class = "title">Report</span>
                    </a>
                </li>
                <li>
                    <a href = "../../auth/logout.php">
                        <span class = "icon"><i class = "fa fa-sign-out"></i></span>
                        <span class = "title">Log Out</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class = "main">
            <!-- Wrapped topbar layout matching Image 2 style -->
            <div class = "topbar" style="display: flex; justify-content: flex-end; align-items: center; padding: 15px 30px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 20px;">
                <div class = "admin" style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: #333;">
                    <i style = "color: #ee4d2d; font-size: 20px;" class = "fa fa-user-circle"></i>
                    <span>
                        <?php
                            global $conn;
                            $sql = "SELECT adminUsername FROM admin WHERE logStatus = 1;";
                            $result = mysqli_query($conn, $sql);
                            
                            if ($result && $result->num_rows > 0)
                            {
                                while ($row = $result->fetch_assoc())
                                {
                                    echo htmlspecialchars($row["adminUsername"]);
                                }
                            }
                        ?>  
                    </span>
                </div>
            </div>
            
            <div class = "display-products" style="padding: 0 30px;">
                <div style="margin-bottom: 20px;">
                    <h2 style="color: #ee4d2d; font-size: 24px; margin-bottom: 5px;">Products</h2>
                    <p style="color: #666; font-size: 14px;">Manage inventory, view detailed product attributes, and update stock records easily.</p>
                </div>

                <!-- Advanced Interactive Controls: Live Search -->
                <div class="table-controls">
                    <input type="text" id="productSearch" placeholder="Search products by name or ID..." onkeyup="filterTable()">
                    <div class = "addbutton">
                        <button style="border:none; padding:8px 15px; border-radius:4px; cursor:pointer;"><a href = "../../admin/product/addProduct.php" style="color:white; text-decoration:none;">Add Product</a></button>
                    </div>
                </div>
                
                <table id="productTable" style="width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <thead>
                        <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <th scope = "col" style="padding: 12px;">Product ID</th>
                            <th scope = "col" style="padding: 12px;">Name</th>
                            <th scope = "col" style="padding: 12px;">Details</th>
                            <th scope = "col" style="padding: 12px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            global $conn;
                            $sql = "SELECT * FROM product LIMIT " . $this_page_first_result . ',' . $results_per_page;
                            $result = mysqli_query($conn, $sql);

                            if ($result && $result->num_rows > 0)
                            {
                                while ($row = $result->fetch_assoc())
                                {
                                    $imagePath = "../../../assets/images/" . basename($row['productIMG']);

                                    echo "<tr style='text-align: center; border-bottom: 1px solid #eee;'>
                                            <td style='padding: 12px;'>".$row['productID']."</td>
                                            <td class='p-name' style='padding: 12px; font-weight: 500;'>".$row['productName']."</td>
                                            <td style='padding: 12px;'>
                                                <button class='toggle-btn' onclick='toggleDetails(".$row['productID'].")'>
                                                    <i class='fa fa-chevron-down' id='icon-".$row['productID']."'></i> View
                                                </button>
                                            </td>
                                            <td style='padding: 12px;'>
                                                <button style='border:none; padding:5px 10px; border-radius:3px; cursor:pointer; margin-right:4px;'><a href=\"../../admin/product/updateProduct.php?updateID=".$row['productID']."\" style='color:white; text-decoration:none;'>Update</a></button>
                                                <button style='border:none; padding:5px 10px; border-radius:3px; cursor:pointer;'><a href=\"../../admin/product/deleteProduct.php?deleteID=".$row['productID']."\" style='color:white; text-decoration:none;'>Delete</a></button>
                                            </td>
                                         </tr>";
                                    
                                    echo "<tr id='details-".$row['productID']."' class='expand-row'>
                                            <td colspan='4'>
                                                <div class='expand-content'>
                                                    <div>
                                                        <img src='".$imagePath."' alt='".$row['productName']."'>
                                                    </div>
                                                    <div>
                                                        <h4 style='margin-bottom: 8px; color: #333;'>Detailed Information: ".$row['productName']."</h4>
                                                        <p style='margin: 4px 0;'><strong>Product ID:</strong> #".$row['productID']."</p>
                                                        <p style='margin: 4px 0;'><strong>Quantity in Stock:</strong> ".$row['productQty']."</p>
                                                        <p style='margin: 4px 0;'><strong>Price:</strong> $".$row['productPrice']."</p>
                                                        <p style='margin: 4px 0;'><strong>Image Asset Path:</strong> <code>".$imagePath."</code></p>
                                                    </div>
                                                </div>
                                            </td>
                                         </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' style='padding: 20px; color: #666;'>No products found.</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>

                <!-- Pagination Links Rendering -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination-container">
                        <?php if ($page > 1): ?>
                            <a href="product.php?page=<?php echo $page - 1; ?>">&laquo; Prev</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="product.php?page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active-page' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="product.php?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- Interactive JavaScript for Toggle and Search -->
    <script>
        function toggleDetails(id) {
            const detailRow = document.getElementById('details-' + id);
            const icon = document.getElementById('icon-' + id);
            
            if (detailRow.style.display === 'table-row') {
                detailRow.style.display = 'none';
                icon.className = 'fa fa-chevron-down';
            } else {
                detailRow.style.display = 'table-row';
                icon.className = 'fa fa-chevron-up';
            }
        }

        function filterTable() {
            const input = document.getElementById('productSearch');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('productTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                if (tr[i].classList.contains('expand-row')) continue;
                
                let tdId = tr[i].getElementsByTagName('td')[0];
                let tdName = tr[i].getElementsByTagName('td')[1];
                
                if (tdId || tdName) {
                    let idValue = tdId.textContent || tdId.innerText;
                    let nameValue = tdName.textContent || tdName.innerText;
                    
                    if (idValue.toLowerCase().indexOf(filter) > -1 || nameValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                        let nextRow = tr[i].nextElementSibling;
                        if(nextRow && nextRow.classList.contains('expand-row')) {
                            nextRow.style.display = "none";
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>