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
<title>Privacy Policy - Trendy Store</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:#f5f5f5;
color:#333;
line-height:1.8;
margin-top: 95px;
}

.header{
background:#000;
color:#fff;
text-align:center;
padding:5px 10px;
}

.header h1{
font-size:40px;
margin-bottom:10px;
}

.container{
max-width:1000px;
margin:30px auto;
padding:20px;
}

.card{
background:#fff;
padding:30px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
margin-bottom:20px;
}

.card h2{
margin-bottom:12px;
color:#111;
font-size:24px;
}

.card p{
color:#555;
}

.footer{
background:#111;
color:#fff;
text-align:center;
padding:20px;
margin-top:30px;
}

@media(max-width:768px){

.header h1{
font-size:30px;
}

.card{
padding:20px;
}

.card h2{
font-size:20px;
}

}

</style>
</head>

<body>
 <?php include 'header.php'; ?>
<div class="header">
<h1>Privacy Policy</h1>
<p>Your privacy is important to us.</p>
</div>

<div class="container">

<div class="card">
<h2>Information We Collect</h2>
<p>
We may collect personal information such as your name, email address,
phone number, shipping address, and payment details when you place an order
or create an account on Trendy Store.
</p>
</div>

<div class="card">
<h2>How We Use Your Information</h2>
<p>
Your information is used to process orders, improve customer service,
provide updates about your orders, and enhance your shopping experience.
</p>
</div>

<div class="card">
<h2>Data Protection</h2>
<p>
We implement security measures to protect your personal information
from unauthorized access, alteration, disclosure, or destruction.
</p>
</div>

<div class="card">
<h2>Cookies</h2>
<p>
Our website may use cookies to improve functionality, remember user
preferences, and provide a better browsing experience.
</p>
</div>

<div class="card">
<h2>Third-Party Services</h2>
<p>
We may use trusted third-party services for payment processing,
shipping, and analytics. These providers only receive information
necessary to perform their services.
</p>
</div>

<div class="card">
<h2>Your Rights</h2>
<p>
You may request access, correction, or deletion of your personal
information by contacting us through our support channels.
</p>
</div>

<div class="card">
<h2>Changes To This Policy</h2>
<p>
Trendy Store reserves the right to update this Privacy Policy at any time.
Changes will be posted on this page with immediate effect.
</p>
</div>

<div class="card">
<h2>Contact Us</h2>
<p>
If you have any questions regarding this Privacy Policy,
please contact us through our Customer Support page.
</p>
</div>

</div>

<div class="footer">
© <?php echo date('Y'); ?> Trendy Store. All Rights Reserved.
</div>
 <?php include 'footer.php'; ?>
</body>
</html>