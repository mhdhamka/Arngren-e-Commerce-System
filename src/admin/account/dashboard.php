<?php
    include ("../../config/db_carngren.php");

    // Capture Filter and Search Parameters
    $search        = trim($_GET['search'] ?? '');
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $sort_by       = $_GET['sort'] ?? 'userID';
    $sort_order    = $_GET['order'] ?? 'ASC';

    // Whitelist sort columns to prevent SQL injection
    $allowed_sorts = [
        'userID'   => 'user.userID', 
        'fullName' => 'user.fullName', 
        'email'    => 'user.email'
    ];
    $sql_sort_column = $allowed_sorts[$sort_by] ?? 'user.userID';
    $sql_sort_order  = (strtoupper($sort_order) === 'ASC') ? 'ASC' : 'DESC';

    // Build WHERE Clause dynamically
    $where_clauses = [];
    if (!empty($search)) {
        $where_clauses[] = "(user.userID LIKE '%$search_escaped%' OR user.fullName LIKE '%$search_escaped%' OR user.email LIKE '%$search_escaped%')";
    }

    $where_sql = "";
    if (count($where_clauses) > 0) {
        $where_sql = " WHERE " . implode(" AND ", $where_clauses);
    }

    // Handle Insertion if function exists
    if (isset($_POST['addAccount'])) {
        $fullName = $_POST['fullName'] ?? '';
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (function_exists('insert_records')) {
            insert_records($fullName, $email, $password);
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
    <title>Arngren | Account Management</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <script src="https://use.fontawesome.com/59805f286a.js"></script>
    <link rel="icon" type="image/x-icon" href="../../../assets/images/logo.PNG">

    <style>
        :root {
            --shopee-orange: #ee4d2d;
            --shopee-orange-dark: #d73211;
        }
        .addbutton button, .toggle-btn, table button {
            background-color: var(--shopee-orange) !important;
            color: white !important;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .addbutton button:hover, .toggle-btn:hover, table button:hover {
            background-color: var(--shopee-orange-dark) !important;
        }
        .sidebar .active {
            background-color: var(--shopee-orange) !important;
        }
        .details-row {
            display: none;
            background-color: #f9f9f9;
        }
        .details-row.active {
            display: table-row;
        }
        .details-content {
            padding: 15px 20px;
            text-align: left;
            border-left: 4px solid var(--shopee-orange);
            margin: 5px 10px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .action-btns {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
        }
        .action-btns a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="sidebarcontainer">
        <div class="sidebar" id="sidebar">
            <ul>
                <li>
                    <a href="">
                        <span class="icon"><img src="../../../assets/images/logo.PNG" width="40px"></span>
                        <span class="title"><h3 style="color:#ee4d2d;">ARNGREN</h3></span>
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
            <div class="topbar" style="display: flex; justify-content: flex-end; align-items: center; padding: 15px 30px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 20px;">
                <div class="admin" style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: #333;">
                    <i style="color: #ee4d2d; font-size: 20px;" class="fa fa-user-circle"></i>
                    <span>
                        <?php
                            global $conn;
                            $sqlAdmin = "SELECT adminUsername FROM admin WHERE logStatus = 1;";
                            $resAdmin = mysqli_query($conn, $sqlAdmin);
                            if ($resAdmin && $resAdmin->num_rows > 0) {
                                while ($rowAdmin = $resAdmin->fetch_assoc()) {
                                    echo htmlspecialchars($rowAdmin["adminUsername"]);
                                }
                            }
                        ?>
                    </span>
                </div>
            </div>
            
            <div class="display-accounts" style="padding: 0 30px;">
                <div style="margin-bottom: 20px;">
                    <h2 style="color: #ee4d2d; font-size: 24px; margin-bottom: 5px;">Account Management</h2>
                    <p style="color: #666; font-size: 14px;">Manage user accounts, search records, and securely maintain user profiles.</p>
                </div>

                <?php
                    // Compute KPI Metrics for Accounts
                    $kpi_sql = "SELECT COUNT(*) as total_accounts FROM user" . $where_sql;
                    $kpi_res = mysqli_query($conn, $kpi_sql);
                    $kpi_data = $kpi_res ? mysqli_fetch_assoc($kpi_res) : [];
                    $total_accounts_kpi = $kpi_data['total_accounts'] ?? 0;
                ?>

                <!-- Summary KPI Card -->
                <div class="kpi-container" style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div class="kpi-card" style="background: #fff; padding: 15px 20px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); flex: 1;">
                        <h3 style="font-size: 14px; color: #6c757d; margin-bottom: 5px;">Total Registered Accounts</h3>
                        <div style="font-size: 20px; font-weight: bold; color: #212529;"><?php echo number_format($total_accounts_kpi); ?></div>
                    </div>
                </div>

                <!-- Comprehensive Search & Filter Toolbar -->
                <form method="GET" action="" class="action-toolbar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div class="filter-group" style="display: flex; gap: 10px; align-items: flex-end;">
                        <div class="filter-item">
                            <label style="display: block; font-size: 12px; color: #666; margin-bottom: 4px;">Search Account</label>
                            <input type="text" name="search" placeholder="ID, Username, Email..." value="<?php echo htmlspecialchars($search); ?>" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; width: 250px;">
                        </div>
                        
                        <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort_by); ?>">
                        <input type="hidden" name="order" value="<?php echo htmlspecialchars($sort_order); ?>">

                        <button type="submit" class="btn-action btn-search" style="padding: 8px 15px; background: var(--shopee-orange); color: white; border: none; border-radius: 4px; cursor: pointer;"><i class="fa fa-search"></i> Filter</button>
                        <?php if (!empty($search)): ?>
                            <a href="dashboard.php" class="btn-action btn-reset" style="padding: 8px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; line-height: normal;">Reset</a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="addbutton">
                        <button type="button" style="border:none; padding:8px 15px; border-radius:4px; cursor:pointer;"><a href="../../admin/account/addAccount.php" style="color:white; text-decoration:none;"><i class="fa fa-plus"></i> Add Account</a></button>
                    </div>
                </form>

                <table id="accountTable" style="width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <thead>
                        <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <?php 
                                function sortLinkAccount($colName, $label, $current_sort, $current_order) {
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
                                    return '<a href="' . $url . '" style="color: inherit; text-decoration: none;">' . $label . ' ' . $icon . '</a>';
                                }
                            ?>
                            <th scope="col" style="padding: 12px; text-align: left; padding-left: 20px;"><?php echo sortLinkAccount('userID', 'User ID', $sort_by, $sort_order); ?></th>
                            <th scope="col" style="padding: 12px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $count_sql = "SELECT COUNT(*) AS total_records FROM user" . $where_sql;
                            $count_result = mysqli_query($conn, $count_sql);
                            $count_row = $count_result ? mysqli_fetch_assoc($count_result) : [];
                            $total_records = $count_row['total_records'] ?? 0;
                            $total_pages = ceil($total_records / $results_per_page);

                            $sql = "SELECT * FROM user" . $where_sql . " ORDER BY $sql_sort_column $sql_sort_order LIMIT $start_from, $results_per_page";
                            $result = mysqli_query($conn, $sql);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $userID   = $row['userID'] ?? '';
                                    $fullName = $row['fullName'] ?? '';
                                    $email    = $row['email'] ?? '';
                        ?>
                                    <tr class="main-row" onclick="toggleDetails(this)" style="border-bottom: 1px solid #dee2e6; cursor: pointer;">
                                        <td style="padding: 12px; text-align: left; padding-left: 20px;">
                                            <strong>#<?php echo htmlspecialchars($userID); ?></strong>
                                        </td>
                                        <td style="padding: 12px; text-align: right;" onclick="event.stopPropagation()">
                                            <div class="action-btns">
                                                <button style="background: var(--shopee-orange);"><a href="../../admin/account/updateAccount.php?updateID=<?php echo $userID; ?>">Update</a></button>
                                                <button style="background: #dc3545;"><a href="../../admin/account/deleteAccount.php?deleteID=<?php echo $userID; ?>" onclick="return confirm('Are you sure you want to delete this account?');">Delete</a></button>
                                                <button class="toggle-btn" type="button" onclick="toggleDetails(this.closest('tr').previousElementSibling || this.closest('tr'))"><i class="fa fa-chevron-down"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="details-row">
                                        <td colspan="2" style="padding: 0;">
                                            <div class="details-content">
                                                <p><strong>Username:</strong> <?php echo htmlspecialchars($fullName); ?></p>
                                                <p><strong>Email Address:</strong> <?php echo htmlspecialchars($email); ?></p>
                                            </div>
                                        </td>
                                    </tr>
                        <?php
                                }
                            } else {
                        ?>
                                <tr>
                                    <td colspan="2" style="text-align:center; padding: 40px; color: #868e96;">
                                        No Account Records Found matching your criteria.
                                    </td>
                                </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>

                <?php if ($total_records > 0): ?>
                    <div class="pagination-container" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                        <div class="pagination-info" style="font-size: 14px; color: #666;">
                            Showing <strong><?php echo min(($start_from + 1), $total_records); ?></strong> to <strong><?php echo min(($start_from + $results_per_page), $total_records); ?></strong> of <strong><?php echo $total_records; ?></strong> entries
                        </div>
                        <ul class="pagination" style="display: flex; list-style: none; gap: 5px;">
                            <?php 
                                $pagination_params = $_GET;
                                $pagination_params['page'] = $page - 1;
                                $prev_url = '?' . http_build_query($pagination_params);
                            ?>
                            <li class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>" style="padding: 6px 12px; border: 1px solid #ddd; border-radius: 4px; background: #fff;">
                                <a href="<?php echo $prev_url; ?>" style="color: #333; text-decoration: none;"><i class="fa fa-angle-left"></i> Prev</a>
                            </li>

                            <?php 
                                $range = 2;
                                for ($i = 1; $i <= $total_pages; $i++): 
                                    if ($i == 1 || $i == $total_pages || ($i >= $page - $range && $i <= $page + $range)):
                                        $pagination_params['page'] = $i;
                                        $page_url = '?' . http_build_query($pagination_params);
                            ?>
                                        <li class="<?php echo ($page == $i) ? 'active' : ''; ?>" style="padding: 6px 12px; border: 1px solid #ddd; border-radius: 4px; background: <?php echo ($page == $i) ? 'var(--shopee-orange)' : '#fff'; ?>;">
                                            <a href="<?php echo $page_url; ?>" style="color: <?php echo ($page == $i) ? '#fff' : '#333'; ?>; text-decoration: none;"><?php echo $i; ?></a>
                                        </li>
                            <?php 
                                    elseif ($i == $page - $range - 1 || $i == $page + $range + 1):
                            ?>
                                        <li class="disabled" style="padding: 6px 12px;"><a href="#" style="color: #999; text-decoration: none;">...</a></li>
                            <?php 
                                    endif;
                                endfor; 
                            ?>

                            <?php 
                                $pagination_params['page'] = $page + 1;
                                $next_url = '?' . http_build_query($pagination_params);
                            ?>
                            <li class="<?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>" style="padding: 6px 12px; border: 1px solid #ddd; border-radius: 4px; background: #fff;">
                                <a href="<?php echo $next_url; ?>" style="color: #333; text-decoration: none;">Next <i class="fa fa-angle-right"></i></a>
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
            if (!detailsRow || !detailsRow.classList.contains('details-row')) return;
            const btnIcon = row.querySelector('.toggle-btn i');
            
            if (detailsRow.classList.contains('active')) {
                detailsRow.classList.remove('active');
                if (btnIcon) btnIcon.className = 'fa fa-chevron-down';
            } else {
                document.querySelectorAll('.details-row.active').forEach(r => {
                    r.classList.remove('active');
                    const icon = r.previousElementSibling.querySelector('.toggle-btn i');
                    if (icon) icon.className = 'fa fa-chevron-down';
                });
                
                detailsRow.classList.add('active');
                if (btnIcon) btnIcon.className = 'fa fa-chevron-up';
            }
        }
    </script>
</body>
</html>