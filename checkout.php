<?php
session_start();

include "db.php";

if(isset($_SESSION['user_id'])){

    $user_id = $_SESSION['user_id'];

    $checkAddress = mysqli_query($conn,"
    SELECT id
    FROM addresses
    WHERE user_id='$user_id'
    LIMIT 1
    ");

    if(mysqli_num_rows($checkAddress) > 0){

        header("Location: payment.php");
        exit;

    }
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $_SESSION['address'] = [
    "name"    => $_POST['name'] ?? '',
    "phone"   => $_POST['phone'] ?? '',
    "address" => $_POST['address'] ?? '',
    "city"    => $_POST['city'] ?? '',
    "pincode" => $_POST['pincode'] ?? ''
  ];

  header("Location: payment.php");
  exit;
}


if(empty($_SESSION['buy_now']) && empty($_SESSION['cart'])){
  echo "<h2 style='padding:20px'>❌ Please select product first</h2>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout | Trendy Store</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
  background:#f5f7fa;
  font-family: 'Segoe UI', sans-serif;
}


.checkout-steps{
  display:flex;
  align-items:center;
  justify-content:space-between;
  margin-bottom:35px;
}

.step{
  flex:1;
  text-align:center;
  position:relative;
  font-size:14px;
  color:#999;
}

.step .circle{
  width:40px;
  height:40px;
  border-radius:50%;
  background:#e0e0e0;
  display:flex;
  align-items:center;
  justify-content:center;
  margin:0 auto 6px;
  font-weight:600;
}

.step.done .circle{
  background:#28a745;
  color:#fff;
}

.step.active .circle{
  background:#007bff;
  color:#fff;
  transform:scale(1.1);
}

.step.done,
.step.active{
  color:#000;
  font-weight:600;
}

.line{
  height:3px;
  background:#e0e0e0;
  flex:1;
  margin:0 8px;
  border-radius:5px;
}

.payment-box{
  background:#fff;
  padding:30px;
  border-radius:16px;
  box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

.happy-box{
  text-align:center;
  margin-top:40px;
  color:#666;
}


@media(max-width:576px){
  .step{ font-size:11px; }
  .step .circle{ width:32px;height:32px;font-size:13px; }
  .payment-box{ padding:20px; }
}

</style>
</head>

<body>

<div class="container my-5">


  <div class="checkout-steps">

    <div class="step done">
      <div class="circle">✓</div>
      <div>Order</div>
    </div>

    <div class="line"></div>

    <div class="step active">
      <div class="circle">2</div>
      <div>Address</div>
    </div>

    <div class="line"></div>

    <div class="step">
      <div class="circle">3</div>
      <div>Payment</div>
    </div>

  </div>


  <div class="payment-box">

    <h3 class="text-center mb-3">🚚 Delivery Details</h3>

    <p class="text-center text-muted mb-4">
      Enter your shipping address to continue
    </p>

 
    <form method="post">

      <input type="text" name="name" class="form-control mb-2"
             placeholder="Full Name"
             value="<?= $_SESSION['address']['name'] ?? '' ?>" required>

      <input type="text" name="phone" class="form-control mb-2"
             placeholder="Mobile Number"
             value="<?= $_SESSION['address']['phone'] ?? '' ?>" required>

      <textarea name="address" class="form-control mb-2"
                placeholder="Full Address" required><?= $_SESSION['address']['address'] ?? '' ?></textarea>

   
      <input type="text" name="city" class="form-control mb-2"
             placeholder="City"
             value="<?= $_SESSION['address']['city'] ?? '' ?>" required>

      <input type="text" name="pincode" class="form-control mb-3"
             placeholder="Pincode"
             value="<?= $_SESSION['address']['pincode'] ?? '' ?>" required>

      <button class="btn btn-dark w-100 py-2">
        Continue to Payment ➡
      </button>

    </form>

  </div>

  
  <div class="happy-box">
    <h2>😊 1,25,000+</h2>
    <p>Happy Customers Trust Trendy Store</p>
  </div>

</div>

</body>
</html>