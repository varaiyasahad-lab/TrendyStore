<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - Trendy Store</title>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Fees & Payments</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f5f5f5;
    margin-top: 95px;
}

/* HEADER */

.header{
    background:#fff;
    padding:18px;
    font-size:22px;
    font-weight:bold;
    border-bottom:1px solid #eee;
}

/* CONTAINER */

.container{
    width:95%;
    max-width:900px;
    margin:20px auto;
}

/* CARD */

.card{
    background:#fff;
    border-radius:16px;
    padding:22px;
    margin-bottom:18px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}

.card h2{
    font-size:20px;
    margin-bottom:12px;
}

.card p{
    color:#666;
    line-height:1.8;
    font-size:15px;
}

/* PAYMENT METHODS */

.methods{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    margin-top:10px;
}

.method{
    background:#f7f7f7;
    padding:12px 18px;
    border-radius:10px;
    font-size:14px;
}

/* NOTE */

.note{
    background:#fff3cd;
    border:1px solid #ffe69c;
    color:#664d03;
    padding:16px;
    border-radius:12px;
    line-height:1.7;
}
.back-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    margin:15px 14px 5px;
    padding:10px 16px;
    background:#111;
    color:#fff;
    border-radius:8px;
    font-size:14px;
    font-weight:bold;
    text-decoration:none;
    transition:0.3s;
}

.back-btn:hover{
    background:#333;
}

.back-arrow{
    font-size:20px;
    line-height:1;
}

</style>
</head>

<body>
 <?php include 'header.php'; ?>
 <a href="account.php" class="back-btn">
    <span class="back-arrow">←</span>
    Back to Account
</a>
<div class="header">
    Fees & Payments
</div>

<div class="container">

<!-- SECTION 1 -->

<div class="card">

<h2>1. Accepted Payment Methods</h2>

<p>
We support multiple secure payment methods for easy shopping.
</p>

<div class="methods">

<div class="method">💳 Credit Card</div>

<div class="method">🏦 Debit Card</div>

<div class="method">📱 UPI</div>

<div class="method">💰 Cash On Delivery</div>

<div class="method">🏧 Net Banking</div>

</div>

</div>

<!-- SECTION 2 -->

<div class="card">

<h2>2. Shipping Fees</h2>

<p>
Shipping charges may vary depending on your location
and order value. Free delivery may apply on selected products.
</p>

</div>

<!-- SECTION 3 -->

<div class="card">

<h2>3. Secure Payments</h2>

<p>
All transactions are encrypted and securely processed
through trusted payment gateways.
</p>

</div>

<!-- SECTION 4 -->

<div class="card">

<h2>4. Failed Transactions</h2>

<p>
If payment fails but money is deducted, the amount
will automatically be refunded within 5-7 business days.
</p>

</div>

<!-- SECTION 5 -->

<div class="card">

<h2>5. Cash On Delivery</h2>

<p>
Cash On Delivery may not be available for all locations
or high-value orders.
</p>

</div>

<!-- NOTE -->

<div class="note">
⚠ Additional bank charges may apply depending on your payment method.
</div>

</div>
 <?php include 'footer.php'; ?>
</body>
</html> 