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
<title>FAQs - Trendy Store</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:#f5f5f5;
margin-top: 95px;
}

.header{
background:#000;
color:#fff;
text-align:center;
padding:10px 20px;
}

.header h1{
font-size:40px;
margin-bottom:10px;
}

.header p{
opacity:.9;
}

.container{
max-width:900px;
margin:40px auto;
padding:0 15px;
}

.faq-item{
background:#fff;
margin-bottom:15px;
border-radius:12px;
overflow:hidden;
box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

.faq-question{
padding:18px 20px;
font-size:18px;
font-weight:bold;
cursor:pointer;
display:flex;
justify-content:space-between;
align-items:center;
}

.faq-question:hover{
background:#f8f8f8;
}

.faq-answer{
display:none;
padding:20px;
border-top:1px solid #eee;
color:#555;
line-height:1.8;
}

.icon{
font-size:22px;
}

.footer{
background:#111;
color:#fff;
text-align:center;
padding:20px;
margin-top:40px;
}

@media(max-width:768px){

.header h1{
font-size:30px;
}

.faq-question{
font-size:16px;
padding:15px;
}

.faq-answer{
padding:15px;
font-size:14px;
}

}

</style>
</head>

<body>
 <?php include 'header.php'; ?>
<div class="header">
<h1>Frequently Asked Questions</h1>
<p>Find answers to common questions about Trendy Store.</p>
</div>

<div class="container">

<div class="faq-item">
<div class="faq-question">
How can I place an order?
<span class="icon">+</span>
</div>
<div class="faq-answer">
Simply browse products, add items to your cart, and proceed to checkout.
</div>
</div>

<div class="faq-item">
<div class="faq-question">
What payment methods do you accept?
<span class="icon">+</span>
</div>
<div class="faq-answer">
We accept Cash on Delivery (COD), UPI, Debit Cards, Credit Cards, and Net Banking.
</div>
</div>

<div class="faq-item">
<div class="faq-question">
How can I track my order?
<span class="icon">+</span>
</div>
<div class="faq-answer">
Go to "My Orders" and click on "Track Order" to view the latest order status.
</div>
</div>

<div class="faq-item">
<div class="faq-question">
Do you offer returns?
<span class="icon">+</span>
</div>
<div class="faq-answer">
Yes, eligible products can be returned within the return period mentioned on the product page.
</div>
</div>

<div class="faq-item">
<div class="faq-question">
How long does delivery take?
<span class="icon">+</span>
</div>
<div class="faq-answer">
Most orders are delivered within 3-7 business days depending on your location.
</div>
</div>

<div class="faq-item">
<div class="faq-question">
How do I contact customer support?
<span class="icon">+</span>
</div>
<div class="faq-answer">
You can contact us through our Contact Us page or WhatsApp support.
</div>
</div>

</div>

<div class="footer">
© <?php echo date('Y'); ?> Trendy Store. All Rights Reserved.
</div>

<script>

document.querySelectorAll('.faq-question').forEach(item=>{

item.addEventListener('click',()=>{

const answer=item.nextElementSibling;
const icon=item.querySelector('.icon');

if(answer.style.display==="block"){
answer.style.display="none";
icon.innerHTML="+";
}else{
answer.style.display="block";
icon.innerHTML="−";
}

});

});

</script>
 <?php include 'footer.php'; ?>
</body>
</html>