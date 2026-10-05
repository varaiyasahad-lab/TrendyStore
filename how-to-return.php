<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>How To Return</title>

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



.header{
    background:#fff;
    padding:18px;
    font-size:22px;
    font-weight:bold;
    border-bottom:1px solid #eee;
}



.container{
    width:95%;
    max-width:800px;
    margin:20px auto;
}



.card{
    background:#fff;
    border-radius:16px;
    padding:20px;
    margin-bottom:18px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}

.step{
    display:flex;
    gap:15px;
    align-items:flex-start;
}

.number{
    width:45px;
    height:45px;
    min-width:45px;
    border-radius:50%;
    background:#000;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
    font-weight:bold;
}

.content h3{
    margin-bottom:8px;
    font-size:18px;
}

.content p{
    color:#666;
    line-height:1.7;
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
    margin-top:10px;
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
<?php include "header.php"; ?>
<a href="account.php" class="back-btn">
    <span class="back-arrow">←</span>
    Back to Account
</a>
<div class="header">
    How To Return
</div>

<div class="container">



<div class="card">

<div class="step">

<div class="number">1</div>

<div class="content">
<h3>Open My Orders</h3>

<p>
Go to your account and open the Orders section.
Select the product you want to return.
</p>
</div>

</div>

</div>



<div class="card">

<div class="step">

<div class="number">2</div>

<div class="content">
<h3>Choose Return Option</h3>

<p>
Click on the Return button and select the reason
for your return request.
</p>
</div>

</div>

</div>



<div class="card">

<div class="step">

<div class="number">3</div>

<div class="content">
<h3>Pack The Product</h3>

<p>
Pack the item properly with original tags and packaging.
Our delivery partner will pick it up.
</p>
</div>

</div>

</div>


<div class="card">

<div class="step">

<div class="number">4</div>

<div class="content">
<h3>Refund Process</h3>

<p>
After successful quality check, your refund will be
processed within 5-7 business days.
</p>
</div>

</div>

</div>



<div class="note">
⚠ Return requests are accepted only within 7 days after delivery.
Products must be unused and in original condition.
</div>

</div>
<?php include "footer.php"; ?>
</body>
</html>