<?php
session_start();
include "db.php";

$order = null;
$error = "";

if(isset($_POST['track'])){

    $tracking_id = trim($_POST['tracking_id']);

    $stmt = $conn->prepare("
        SELECT * FROM orders
        WHERE tracking_id = ?
        LIMIT 1
    ");

    $stmt->bind_param("s", $tracking_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $order = $result->fetch_assoc();
    }else{
        $error = "No order found with this Tracking ID.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Track Order | Trendy Store</title>

<style>

body{
    margin:0;
    font-family:Arial,sans-serif;
    background:#f5f5f5;
    margin-top: 95px;
}

.container{
    max-width:1600px;
    margin:40px auto 100px;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 0 15px rgba(0,0,0,.08);
    overflow:hidden;
}

h1{
    text-align:center;
    margin-bottom:25px;
}

.track-form{
    display:flex;
    gap:10px;
}

.track-form input{
    flex:1;
    padding:14px;
    border:1px solid #ccc;
    border-radius:8px;
}

.track-form button{
    padding:14px 25px;
    border:none;
    background:#24384d;
    color:#fff;
    border-radius:8px;
    cursor:pointer;
}

.error{
    color:red;
    margin-top:15px;
}

.card{
    margin-top:25px;
    background:#fafafa;
    padding:20px;
    border-radius:10px;
}

.card p{
    margin:10px 0;
}

.status{
    color:green;
    font-weight:bold;
}

.timeline{
    margin-top:25px;
}

.step{
    border-left:4px solid #28a745;
    padding-left:15px;
    margin-bottom:15px;
}

.step h4{
    margin:0;
}

.step span{
    color:#666;
    font-size:14px;
}

@media(max-width:768px){

.track-form{
    flex-direction:column;
}

.track-form button{
    width:100%;
}

}

</style>
</head>
<body>

<?php include "header.php"; ?>

<div class="container">

<h1>Track Your Order</h1>

<form method="post" class="track-form">

<input type="text"
name="tracking_id"
placeholder="Enter Tracking ID (Example: TRK12345)"
required>

<button type="submit" name="track">
Track Order
</button>

</form>

<?php if($error){ ?>
<p class="error"><?php echo $error; ?></p>
<?php } ?>


<?php if($order){ ?>

<?php

$current_status = "Pending";

if(!empty($order['confirmed_at'])){
    $current_status = "Confirmed";
}

if(!empty($order['packed_at'])){
    $current_status = "Packed";
}

if(!empty($order['shipped_at'])){
    $current_status = "Shipped";
}

if(!empty($order['out_for_delivery_at'])){
    $current_status = "Out For Delivery";
}

if(!empty($order['delivered_at'])){
    $current_status = "Delivered";
}

?>

<div class="card">

<h2>Order Details</h2>

<div class="card">

<h2>Order Details</h2>

<p><b>Order Code:</b> <?php echo $order['order_code']; ?></p>

<p><b>Customer:</b> <?php echo $order['name']; ?></p>

<p><b>Mobile:</b> <?php echo $order['mobile']; ?></p>

<p><b>Total Amount:</b> ₹<?php echo $order['total']; ?></p>

<p><b>Payment:</b> <?php echo $order['payment_method']; ?></p>

<p><b>Payment Status:</b> <?php echo $order['payment_status']; ?></p>

<p><b>Courier:</b> <?php echo $order['courier_name']; ?></p>

<p><b>Tracking ID:</b> <?php echo $order['tracking_id']; ?></p>

<p>
<b>Current Status:</b>
<span class="status">
<?php echo $current_status; ?>
</span>
</p>

<div class="timeline">

<?php if($order['confirmed_at']){ ?>
<div class="step">
<h4>Order Confirmed</h4>
<span><?php echo $order['confirmed_at']; ?></span>
</div>
<?php } ?>

<?php if($order['packed_at']){ ?>
<div class="step">
<h4>Order Packed</h4>
<span><?php echo $order['packed_at']; ?></span>
</div>
<?php } ?>

<?php if($order['shipped_at']){ ?>
<div class="step">
<h4>Order Shipped</h4>
<span><?php echo $order['shipped_at']; ?></span>
</div>
<?php } ?>

<?php if($order['out_for_delivery_at']){ ?>
<div class="step">
<h4>Out For Delivery</h4>
<span><?php echo $order['out_for_delivery_at']; ?></span>
</div>
<?php } ?>

<?php if($order['delivered_at']){ ?>
<div class="step">
<h4>Delivered</h4>
<span><?php echo $order['delivered_at']; ?></span>
</div>
<?php } ?>

</div>

</div>

<?php } ?>

</div>

<?php include "footer.php"; ?>

</body>
</html>