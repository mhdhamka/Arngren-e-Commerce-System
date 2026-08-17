<?php
    include("../../config/db_carngren.php");

    // Capture Filter and Search Parameters for the Report
    $search         = trim($_GET['search'] ?? '');
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $start_date     = trim($_GET['start_date'] ?? '');
    $end_date       = trim($_GET['end_date'] ?? '');
    $status_filter  = trim($_GET['status'] ?? '');

    // Build WHERE Clause dynamically for Report Metrics & Charts
    $where_clauses = [];
    if (!empty($search)) {
        $where_clauses[] = "(transaction.orderID LIKE '%$search_escaped%' OR user.fullName LIKE '%$search_escaped%' OR user.email LIKE '%$search_escaped%' OR transaction.orderDate LIKE '%$search_escaped%')";
    }
    if (!empty($start_date)) {
        $start_date_esc = mysqli_real_escape_string($conn, $start_date);
        $where_clauses[] = "transaction.orderDate >= '$start_date_esc'";
    }
    if (!empty($end_date)) {
        $end_date_esc = mysqli_real_escape_string($conn, $end_date);
        $where_clauses[] = "transaction.orderDate <= '$end_date_esc'";
    }
    if (!empty($status_filter)) {
        $status_esc = mysqli_real_escape_string($conn, $status_filter);
        $where_clauses[] = "transaction.status = '$status_esc'";
    }

    $where_sql = "";
    if (count($where_clauses) > 0) {
        $where_sql = " WHERE " . implode(" AND ", $where_clauses);
    }

    // Compute KPI Metrics based on current filter criteria
    $kpi_sql = "SELECT COUNT(*) as total_orders, SUM(total) as total_revenue FROM transaction LEFT JOIN user ON transaction.userID = user.userID" . $where_sql;
    $kpi_res = mysqli_query($conn, $kpi_sql);
    $kpi_data = $kpi_res ? mysqli_fetch_assoc($kpi_res) : [];
    $total_orders_kpi = $kpi_data['total_orders'] ?? 0;
    $total_revenue_kpi = $kpi_data['total_revenue'] ?? 0;
    $avg_order_value = $total_orders_kpi > 0 ? $total_revenue_kpi / $total_orders_kpi : 0;

    /* ===============================
       Monthly Sales Report Data
    ================================= */
    $months = [];
    $sales = [];

    $monthly_sql = "
        SELECT
            DATE_FORMAT(transaction.orderDate,'%b') AS monthName,
            MONTH(transaction.orderDate) AS monthNo,
            SUM(transaction.total) AS totalSales
        FROM transaction
        LEFT JOIN user ON transaction.userID = user.userID
        " . $where_sql . "
        GROUP BY MONTH(transaction.orderDate), DATE_FORMAT(transaction.orderDate,'%b')
        ORDER BY MONTH(transaction.orderDate)
    ";

    $monthly_result = mysqli_query($conn, $monthly_sql);
    if ($monthly_result) {
        while($row = mysqli_fetch_assoc($monthly_result)) {
            $months[] = $row['monthName'];
            $sales[]  = (float)$row['totalSales'];
        }
    }

    /* ===============================
       Product Sales Report Data
    ================================= */
    $productNames = [];
    $productQty = [];

    $product_sql = "
        SELECT
            cart.productName,
            SUM(cart.orderQty) AS totalQty
        FROM transaction
        INNER JOIN cart ON transaction.userID = cart.userID
        LEFT JOIN user ON transaction.userID = user.userID
        " . $where_sql . "
        GROUP BY cart.productID, cart.productName
        ORDER BY totalQty DESC
        LIMIT 10
    ";

    $product_result = mysqli_query($conn, $product_sql);
    if ($product_result) {
        while($row = mysqli_fetch_assoc($product_result)) {
            $productNames[] = $row['productName'];
            $productQty[]   = (int)$row['totalQty'];
        }
    }
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | Transaction Report</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <link rel="stylesheet" href="../../../assets/css/report.css">
    <link rel="stylesheet" href="../../../assets/css/record.css">
    <script src="https://use.fontawesome.com/59805f286a.js"></script>
    <link rel="icon" type="image/x-icon" href="../../../assets/images/logo.PNG">
</head>
<body>
    <div class="sidebarcontainer">
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
                    <a class="active" href="../../admin/transaction/report.php">
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
                    <i style="color: #ee4d2d" class="fa fa-user-circle"></i>
                    <small>
                        <?php
                            $sqlAdmin = "SELECT adminUsername FROM admin WHERE logStatus = 1;";
                            $resAdmin = mysqli_query($conn, $sqlAdmin);
                            if ($resAdmin && $resAdmin->num_rows > 0) {
                                while ($rowAdmin = $resAdmin->fetch_assoc()) {
                                    echo htmlspecialchars($rowAdmin["adminUsername"]);
                                }
                            }
                        ?>
                    </small>
                </div>
            </div>
            
            <div class="table-container">
                <h1 class="heading" style="margin-bottom: 8px; color: #212529;">
                    <i class="fa fa-bar-chart" style="color: #ee4d2d;"></i> Sales & Transaction Report
                </h1>
                <p style="color: #6c757d; font-size: 13px; margin-bottom: 20px;">Analyze interactive sales performance trends, revenue metrics, and product demands.</p>
                
                <!-- Summary KPI Cards -->
                <div class="kpi-container" style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div class="kpi-card" style="flex: 1; background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #ee4d2d;">
                        <h3 style="font-size: 14px; color: #6c757d; margin-bottom: 5px;">Filtered Orders</h3>
                        <div class="value" style="font-size: 24px; font-weight: bold; color: #212529;"><?php echo number_format($total_orders_kpi); ?></div>
                    </div>
                    <div class="kpi-card" style="flex: 1; background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;">
                        <h3 style="font-size: 14px; color: #6c757d; margin-bottom: 5px;">Total Revenue</h3>
                        <div class="value" style="font-size: 24px; font-weight: bold; color: #212529;">KR <?php echo number_format($total_revenue_kpi, 2); ?></div>
                    </div>
                    <div class="kpi-card" style="flex: 1; background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #17a2b8;">
                        <h3 style="font-size: 14px; color: #6c757d; margin-bottom: 5px;">Average Order Value</h3>
                        <div class="value" style="font-size: 24px; font-weight: bold; color: #212529;">KR <?php echo number_format($avg_order_value, 2); ?></div>
                    </div>
                </div>

                <!-- Comprehensive Search & Filter Toolbar -->
                <form method="GET" action="" class="action-toolbar" style="margin-bottom: 30px;">
                    <div class="filter-group" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                        <div class="filter-item">
                            <label style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px;">Keyword Search</label>
                            <input type="text" name="search" placeholder="ID, Name, Email..." value="<?php echo htmlspecialchars($search); ?>" style="padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                        </div>
                        <div class="filter-item">
                            <label style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px;">From Date</label>
                            <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" style="padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                        </div>
                        <div class="filter-item">
                            <label style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px;">To Date</label>
                            <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" style="padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                        </div>
                        <div class="filter-item">
                            <label style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px;">Status</label>
                            <select name="status" style="padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                                <option value="">All Statuses</option>
                                <option value="Completed" <?php echo ($status_filter == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                <option value="Pending" <?php echo ($status_filter == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Cancelled" <?php echo ($status_filter == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-action btn-search" style="padding: 9px 15px; background: #212529; color: white; border: none; border-radius: 4px; cursor: pointer;"><i class="fa fa-search"></i> Apply Filter</button>
                        <?php if (!empty($search) || !empty($start_date) || !empty($end_date) || !empty($status_filter)): ?>
                            <a href="report.php" class="btn-action btn-reset" style="padding: 9px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>

                <!-- Charts Container -->
                <div class="chart-container" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h3 style="color: #333; margin-bottom: 15px;">Monthly Sales (KR)</h3>
                    <canvas id="salesChart" style="max-height: 350px; width: 100%;"></canvas>

                    <hr style="margin: 40px 0; border: 0; border-top: 1px solid #eee;">

                    <h3 style="color: #333; margin-bottom: 15px;">Most Purchased Products</h3>
                    <canvas id="productChart" style="max-height: 350px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Pass PHP arrays securely into JavaScript
        const monthsData = <?php echo json_encode($months); ?>;
        const salesData = <?php echo json_encode($sales); ?>;
        
        const productNamesData = <?php echo json_encode($productNames); ?>;
        const productQtyData = <?php echo json_encode($productQty); ?>;

        // 1. Monthly Sales -> Modern Area Line Chart (Shopee Orange Theme)
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        const salesGradient = ctxSales.createLinearGradient(0, 0, 0, 350);
        salesGradient.addColorStop(0, 'rgba(238, 77, 45, 0.5)');
        salesGradient.addColorStop(1, 'rgba(238, 77, 45, 0.0)');

        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: monthsData,
                datasets: [{
                    label: 'Total Sales (KR)',
                    data: salesData,
                    backgroundColor: salesGradient,
                    borderColor: '#EE4D2D',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#EE4D2D',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    lineTension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        fontFamily: "'Segoe UI', sans-serif",
                        fontColor: '#495057',
                        usePointStyle: true
                    }
                },
                tooltips: {
                    backgroundColor: 'rgba(33, 37, 41, 0.9)',
                    titleFontFamily: "'Segoe UI', sans-serif",
                    titleFontSize: 14,
                    bodyFontFamily: "'Segoe UI', sans-serif",
                    bodyFontSize: 13,
                    xPadding: 12,
                    yPadding: 12,
                    cornerRadius: 6,
                    displayColors: false,
                    callbacks: {
                        label: function(tooltipItem) {
                            return ' Total Sales: KR ' + Number(tooltipItem.yLabel).toLocaleString(undefined, {minimumFractionDigits: 2});
                        }
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            fontFamily: "'Segoe UI', sans-serif",
                            fontColor: '#6c757d'
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            color: 'rgba(0, 0, 0, 0.04)',
                            borderDash: [4, 4],
                            drawBorder: false
                        },
                        ticks: {
                            beginAtZero: true,
                            fontFamily: "'Segoe UI', sans-serif",
                            fontColor: '#6c757d',
                            callback: function(value) {
                                return 'KR ' + value.toLocaleString();
                            }
                        }
                    }]
                }
            }
        });

        // 2. Most Purchased Products -> Modern Doughnut Chart
        const ctxProduct = document.getElementById('productChart').getContext('2d');
        
        const productColors = [
            '#EE4D2D', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#C9CBCF', '#E74C3C', '#2ECC71', '#3498DB'
        ];

        new Chart(ctxProduct, {
            type: 'doughnut',
            data: {
                labels: productNamesData,
                datasets: [{
                    label: 'Quantity Sold',
                    data: productQtyData,
                    backgroundColor: productColors.slice(0, productNamesData.length),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1500
                },
                cutoutPercentage: 65,
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        boxWidth: 14,
                        fontFamily: "'Segoe UI', sans-serif",
                        fontColor: '#495057',
                        padding: 15
                    }
                },
                tooltips: {
                    backgroundColor: 'rgba(33, 37, 41, 0.9)',
                    titleFontFamily: "'Segoe UI', sans-serif",
                    titleFontSize: 14,
                    bodyFontFamily: "'Segoe UI', sans-serif",
                    bodyFontSize: 13,
                    xPadding: 12,
                    yPadding: 12,
                    cornerRadius: 6,
                    displayColors: true,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            const dataset = data.datasets[tooltipItem.datasetIndex];
                            const currentValue = dataset.data[tooltipItem.index];
                            const label = data.labels[tooltipItem.index];
                            return ` ${label}: ${currentValue} units`;
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>