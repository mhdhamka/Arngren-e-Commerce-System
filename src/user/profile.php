<?php
session_start();
include("../../src/config/db_carngren.php");

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../auth/login.php");
    exit();
}

$userID = $_SESSION['userID'];

// Get user information
$userStmt = $conn->prepare("SELECT * FROM user WHERE userID = ?");
$userStmt->bind_param("i", $userID);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();

// Get order history
$orderStmt = $conn->prepare("
    SELECT orderID, orderDate, orderTime, total, status, address, city, state
    FROM transaction
    WHERE userID = ?
    ORDER BY orderDate DESC, orderTime DESC
");
$orderStmt->bind_param("i", $userID);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arngren | My Profile</title>
    <link rel="stylesheet" href="../../assets/css/index.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">
    <script src="https://use.fontawesome.com/59805f286a.js"></script>
    <style>
        .profile-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .profile-wrapper {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
        }
        
        .profile-sidebar {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            height: fit-content;
        }
        
        .profile-avatar {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .profile-avatar i {
            font-size: 80px;
            color: #c45b56;
        }
        
        .profile-name {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 15px 0;
        }
        
        .profile-email {
            text-align: center;
            font-size: 12px;
            color: #666;
            word-break: break-all;
            margin-bottom: 20px;
        }
        
        .profile-status {
            text-align: center;
            padding: 8px;
            background: #d4edda;
            color: #155724;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .profile-menu {
            list-style: none;
            padding: 0;
        }
        
        .profile-menu li {
            margin-bottom: 10px;
        }
        
        .profile-menu a {
            display: block;
            padding: 12px;
            color: #c45b56;
            text-decoration: none;
            border-radius: 4px;
            transition: 0.3s;
        }
        
        .profile-menu a:hover {
            background: #f0f0f0;
            color: #a84742;
        }
        
        .profile-menu a.active {
            background: #c45b56;
            color: white;
        }
        
        .profile-logout {
            width: 100%;
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 20px;
        }
        
        .profile-logout:hover {
            background: #c82333;
        }
        
        .profile-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .profile-content h2 {
            color: #c45b56;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-row label {
            font-weight: bold;
            color: #333;
        }
        
        .info-row span {
            color: #666;
        }
        
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .orders-table th {
            background: #c45b56;
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        .orders-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .orders-table tr:hover {
            background: #f9f9f9;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-badge.pending {
            background: #ffc107;
            color: #333;
        }
        
        .status-badge.processing {
            background: #17a2b8;
            color: white;
        }
        
        .status-badge.completed {
            background: #28a745;
            color: white;
        }
        
        .no-orders {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .profile-wrapper {
                grid-template-columns: 1fr;
            }
            
            .profile-sidebar {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="headercontainer">
            <div class="topnav">
                <h1>www.ARNGREN.net</h1>
                <div class="centernav">
                    <ul>
                        <li><a href="../auth/index.php">Home</a></li>
                        <li>|</li>
                        <li><a href="productList.php">Products</a></li>
                        <li>|</li>
                        <li><a href="aboutUs.php">About Us</a></li>
                    </ul>
                </div>
                
                <div class="logo">
                    <a href="../auth/index.php">
                        <img src="../../assets/images/logo.PNG" width="125px">
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="profile-container">
        <h1>My Profile</h1>
        
        <div class="profile-wrapper">
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <i class="fa fa-user-circle"></i>
                </div>
                <div class="profile-name"><?php echo htmlspecialchars($user['fullName']); ?></div>
                <div class="profile-email"><?php echo htmlspecialchars($user['email']); ?></div>
                <div class="profile-status">
                    <?php echo $user['logStatus'] == 1 ? '✓ Online' : 'Offline'; ?>
                </div>
                
                <ul class="profile-menu">
                    <li><a href="profile.php" class="active"><i class="fa fa-user"></i> Account Info</a></li>
                    <li><a href="productList.php"><i class="fa fa-shopping-bag"></i> Shop</a></li>
                    <li><a href="../auth/logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                </ul>
            </div>
            
            <div class="profile-content">
                <h2>Account Information</h2>
                
                <div class="info-row">
                    <label>Full Name</label>
                    <span><?php echo htmlspecialchars($user['fullName']); ?></span>
                </div>
                
                <div class="info-row">
                    <label>Email Address</label>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                
                <div class="info-row">
                    <label>Account Type</label>
                    <span>Customer</span>
                </div>
                
                <div class="info-row">
                    <label>Member Since</label>
                    <span>2024</span>
                </div>
                
                <h2 style="margin-top: 40px;">Order History</h2>
                
                <?php if ($orderResult->num_rows > 0): ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Shipping To</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $orderResult->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($order['orderID']); ?></strong></td>
                                    <td><?php echo $order['orderDate'] . ' ' . $order['orderTime']; ?></td>
                                    <td><strong>$<?php echo number_format($order['total'], 2); ?></strong></td>
                                    <td><?php echo htmlspecialchars($order['city'] . ', ' . $order['state']); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo strtolower($order['status']); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-orders">
                        <h3>No orders yet</h3>
                        <p>Start shopping to place your first order!</p>
                        <a href="productList.php" style="color: #c45b56; text-decoration: none; font-weight: bold;">Browse Products →</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 ARNGREN. ALL RIGHTS RESERVED</p>
    </div>
</body>
</html>