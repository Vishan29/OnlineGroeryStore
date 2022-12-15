<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <title>Payment</title>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form class="placed-orders">
    <div class="box-container">
    <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
      $select_orders->execute([$user_id]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
        if($fetch_orders['method']=='online payment'&&$fetch_orders['payment_status']=='pending'){ 
   ?>
   <div class="box">
      <p> Placed On : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Payment Method : <span><?= $fetch_orders['method']; ?></span> </p>
      <p> Your Orders : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total Price : <span>$<?= $fetch_orders['total_price']; ?>/-</span> </p>
   </div>
   <?php 
        break;}
        else continue;

            }
      }
   ?>
   </div>
   <div class="btn_style">
        <button id='rzp-button1'>Pay Amount</button>
        <a href="home.php">Proceed to Homepage</a>
    </div>
</form>   
</body>

<script>
    var options = {
    "key": "rzp_test_lXeKoyUgOJgDfv",
    "amount": (<?= $fetch_orders['total_price'] ?>)*100, 
    "name": "GroceryMart",
    "description": "order payment",
    "image": "images/logo.png",// COMPANY LOGO
    "handler": function (response) {
        console.log(response);
        // AFTER TRANSACTION IS COMPLETE YOU WILL GET THE RESPONSE HERE.
        <?php
        if($fetch_orders['method']=='online payment'&&$fetch_orders['payment_status']=='pending'){
            $update_order = $conn->prepare("UPDATE `orders` SET payment_status='complete' WHERE id = ?");
            $update_order->execute([$fetch_orders['id']]);
        }
        ?>
    },
   
    "notes": {
        "address": "address" //customer address 
    },
    "theme": {
        "color": "#15b8f3" // screen color
    }
};
console.log(options);
var propay = new Razorpay(options);
document.getElementById('rzp-button1').onclick = function(e){
    propay.open();
    e.preventDefault();
}
</script>
</html>