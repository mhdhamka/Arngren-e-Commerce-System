<?php include "dbConnection_arngren.php";

	if(isset($_POST['insert'])){
    $orderID = $_POST['orderID'];
    $orderID = $_POST['userID'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $orderDate = $_POST['orderDate'];
    $orderTime = $_POST['orderTime'];
    $productName = $_POST['productName'];
    $Qty = $_POST['Qty'];
	$total = $_POST['total'];
	$address = $_POST['address'];
    $state = $_POST['state'];
	$city = $_POST['city'];
    $zip = $_POST['zip'];
		
        


		makePayment($orderID, $userID, $fullname, $email, $orderDate, $orderTime, $productName, $Qty, $total, $address, $state, $city, $zip);
	}
?>

<!DOCTYPE html>
<html>
<head>
<title>Transaction Records | ARNGREN</title>
<meta charset="UTF-8">
<style type="text/css">
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body{
        background-color: #ffffff;
        font-family: sans-serif;
    }
    .table-container{
        padding: 0 10%;
        margin: 40px auto0;
    }
    .heading{
        font-size: 40px;
        text-align: center;
        color: #000000;
        margin-bottom: 40px;
        font-family: Georgia;
    }
    .table{
        width: 100%;
        border-collapse: collapse;
    }
    .table thead{
        background-color: #39a863;
    }
    .table thead tr th{
        font-size: 14px;
        font-weight: medium;
        letter-spacing: 0.35px;
        color: #ffffff;
        opacity: 1;
        padding: 12px;
        vertical-align: top;
        border: 1px solid #75757585;
    }
    .table tbody tr td{
        font-size: 14px;
        letter-spacing: 0.35px;
        font-weight: normal;
        color: #000000;
        background-color: #ffffff;
        padding: 8px;
        text-align: center;
        border: 1px solid #75757585;
    }
    .btn{
        width: 130px;
        text-decoration: none;
        line-height: 35px;
        display: inline-block;
        background-color: rgb(24, 206, 145);
        font-weight: medium;
        color: white;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        font-size: 14px;
        opacity: 1;
    }
</style>
</head>
<body>
    <div class="table-container">
        <h1 class="heading">Customers Record (ADMIN)</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Order Date</th>
                    <th>Order Time</th>
                    <th>Product Quantity</th>
                    <th>Product Name</th>
                    <th>Total Price(excl. 6% Tax)</th>
                    <th>Customer Address</th>
                    <th>State/County/District</th>
                    <th>City</th>
                    <th>Zip</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="fullname"></td>
                    <td data-label="fullname"></td>
                    <td data-label="email"></td>
                    <td data-label="orderDate"></td>
                    <td data-label="orderTime"></td>
                    <td data-label="Qty"></td>
                    <td data-label="productName"></td>
                    <td data-label="orderID"></td>
                    <td data-label="total"></td>
                    <td data-label="address"></td>
                    <td data-label="state"></td>
                    <td data-label="city"></td>
                    <td data-label="zip"></td>

                </tr>
            </tbody>
            <?php
				displayRecord();
            ?>
        </table>
    </div>
</body>
</html>