<?php
session_start();

?>
<?php include "dbConnection_arngren.php";

	if(isset($_POST['insert_records'])){
		$orderID = $_POST['orderID'];
		$userID = $_POST['userID'];
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
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset = "UTF-8">
    <title>Credit/Debit Card Payment | ARNGREN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
          font-family: Arial;
          font-size: 17px;
          padding: 8px;
        }
        
        * {
          box-sizing: border-box;
        }
        
        .row {
          display: -ms-flexbox; 
          display: flex;
          -ms-flex-wrap: wrap; 
          flex-wrap: wrap;
          margin: 0 -16px;
        }
        
        .col-25 {
          -ms-flex: 25%; 
          flex: 25%;
        }
        
        .col-50 {
          -ms-flex: 50%; 
          flex: 50%;
        }
        
        .col-75 {
          -ms-flex: 75%; 
          flex: 75%;
        }
        
        .col-25,
        .col-50,
        .col-75 {
          padding: 0 16px;
        }
        
        .container {
          background-color: #f2f2f2;
          padding: 5px 20px 15px 20px;
          border: 1px solid lightgrey;
          border-radius: 3px;
        }
        
        input[type=text] {
          width: 100%;
          margin-bottom: 20px;
          padding: 12px;
          border: 1px solid #ccc;
          border-radius: 3px;
        }
        
        label {
          margin-bottom: 10px;
          display: block;
        }
        
        .icon-container {
          margin-bottom: 20px;
          padding: 7px 0;
          font-size: 24px;
        }
        
        .btn {
          background-color: #04AA6D;
          color: white;
          padding: 12px;
          margin: 10px 0;
          border: none;
          width: 100%;
          border-radius: 3px;
          cursor: pointer;
          font-size: 17px;
        }
        
        .btn:hover {
          background-color: #45a049;
        }
        
        a {
          color: #2196F3;
        }
        
        hr {
          border: 1px solid lightgrey;
        }
        
        span.total {
          float: right;
          color: grey;
        }
        
        /* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
        @media (max-width: 800px) {
          .row {
            flex-direction: column-reverse;
          }
          .col-25 {
            margin-bottom: 20px;
          }
        }
        .Email{
          height:35px;
          width: 706px;
        }
        </style>

</head>
<body>

    <div class="row">
      <div class="col-75">
        <div class="container">
        <form  method= "POST" action= "receipt.php">
          
          <div class="row">
            <div class="col-50">
              <h3>Billing Address</h3>
              <label for="userID"  id="userID" name="userID"><strong>User ID:</strong></label>
              <?php           
                              global $conn;
                              $sql = "SELECT userID FROM user WHERE logStatus = 1";
                              $result = mysqli_query($conn, $sql);
                              
                              if ($result !== false && $result->num_rows > 0) 
                              {
                                while ($row = $result -> fetch_assoc())
                                {
                                  
                                  echo $row['userID'];
                                  $_SESSION["useridentity"] = $row['userID'];
                                  
                                }
                              }
              ?>
              <br>
              <br>
              <label for="fullname"><i class="fa fa-user"></i> Full Name</label>
              <input for="fullname" type="text" id="fullname" name="fullname"  placeholder="John M. Doe" required>

              <label for="email"><i class="fa fa-envelope"></i> Email</label>
              <input class="Email"type="email" id="email" name="email" placeholder="john@example.com" required>
              <label for="address"><i class="fa fa-address-card-o"></i> Address</label>
              <input type="text" id="address" name="address" placeholder="542 W. 15th Street"  required>
              <label for="city"><i class="fa fa-institution"></i> City</label>
              <input type="text" id="city" name="city" placeholder="Raleigh"  required>
  
              <div class="row">
                <div class="col-50">
                  <label for="state">State/County/District</label>
                  <input type="text" id="state" name="state" placeholder="North Carolina"  required>
                </div>
                <div class="col-50">
                  <label for="zip">Zip</label>
                  <input type="text" id="zip" name="zip" placeholder="27513"  required>
                </div>
                <div class="col-50">
                  <label for="zip">Order Date</label>
                  <input type="date" id="orderDate" name="orderDate"  required>
                </div>
                <div class="col-50">
                  <label for="zip">Order Time</label>
                  <input type="time" id="orderTime" name="orderTime"  required>
                </div>
              </div>
            </div>
  
            <div class="col-50">
              <h3>Payment</h3>
              <label for="fname">Accepted Cards</label>
              <div class="icon-container">
                <i class="fa fa-cc-visa" style="color:navy;"></i>
                <i class="fa fa-cc-amex" style="color:blue;"></i>
                <i class="fa fa-cc-mastercard" style="color:red;"></i>
                <i class="fa fa-cc-discover" style="color:orange;"></i>
              </div>
              <label for="cname">Name on Card</label>
              <input type="text" id="cardname" name="cardname" placeholder="John More Doe" required>
              <label for="ccnum">Credit/Debit card number</label>
              <input type="text" id="cardnumber" name="cardnumber" placeholder="1111-2222-3333-4444" required>
              <label for="expmonth">Exp Month</label>
              <input type="text" id="expmonth" name="expmonth" placeholder="September" required>
              <div class="row">
                <div class="col-50">
                  <label for="expyear">Exp Year</label>
                  <input type="text" id="expyear" name="expyear" placeholder="2024" required>
                </div>
                <div class="col-50">
                  <label for="cvv">CVV</label>
                  <input type="text" id="cvv" name="cvv" placeholder="127" required>
                </div>
                <div class="col-50">
                  <label for="total" id="total" name="total"><strong>Total Price (Kr.):</strong></label>
                  <label for="tax" id="tax" name="tax"><strong>Tax (6%):</strong></label>
                  <label for="merchTotal" id="merchTotal" name="merchTotal"><strong>Merchandise Total:</strong></label> 
                </div>
                <div class="col-50">
                  <?php
                  //total
                  echo $_SESSION["total"];
                  ?>
                  <p><?php 
                  //tax (6%)
                  $x = $_SESSION["total"];
                  $y = 0.06;
                  echo $x*$y;
                  ?></p>
                  <?php
                  //merchandise total 
                  $x = $_SESSION["total"];
                  $y = 0.06;
                  $z = $x*$y;
                  echo $x + $z;
                  $j = $x + $z;
                  $_SESSION["merchtotal"] = "$j";
                  ?>
                </div>
                <div class="col-50">
                </div>
              </div>
            </div>
            
          </div>
          <button type="submit" value="insert_records" class="btn" id="insert_records" name="insert_records">Pay Now</button>
          <div style="display:none;"> <?php echo $_SESSION["nameProduct"];
          ?></div>
        </form>
        </div>
      </div>
    </div>
    </body>
</html>
