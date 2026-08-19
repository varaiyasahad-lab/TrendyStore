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
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - Trendy Store</title>

<style>

*{
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;

}

body{
background:#f5f5f5;
color:#222;
}

.hero{
background:#000;
color:#fff;
text-align:center;
padding:5px 2px;
margin-top: 95px;
}

.hero h1{
font-size:42px;
margin-bottom:10px;
}

.hero p{
font-size:18px;
opacity:.9;
}

.container{
max-width:1100px;
margin:auto;
padding:50px 20px;
}

.section{
background:#fff;
padding:30px;
border-radius:18px;
margin-bottom:25px;
box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.section h2{
margin-bottom:15px;
font-size:28px;
}

.section p{
line-height:1.8;
color:#555;
}

.features{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
margin-top:20px;
}

.feature{
background:#fff;
padding:25px;
border-radius:18px;
text-align:center;
box-shadow:0 5px 15px rgba(0,0,0,.08);
transition:.3s;
}

.feature:hover{
transform:translateY(-5px);
}

.feature span{
font-size:40px;
display:block;
margin-bottom:10px;
}

.feature h3{
margin-bottom:10px;
}

.footer{
background:#111;
color:#fff;
text-align:center;
padding:20px;
margin-top:30px;
}

@media(max-width:768px){

.hero h1{
font-size:32px;
}

.features{
grid-template-columns:1fr;
}

}

</style>
</head>

<body>
    
 <?php include 'header.php'; ?>
 
<div class="hero">
<h1>About Trendy Store</h1>
<p>Your Destination For Fashion & Lifestyle</p>
</div>

<div class="container">

<div class="section">
<h2>Who We Are</h2>

<p>
Trendy Store is an online fashion destination dedicated to bringing the latest trends in clothing, footwear, and lifestyle products at affordable prices. Our mission is to make fashion accessible, stylish, and convenient for everyone.
</p>

</div>

<div class="section">
<h2>Our Mission</h2>

<p>
We believe fashion is a way to express confidence and personality. Trendy Store continuously works to provide high-quality products, excellent customer service, and a seamless shopping experience.
</p>

</div>

<div class="section">
<h2>Why Choose Us?</h2>

<div class="features">

<div class="feature">
<span>🚚</span>
<h3>Fast Delivery</h3>
<p>Quick and reliable shipping across India.</p>
</div>

<div class="feature">
<span>🔒</span>
<h3>Secure Payments</h3>
<p>Safe and trusted payment options.</p>
</div>

<div class="feature">
<span>⭐</span>
<h3>Quality Products</h3>
<p>Carefully selected fashion products.</p>
</div>

</div>

</div>

<div class="section">
<h2>Our Vision</h2>

<p>
Our vision is to become one of India's most trusted online fashion stores by offering premium products, exceptional value, and customer satisfaction.
</p>

</div>

</div>

<div class="footer">
© <?php echo date('Y'); ?> Trendy Store. All Rights Reserved.
</div>
 <?php include 'footer.php'; ?>
</body>
</html>