<?php
include "db.php";

if (!isset($_GET['order_code'])) {
    die("❌ Order code missing");
}

$order_code = $conn->real_escape_string($_GET['order_code']);

/* FETCH ORDER */
$order = $conn->query("
SELECT * FROM orders 
WHERE order_code='$order_code'
")->fetch_assoc();

if (!$order) {
    die("❌ Order not found");
}

$order_id = $order['id'];

/* FETCH ORDER ITEMS */
$items = $conn->query("
SELECT 
    oi.qty,
    oi.price,
    oi.size,
    oi.color,
    oi.product_id,
    p.name AS product_name,
    p.image
FROM order_items oi
JOIN products p 
ON oi.product_id = p.id
WHERE oi.order_id = '$order_id'
");

/* STEP LOGIC */
$steps = [
    'Confirmed',
    'Packed',
    'Shipped',
    'Out for Delivery',
    'Delivered'
];

$currentIndex = array_search(
    $order['order_status'],
    $steps
);

/* CANCEL CONDITION */
$canCancel = in_array(
    $order['order_status'],
    ['Confirmed','Packed']
);

/* PROGRESS HEIGHT */
$progressHeight = ($currentIndex !== false)
? (($currentIndex * 70) + 15)
: 0;
?>

<!DOCTYPE html>
<html>

<head>

<title>Track Order</title>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f6f7;
    font-family:Segoe UI, Arial;
}

/* CARD */

.card-box{
    background:#fff;
    padding:18px;
    border-radius:16px;
    box-shadow:0 4px 18px rgba(0,0,0,0.08);
    margin-bottom:18px;
}

/* PRODUCT */

.product-card{
    display:flex;
    align-items:center;
    gap:15px;
    margin-bottom:18px;
    text-decoration:none;
    color:#000;
    background:#fafafa;
    padding:12px;
    border-radius:14px;
    transition:0.2s;
}

.product-card:hover{
    background:#f0f0f0;
}

.product-img{
    width:150px;
    height:150px;
    object-fit:contain;
    border-radius:14px;
    border:1px solid #ddd;
    background:#fff;
    padding:6px;
    transition:0.3s;
}

.product-img:hover{
    transform:scale(1.05);
}

/* TIMELINE */

.timeline{
    position:relative;
    padding-left:35px;
}

.timeline::before{
    content:"";
    position:absolute;
    left:12px;
    top:0;
    width:3px;
    height:calc(100% - 35px);
    background:#e0e0e0;
    border-radius:10px;
}

.timeline-progress{
    position:absolute;
    left:12px;
    top:0;
    width:3px;
    background:#2ecc71;
    border-radius:10px;
    animation:growLine 1s ease forwards;
}

@keyframes growLine{
    from{
        height:0;
    }
}

.timeline-step{
    position:relative;
    margin-bottom:22px;
    font-size:15px;
    padding-bottom:8px;
}

.timeline-step:last-child{
    margin-bottom:0;
}

.timeline-step::before{
    content:"";
    position:absolute;
    left:-28px;
    top:2px;
    width:14px;
    height:14px;
    border-radius:50%;
    background:#cfcfcf;
}

.timeline-step.active::before{
    background:#2ecc71;
}

.timeline-step.delivered::before{
    content:"✔";
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:9px;
    font-weight:bold;
    background:#2ecc71;
}

.step-date{
    font-size:12px;
    color:#777;
    margin-top:2px;
}

/* TITLE */

.order-title{
    font-size:28px;
    font-weight:700;
    margin-bottom:15px;
}

/* BUTTON */

.btn-dark{
    border-radius:10px;
}

/* MOBILE */

@media(max-width:768px){

.product-card{
    align-items:flex-start;
}

.product-img{
    width:150px;
    height:150px;
}

.order-title{
    font-size:24px;
}

}

</style>

</head>

<body class="p-3">

<div class="container">

<!-- ORDER HEADER -->

<div class="card-box">

<div class="order-title">
📦 Track Order
</div>

<p>
<b>Order Code:</b> <?= $order_code ?>
</p>

<p>
<b>Status:</b> <?= $order['order_status'] ?>
</p>

<p>
<b>Order Date:</b>
<?= date("d M Y, h:i A", strtotime($order['created_at'])) ?>
</p>

<hr>

<?php 
$grandTotal = 0;

while($item = $items->fetch_assoc()){ 
?>

<?php

/* COLOR IMAGE FIX */

$img = $item['image'];

if(!empty($item['color'])){

    $color = strtolower($item['color']);

    $q = $conn->query("
    SELECT color_image
    FROM product_colors
    WHERE product_id='".$item['product_id']."'
    AND LOWER(color_name)='$color'
    LIMIT 1
    ");

    if($q && $q->num_rows > 0){

        $row = $q->fetch_assoc();

        $img = $row['color_image'];
    }
}

/* PRICE FIX */

$price = $item['price'];

if($price == 0){

    $res = $conn->query("
    SELECT price 
    FROM products 
    WHERE id='".$item['product_id']."'
    ");

    if($res && $res->num_rows > 0){

        $p = $res->fetch_assoc();

        $price = $p['price'];
    }
}

/* TOTAL */

$total = $price * $item['qty'];

$grandTotal += $total;

?>

<a 
href="product-detail.php?id=<?= $item['product_id'] ?>"
class="product-card"
>

<img 
src="uploads/<?= $img ?>" 
class="product-img"
>
<div>

<div style="font-size:18px;font-weight:700;">
<?= $item['product_name'] ?>
</div>

<div style="margin-top:4px;">
Size: <?= $item['size'] ?>
</div>

<div>
Color: <?= ucfirst($item['color']) ?>
</div>

<div>
Qty: <?= $item['qty'] ?>
</div>

<div style="
margin-top:5px;
font-size:18px;
font-weight:bold;
color:#198754;
">
₹<?= $total ?>
</div>

<div style="
margin-top:6px;
font-size:14px;
color:#0d6efd;
font-weight:600;
">
View Product →
</div>

</div>

</a>

<?php } ?>

<hr>

<h5>
Total: ₹<?= $order['total_amount'] ?>
</h5>
</div>

<!-- ORDER PROGRESS -->

<div class="card-box">

<h5 class="mb-4">
Order Progress
</h5>

<div class="timeline">

<div 
class="timeline-progress" 
style="height: <?= $progressHeight ?>px;"
>
</div>

<div class="timeline-step <?= $currentIndex >= 0 ? 'active' : '' ?>">

Confirmed

<div class="step-date">
<?= $order['confirmed_at']
? date("d M Y, h:i A", strtotime($order['confirmed_at']))
: '' ?>
</div>

</div>

<div class="timeline-step <?= $currentIndex >= 1 ? 'active' : '' ?>">

Packed

<div class="step-date">
<?= $order['packed_at']
? date("d M Y, h:i A", strtotime($order['packed_at']))
: '' ?>
</div>

</div>

<div class="timeline-step <?= $currentIndex >= 2 ? 'active' : '' ?>">

Dispatched

<div class="step-date">
<?= $order['shipped_at']
? date("d M Y, h:i A", strtotime($order['shipped_at']))
: '' ?>
</div>

</div>

<div class="timeline-step <?= $currentIndex >= 3 ? 'active' : '' ?>">

Out for Delivery

<div class="step-date">
<?= $order['out_for_delivery_at']
? date("d M Y, h:i A", strtotime($order['out_for_delivery_at']))
: '' ?>
</div>

</div>

<div class="timeline-step <?= $currentIndex >= 4 ? 'active delivered' : '' ?>">

Delivered

<div class="step-date">
<?= $order['delivered_at']
? date("d M Y, h:i A", strtotime($order['delivered_at']))
: '' ?>
</div>

</div>

</div>

<?php if($canCancel){ ?>

<form method="POST" action="cancel-order.php" class="mt-4">

<input 
type="hidden"
name="order_code"
value="<?= $order_code ?>"
>

<button class="btn btn-danger w-100">
Cancel Order
</button>

</form>

<?php } ?>

<hr>

<h5>
Delivery Address
</h5>

<p>
<b><?= $order['name'] ?></b><br>
<?= $order['address'] ?><br>
Phone: <?= $order['mobile'] ?>
</p>

<hr>

<h5>
Payment Details
</h5>

<p>
Method: <?= $order['payment_method'] ?><br>

Status: <?= $order['payment_status'] ?><br>

Total: ₹<?= $order['total_amount'] ?>
</p>

<a href="index.php" class="btn btn-dark w-100 mt-3">
🛍 Continue Shopping
</a>

</div>

</div>

</body>
</html>