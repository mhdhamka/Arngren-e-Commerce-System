<?php
    include ("../../config/db_carngren.php");

    // Capture Filter and Search Parameters
    $search        = trim($_GET['search'] ?? '');
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $start_date    = trim($_GET['start_date'] ?? '');
    $end_date      = trim($_GET['end_date'] ?? '');
    $status_filter = trim($_GET['status'] ?? '');
    $sort_by       = $_GET['sort'] ?? 'orderDate';
    $sort_order    = $_GET['order'] ?? 'ASC';

    // Whitelist sort columns to prevent SQL injection
    $allowed_sorts = [
        'orderID' => 'transaction.orderID', 
        'fullName' => 'user.fullName', 
        'orderDate' => 'transaction.orderDate', 
        'total' => 'transaction.total'
    ];
    $sql_sort_column = $allowed_sorts[$sort_by] ?? 'transaction.orderDate';
    $sql_sort_order  = (strtoupper($sort_order) === 'ASC') ? 'ASC' : 'DESC';

    // Build WHERE Clause dynamically
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

    // 1. Handle CSV Export Request
    if (isset($_GET['export']) && $_GET['export'] == 'csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=transaction_records_' . date('Y-m-d') . '.csv');
        $output = fopen('php://output', 'w');
        
        fputcsv($output, array('Order ID', 'Customer Name', 'Customer Email', 'Order Date', 'Order Time', 'Status', 'Total Price (KR)', 'Address', 'City', 'State', 'Zip'));
        
        $csv_sql = "
            SELECT transaction.orderID, user.fullName, user.email, transaction.orderDate, transaction.orderTime, transaction.status, transaction.total, transaction.address, transaction.state, transaction.city, transaction.zip
            FROM transaction
            LEFT JOIN user ON transaction.userID = user.userID
            " . $where_sql . " ORDER BY $sql_sort_column $sql_sort_order
        ";
        
        $csv_result = mysqli_query($conn, $csv_sql);
        if ($csv_result) {
            while ($row = mysqli_fetch_assoc($csv_result)) {
                fputcsv($output, array(
                    $row['orderID'],
                    $row['fullName'] ?? 'Guest Customer',
                    $row['email'] ?? 'N/A',
                    $row['orderDate'],
                    $row['orderTime'],
                    $row['status'] ?? 'Pending',
                    $row['total'],
                    $row['address'],
                    $row['city'],
                    $row['state'],
                    $row['zip']
                ));
            }
        }
        fclose($output);
        exit();
    }

    // Handle Insertion if function exists
    if (isset($_POST['insert'])) {
        $orderID     = $_POST['orderID'] ?? '';
        $userID      = $_POST['userID'] ?? '';
        $fullname    = $_POST['fullname'] ?? '';
        $email       = $_POST['email'] ?? '';
        $orderDate   = $_POST['orderDate'] ?? '';
        $orderTime   = $_POST['orderTime'] ?? '';
        $productName = $_POST['productName'] ?? '';
        $Qty         = $_POST['Qty'] ?? '';
        $total       = $_POST['total'] ?? '';
        $address     = $_POST['address'] ?? '';
        $state       = $_POST['state'] ?? '';
        $city        = $_POST['city'] ?? '';
        $zip         = $_POST['zip'] ?? '';
        
        if (function_exists('makePayment')) {
            makePayment($orderID, $userID, $fullname, $email, $orderDate, $orderTime, $Qty, $productName, $total, $address, $state, $city, $zip);
        }
    }

    // Pagination Setup
    $results_per_page = 10;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) { $page = 1; }
    $start_from = ($page - 1) * $results_per_page;
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | Transaction Records</title>
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
                        <span class="title"><h2>ARNGREN </h2></span>
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
                    <a class="active" href="../../admin/transaction/record.php">
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
                <h1 class="heading" style="margin-bottom: 8px; color: #212529;">Transaction Records</h1>
                <p style="color: #6c757d; font-size: 13px; margin-bottom: 20px;">Manage orders, filter by date ranges, sort, and export summary data easily.</p>
                
                <?php
                    // Compute KPI Metrics based on current filter criteria
                    $kpi_sql = "SELECT COUNT(*) as total_orders, SUM(total) as total_revenue FROM transaction LEFT JOIN user ON transaction.userID = user.userID" . $where_sql;
                    $kpi_res = mysqli_query($conn, $kpi_sql);
                    $kpi_data = $kpi_res ? mysqli_fetch_assoc($kpi_res) : [];
                    $total_orders_kpi = $kpi_data['total_orders'] ?? 0;
                    $total_revenue_kpi = $kpi_data['total_revenue'] ?? 0;
                    $avg_order_value = $total_orders_kpi > 0 ? $total_revenue_kpi / $total_orders_kpi : 0;
                ?>

                <!-- Summary KPI Cards -->
                <div class="kpi-container">
                    <div class="kpi-card">
                        <h3>Total Filtered Orders</h3>
                        <div class="value"><?php echo number_format($total_orders_kpi); ?></div>
                    </div>
                    <div class="kpi-card">
                        <h3>Total Revenue</h3>
                        <div class="value">KR <?php echo number_format($total_revenue_kpi, 2); ?></div>
                    </div>
                    <div class="kpi-card">
                        <h3>Average Order Value</h3>
                        <div class="value">KR <?php echo number_format($avg_order_value, 2); ?></div>
                    </div>
                </div>

                <!-- Comprehensive Search & Filter Toolbar -->
                <form method="GET" action="" class="action-toolbar">
                    <div class="filter-group">
                        <div class="filter-item">
                            <label>Keyword Search</label>
                            <input type="text" name="search" placeholder="ID, Name, Email..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="filter-item">
                            <label>From Date</label>
                            <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                        </div>
                        <div class="filter-item">
                            <label>To Date</label>
                            <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                        </div>
                        <div class="filter-item">
                            <label>Status</label>
                            <select name="status">
                                <option value="">All Statuses</option>
                                <option value="Completed" <?php echo ($status_filter == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                <option value="Pending" <?php echo ($status_filter == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Cancelled" <?php echo ($status_filter == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort_by); ?>">
                        <input type="hidden" name="order" value="<?php echo htmlspecialchars($sort_order); ?>">

                        <button type="submit" class="btn-action btn-search"><i class="fa fa-search"></i> Filter</button>
                        <?php if (!empty($search) || !empty($start_date) || !empty($end_date) || !empty($status_filter)): ?>
                            <a href="record.php" class="btn-action btn-reset">Reset</a>
                        <?php endif; ?>
                    </div>
                    
                    <div style="display: flex; gap: 8px;">
                        <?php 
                            $query_params = $_GET;
                            unset($query_params['page']);
                            $query_params['export'] = 'csv';
                            $csv_query_string = http_build_query($query_params);
                        ?>
                        <a href="?<?php echo $csv_query_string; ?>" class="btn-action btn-csv">
                            <i class="fa fa-file-excel-o"></i> CSV
                        </a>
                    </div>
                </form>

                <table class="modern-table">
                    <thead>
                        <tr>
                            <?php 
                                function sortLink($colName, $label, $current_sort, $current_order) {
                                    $new_order = ($current_sort === $colName && $current_order === 'ASC') ? 'DESC' : 'ASC';
                                    $params = $_GET;
                                    $params['sort'] = $colName;
                                    $params['order'] = $new_order;
                                    unset($params['page']);
                                    $url = '?' . http_build_query($params);
                                    $icon = '';
                                    if ($current_sort === $colName) {
                                        $icon = $current_order === 'ASC' ? '<i class="fa fa-sort-asc"></i>' : '<i class="fa fa-sort-desc"></i>';
                                    } else {
                                        $icon = '<i class="fa fa-sort" style="color: #ccc;"></i>';
                                    }
                                    return '<a href="' . $url . '">' . $label . ' ' . $icon . '</a>';
                                }
                            ?>
                            <th><?php echo sortLink('orderID', 'Order ID', $sort_by, $sort_order); ?></th>
                            <th><?php echo sortLink('fullName', 'Customer Name', $sort_by, $sort_order); ?></th>
                            <th><?php echo sortLink('orderDate', 'Order Date & Time', $sort_by, $sort_order); ?></th>
                            <th>Status</th>
                            <th><?php echo sortLink('total', 'Total Price', $sort_by, $sort_order); ?></th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $count_sql = "SELECT COUNT(*) AS total_records FROM transaction LEFT JOIN user ON transaction.userID = user.userID" . $where_sql;
                            $count_result = mysqli_query($conn, $count_sql);
                            $count_row = $count_result ? mysqli_fetch_assoc($count_result) : [];
                            $total_records = $count_row['total_records'] ?? 0;
                            $total_pages = ceil($total_records / $results_per_page);

                            $sql = "
                            SELECT
                                transaction.orderID,
                                transaction.userID,
                                user.fullName,
                                user.email,
                                transaction.orderDate,
                                transaction.orderTime,
                                transaction.status,
                                transaction.total,
                                transaction.address,
                                transaction.state,
                                transaction.city,
                                transaction.zip
                            FROM transaction
                            LEFT JOIN user ON transaction.userID = user.userID
                            " . $where_sql . "
                            ORDER BY $sql_sort_column $sql_sort_order
                            LIMIT $start_from, $results_per_page
                            ";
                            
                            $result = mysqli_query($conn, $sql);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $orderID   = $row['orderID'] ?? '';
                                    $userID    = $row['userID'] ?? 'N/A';
                                    $fullName  = $row['fullName'] ?? 'Guest Customer';
                                    $email     = $row['email'] ?? 'N/A';
                                    $orderDate = $row['orderDate'] ?? '';
                                    $orderTime = $row['orderTime'] ?? '';
                                    $status    = $row['status'] ?? 'Pending';
                                    $total     = $row['total'] ?? 0;
                                    $address   = $row['address'] ?? '';
                                    $state     = $row['state'] ?? '';
                                    $city      = $row['city'] ?? '';
                                    $zip       = $row['zip'] ?? '';

                                    $status_class = 'status-pending';
                                    if (strtolower($status) === 'completed') $status_class = 'status-completed';
                                    if (strtolower($status) === 'cancelled') $status_class = 'status-cancelled';
                                    ?>
                                    <tr class="main-row" onclick="toggleDetails(this)">
                                        <td>#<?php echo htmlspecialchars($orderID); ?></td>
                                        <td><strong><?php echo htmlspecialchars($fullName); ?></strong></td>
                                        <td><?php echo htmlspecialchars($orderDate . ' ' . $orderTime); ?></td>
                                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                                        <td><span class="price-badge">KR <?php echo number_format((float)$total, 2); ?></span></td>
                                        <td>
                                            <button class="toggle-btn">
                                                <i class="fa fa-chevron-down"></i> Details
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="details-row">
                                        <td colspan="6" style="padding: 0;">
                                            <div class="details-content">
                                                <div class="detail-item">
                                                    <label>User ID</label>
                                                    <span><?php echo htmlspecialchars($userID); ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <label>Customer Email</label>
                                                    <span><?php echo htmlspecialchars($email); ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <label>Delivery Address</label>
                                                    <span><?php echo htmlspecialchars($address); ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <label>City / State / Zip</label>
                                                    <span><?php echo htmlspecialchars($city . ', ' . $state . ' ' . $zip); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; padding: 40px; color: #868e96;">
                                        No Transaction Records Found matching your filter criteria.
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>

                <?php if ($total_records > 0): ?>
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing <strong><?php echo min(($start_from + 1), $total_records); ?></strong> to <strong><?php echo min(($start_from + $results_per_page), $total_records); ?></strong> of <strong><?php echo $total_records; ?></strong> entries
                        </div>
                        <ul class="pagination">
                            <?php 
                                $pagination_params = $_GET;
                                $pagination_params['page'] = $page - 1;
                                $prev_url = '?' . http_build_query($pagination_params);
                            ?>
                            <li class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a href="<?php echo $prev_url; ?>"><i class="fa fa-angle-left"></i> Prev</a>
                            </li>

                            <?php 
                                $range = 2;
                                for ($i = 1; $i <= $total_pages; $i++): 
                                    if ($i == 1 || $i == $total_pages || ($i >= $page - $range && $i <= $page + $range)):
                                        $pagination_params['page'] = $i;
                                        $page_url = '?' . http_build_query($pagination_params);
                            ?>
                                <li class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                                    <a href="<?php echo $page_url; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php 
                                    elseif ($i == $page - $range - 1 || $i == $page + $range + 1):
                            ?>
                                <li class="disabled"><a href="#">...</a></li>
                            <?php 
                                    endif;
                                endfor; 
                            ?>

                            <?php 
                                $pagination_params['page'] = $page + 1;
                                $next_url = '?' . http_build_query($pagination_params);
                            ?>
                            <li class="<?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a href="<?php echo $next_url; ?>">Next <i class="fa fa-angle-right"></i></a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function toggleDetails(row) {
            const detailsRow = row.nextElementSibling;
            const btnIcon = row.querySelector('.toggle-btn i');
            
            if (detailsRow.classList.contains('active')) {
                detailsRow.classList.remove('active');
                btnIcon.className = 'fa fa-chevron-down';
            } else {
                document.querySelectorAll('.details-row.active').forEach(r => {
                    r.classList.remove('active');
                    r.previousElementSibling.querySelector('.toggle-btn i').className = 'fa fa-chevron-down';
                });
                
                detailsRow.classList.add('active');
                btnIcon.className = 'fa fa-chevron-up';
            }
        }
    </script>
</body>
</html>