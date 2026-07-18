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
<title>Returns & Refund Policy</title>

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

/* NOTE */

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
    Returns & Refund Policy
</div>

<div class="container">

<!-- SECTION 1 -->

<div class="card">

<h2>1. Return Eligibility</h2>

<p>
Products can be returned within 7 days after delivery.
Items must be unused and in original packaging condition.
</p>

</div>

<!-- SECTION 2 -->

<div class="card">

<h2>2. Non-Returnable Items</h2>

<p>
Used, damaged, or customized products are not eligible
for return or refund.
</p>

</div>

<!-- SECTION 3 -->

<div class="card">

<h2>3. Refund Process</h2>

<p>
After successful product inspection, refunds are processed
within 5-7 business days to the original payment method.
</p>

</div>

<!-- SECTION 4 -->

<div class="card">

<h2>4. Cancellation Policy</h2>

<p>
Orders can only be cancelled before shipment.
Once shipped, cancellation requests will not be accepted.
</p>

</div>

<!-- SECTION 5 -->

<div class="card">

<h2>5. Contact Support</h2>

<p>
For return or refund related help, please contact our
customer support team from the Customer Care section.
</p>

</div>

<!-- NOTE -->

<div class="note">
⚠ Refund timelines may vary depending on bank processing time.
</div>

</div>
 <?php include 'footer.php'; ?>
</body>
</html>