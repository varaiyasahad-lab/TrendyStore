<?php
include "db.php";

if (!isset($_GET['order_code'])) {
  die("Order Code missing");
}

$order_code = $_GET['order_code'];
$q = $conn->query("SELECT * FROM orders WHERE order_code='$order_code'");
$order = $q->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Successful | Trendy Store</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
  background:#f2f6fb;
  font-family:'Segoe UI',Tahoma,sans-serif;
}

/* CENTER */
.success-wrapper{
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
}

/* CARD */
.success-card{
  background:#fff;
  padding:40px;
  border-radius:20px;
  text-align:center;
  box-shadow:0 20px 50px rgba(0,0,0,.1);
  width:100%;
  max-width:420px;
}

/* CIRCLE */
.check-circle{
  width:90px;
  height:90px;
  border-radius:50%;
  border:4px solid #34a853;
  margin:0 auto 20px;
  position:relative;
  animation: scaleIn .4s ease forwards;
}

/* CHECK */
.check{
  width:28px;
  height:50px;
  border-right:6px solid #34a853;
  border-bottom:6px solid #34a853;
  position:absolute;
  left:30px;
  top:15px;
  transform:rotate(45deg) scale(0);
  animation: drawCheck .6s ease forwards;
  animation-delay:.4s;
}

/* TEXT */
.success-text{
  font-size:20px;
  font-weight:600;
  color:#34a853;
  opacity:0;
  animation: fadeIn .4s ease forwards;
  animation-delay:1s;
}

.sub-text{
  color:#555;
  opacity:0;
  animation: fadeIn .4s ease forwards;
  animation-delay:1.3s;
}

/* BUTTON */
.btn-success{
  margin-top:25px;
  border-radius:10px;
}

/* ANIMATIONS */
@keyframes drawCheck{
  to{ transform:rotate(45deg) scale(1); }
}
@keyframes scaleIn{
  from{ transform:scale(.5); opacity:0 }
  to{ transform:scale(1); opacity:1 }
}
@keyframes fadeIn{
  to{ opacity:1 }
}
</style>

</head>
<body>

<div class="success-wrapper">
  <div class="success-card">

    <!-- GPay Style Animation -->
    <div class="check-circle">
      <div class="check"></div>
    </div>

    <div class="success-text">Payment Successful</div>
    <div class="sub-text mt-2">
      Order Placed Successfully
    </div>

    <hr class="my-4">

    <p><b>Order Code:</b> <?= $order['order_code']; ?></p>

<p><b>Tracking ID:</b> <?= $order['tracking_id']; ?></p>

   <p><b>Total Paid:</b> ₹<?= $order['total_amount']; ?></p>
    <a href="track-order.php?order_code=<?= $order['order_code']; ?>"
       class="btn btn-success w-100">
      Track Order
    </a>

   

  </div>
</div>

</body>
</html>
