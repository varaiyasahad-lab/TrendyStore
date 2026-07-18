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
<title>Terms & Conditions</title>

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

.note{
    background:#fff3cd;
    border:1px solid #ffe69c;
    color:#664d03;
    padding:16px;
    border-radius:12px;
    line-height:1.7;
}

</style>
</head>

<body>
 <?php include 'header.php'; ?>
<div class="header">
    Terms & Conditions
</div>

<div class="container">

<!-- SECTION 1 -->

<div class="card">

<h2>1. Acceptance Of Terms</h2>

<p>
By accessing and using our website, you agree to comply with
all terms and conditions mentioned on this page.
</p>

</div>

<!-- SECTION 2 -->

<div class="card">

<h2>2. Orders & Payments</h2>

<p>
All orders placed on our website are subject to product
availability and payment confirmation.
We reserve the right to cancel any order at any time.
</p>

</div>

<!-- SECTION 3 -->

<div class="card">

<h2>3. Shipping Policy</h2>

<p>
Delivery timelines may vary depending on your location.
Unexpected delays may happen during peak seasons or holidays.
</p>

</div>

<!-- SECTION 4 -->

<div class="card">

<h2>4. Returns & Refunds</h2>

<p>
Customers can request returns within the allowed return period.
Refunds are processed after successful product inspection.
</p>

</div>

<!-- SECTION 5 -->

<div class="card">

<h2>5. User Responsibilities</h2>

<p>
Users must provide correct information while placing orders.
Any misuse of the website may result in account suspension.
</p>

</div>

<!-- NOTE -->

<div class="note">
⚠ By using this website, you agree to all terms and conditions mentioned above.
</div>

</div>
 <?php include 'footer.php'; ?>
</body>
</html>