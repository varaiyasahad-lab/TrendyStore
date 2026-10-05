<?php
session_start();


if (empty($_SESSION['cart'])) {
  header("Location: view-cart.php");
  exit;
}

$error = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        empty($_POST['name']) ||
        empty($_POST['address']) ||
        empty($_POST['city']) ||
        empty($_POST['pincode']) ||
        empty($_POST['mobile'])
    ) {
        $error = "❌ Please fill all address details";
    } else {

        $_SESSION['address'] = [
            'name'    => trim($_POST['name']),
            'address' => trim($_POST['address']),
            'city'    => trim($_POST['city']),
            'pincode' => trim($_POST['pincode']),
            'mobile'  => trim($_POST['mobile'])
        ];

        header("Location: payment.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Address | Trendy Store</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
  background:#f8f9fa;
  font-family: 'Segoe UI', Tahoma, sans-serif;
}


.checkout-steps{
  display:flex;
  justify-content:center;
  align-items:center;
  gap:20px;
  margin-bottom:30px;
}

.step{
  text-align:center;
  font-size:14px;
  color:#999;
  font-weight:500;
}

.step.done,
.step.active{
  color:#198754;
}

.circle{
  width:36px;
  height:36px;
  border-radius:50%;
  background:#e9ecef;
  display:flex;
  align-items:center;
  justify-content:center;
  margin:0 auto 6px;
  font-weight:600;
}

.step.done .circle{
  background:#198754;
  color:#fff;
}

.step.active .circle{
  background:#fff;
  border:2px solid #198754;
  color:#198754;
}

.line{
  width:60px;
  height:3px;
  background:#e9ecef;
}

.line.active{
  background:#198754;
}


.checkout-card{
  background:#fff;
  max-width:480px;
  margin:auto;
  padding:30px;
  border-radius:16px;
  box-shadow:0 12px 35px rgba(0,0,0,0.08);
}


.checkout-card input,
.checkout-card textarea{
  border-radius:10px;
  padding:11px 12px;
  font-size:14px;
}

.checkout-card input:focus,
.checkout-card textarea:focus{
  border-color:#198754;
  box-shadow:0 0 0 0.15rem rgba(25,135,84,.25);
}


.checkout-card .btn{
  border-radius:10px;
  padding:10px 18px;
  font-weight:500;
}


@media(max-width:576px){
  .checkout-card{
    padding:22px;
  }
  .line{
    width:40px;
  }
}
</style>

</head>

<body>

<div class="container my-5">


  <div class="checkout-steps">
    <div class="step done">
      <div class="circle">✓</div>
      Order Summary
    </div>

    <div class="line active"></div>

    <div class="step active">
      <div class="circle">2</div>
      Address
    </div>

    <div class="line"></div>

    <div class="step">
      <div class="circle">3</div>
      Payment
    </div>
  </div>


  <div class="checkout-card mt-5">

    <h3 class="text-center mb-4">📍 Shipping Address</h3>

    <?php if($error){ ?>
      <div class="alert alert-danger text-center">
        <?= $error; ?>
      </div>
    <?php } ?>

    <form method="post">

      <input type="text" name="name" class="form-control mb-3"
             placeholder="Full Name" required>

      <textarea name="address" class="form-control mb-3"
                placeholder="House no, Street, Area" required></textarea>

      <input type="text" name="mobile" class="form-control mb-3"
             placeholder="Mobile Number" maxlength="10" required>

      <input type="text" name="city" class="form-control mb-3"
             placeholder="City" required>

      <input type="text" name="pincode" class="form-control mb-4"
             placeholder="Pincode" maxlength="6" required>

      <div class="d-flex justify-content-between">
        <a href="view-cart.php" class="btn btn-secondary">
          ⬅ Back
        </a>

        <button type="submit" class="btn btn-success px-4">
          Proceed to Payment ➡
        </button>
      </div>

    </form>

  </div>

</div>

</body>
</html>
