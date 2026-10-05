<?php
session_start();
?>
<?php
include "db.php";

$order_code = $_GET['code'] ?? '';
$view = $_GET['view'] ?? '';

$index = isset($_GET['i']) ? (int)$_GET['i'] : 0;

$order = $conn->query("
SELECT * FROM orders 
WHERE order_code='$order_code'
")->fetch_assoc();

if(!$order){
    die("Order Not Found");
}


if(isset($_POST['cancel_order'])){

    $reason  = $_POST['reason'] ?? '';
    $comment = $_POST['comment'] ?? '';

    $conn->query("
    UPDATE orders 
    SET order_status='cancelled',
        cancel_reason='$reason - $comment',
        cancelled_at=NOW()
    WHERE order_code='$order_code'
    ");

    header("Location: order-details.php?code=".$order_code);
    exit;
}

if(isset($_POST['return_order'])){

    $reason = $_POST['return_reason'] ?? '';

    $conn->query("
    UPDATE orders 
    SET return_status='Return Requested',
        return_reason='$reason',
        returned_at=NOW()
    WHERE order_code='$order_code'
    ");

    header("Location: order-details.php?code=".$order_code);
    exit;
}


$status = strtolower(trim($order['order_status'] ?? ''));

$return = strtolower(trim($order['return_status'] ?? ''));
$refund = strtolower(trim($order['refund_status'] ?? ''));

if($return == "none") $return = "";
if($refund == "none") $refund = "";


$order_id = $order['id'];

$items = $conn->query("
SELECT 
oi.*, 
p.name,
pc.color_image

FROM order_items oi

JOIN products p 
ON oi.product_id = p.id

LEFT JOIN product_colors pc
ON pc.product_id = oi.product_id
AND LOWER(pc.color_name) = LOWER(oi.color)

WHERE oi.order_id = '$order_id'

ORDER BY oi.id ASC
");
$total = 0;


?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
 *{
    box-sizing:border-box;
}

html,body{
    margin:0;
    padding:0;
    overflow-x:hidden;
}
body{
    font-family:Arial;
    background:#f5f5f6;
    margin:0;
    padding-top:95px;
}

.card{
    background:#fff;
    margin:10px;
    padding:15px;
    border-radius:12px;
}


.product-box{
    display:flex;
    align-items:center;
    gap:20px;
    padding:18px 0;
    margin-bottom:15px;
}

.product-img{
    width:150px;
    height:150px;
    object-fit:contain;
    border:1px solid #ddd;
    border-radius:12px;
}


.timeline{
    position:relative;
    padding-left:30px;
}

.timeline::before{
    content:"";
    position:absolute;
    left:6px;
    top:10px;
    width:3px;
    height:85%;
    background:#ddd;
    border-radius:10px;
}

.progress{
    position:absolute;
    left:6px;
    top:10px;
    width:3px;
    background:green;
    z-index:1;
    border-radius:10px;
}

.gold .progress{
    background:#c89b3c;
}

.step{
    position:relative;
    padding-bottom:22px;
}

.step:last-child{
    padding-bottom:0;
}

.step::before{
    content:"";
    width:14px;
    height:14px;
    border-radius:50%;
    background:#ccc;
    position:absolute;
    left:-29px;
    top:5px;
    z-index:2;
}

.active::before{
    background:green;
}

.gold .active::before{
    background:#c89b3c;
}


.btn{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    color:#fff;
    margin-top:10px;
    font-size:15px;
}

.red{
    background:red;
}

.blue{
    background:#007bff;
}

input,select{
    width:100%;
    padding:10px;
    margin-top:10px;
    border:1px solid #ddd;
    border-radius:6px;
    box-sizing:border-box;
}


@media(max-width:600px){

.product-box{
    align-items:flex-start;
}

.product-img{
    width:120px;
    height:120px;
}

}

</style>

</head>

<body>
    <?php include 'header.php'; ?>
    <div style="
background:#fff;
padding:12px 15px;
margin:10px;
border-radius:10px;
font-weight:bold;
">
<a href="my-orders.php" style="
text-decoration:none;
color:#007bff;
font-size:15px;
">
← Back to Orders
</a>
</div>


<div class="card">

<b>Products</b><br><br>

<?php

$total = 0;

?>

<?php while($item = $items->fetch_assoc()){ 

if(
    empty($item['name']) ||
    empty($item['price']) ||
    empty($item['qty'])
){
    continue;
}

?>

<?php $total += $item['price'] * $item['qty']; ?>

<a href="product-detail.php?id=<?= $item['product_id'] ?>" style="text-decoration:none;color:black;">

<div class="product-box">

<img 
src="uploads/<?php echo $item['color_image']; ?>" 
class="product-img"
>

<div>

<div style="font-size:22px;font-weight:bold;margin-bottom:6px;">
<?= $item['name'] ?>
</div>

<div style="font-size:18px;color:#222;">
₹<?= $item['price'] ?> × <?= $item['qty'] ?>
</div>

<div style="margin-top:5px;color:#666;">
Size: <?= $item['size'] ?>
</div>

<div style="margin-top:5px;color:#666;">
Color: <?= $item['color'] ?>
</div>

<div style="margin-top:10px;font-weight:bold;color:#007bff;">
View Product →
</div>

</div>

</div>

</a>

<hr style="margin:20px 0;">

<?php } ?>

</div>


<?php if($status=="cancelled"){ ?>

<div class="card">

<b>Cancelled</b><br><br>

<div class="timeline">

<div class="progress" style="height:74px;"></div>

<div class="step active">
Confirmed<br>
<small><?= $order['created_at'] ?></small>
</div>

<div class="step active">
Cancelled<br>
<small><?= $order['cancelled_at'] ?></small>
</div>

</div>

</div>

<?php } ?>


<?php if(($return=="" || $view=="original") && $status!="cancelled"){ ?>

<div class="card">

<b>Order Status</b><br><br>

<?php

$steps = [
"confirmed"=>$order['created_at'],
"packed"=>$order['packed_at'],
"shipped"=>$order['shipped_at'],
"out for delivery"=>$order['out_for_delivery_at'],
"delivered"=>$order['delivered_at']
];

$currentIndex = array_search(
$status,
array_keys($steps)
);

$lineHeight = 0;

if($currentIndex > 0){
 $lineHeight = ($currentIndex * 58) ;
}

?>

<div class="timeline">

<?php if($currentIndex > 0){ ?>

<div class="progress" style="height:<?= $lineHeight ?>px;"></div>

<?php } ?>

<?php 
$i = 0;

foreach($steps as $k=>$date){ ?>

<div class="step <?= ($i <= $currentIndex)?'active':'' ?>">

<?= ucfirst($k) ?><br>

<?php if(!empty($date)){ ?>
<small><?= $date ?></small>
<?php } ?>

</div>

<?php $i++; } ?>

</div>

<!-- CANCEL -->

<?php if(in_array($status,["confirmed"])){ ?>

<form method="POST">

<select name="reason" required>
<option>Wrong Order</option>
<option>Change Mind</option>
<option>Delay</option>
<option>Found Better Price</option>
<option>Other</option>
</select>

<input type="text" name="comment" placeholder="Comment">

<button class="btn red" name="cancel_order">
Cancel Order
</button>

</form>

<?php } ?>


<?php if($status=="delivered" && $return==""){ ?>

<form method="POST">

<select name="return_reason" required>
<option>Wrong Item</option>
<option>Damaged Product</option>
<option>Size Issue</option>
<option>Quality Issue</option>
<option>Not as Expected</option>
<option>Late Delivery</option>
<option>Other</option>
</select>

<button class="btn blue" name="return_order">
Return & Refund
</button>

</form>

<?php } ?>

</div>

<?php } ?>


<?php if($return!="" && $view!="original"){ ?>

<div class="card">

<b style="color:#c89b3c">
Return & Refund
</b><br><br>

<?php

$currentReturn = 0;

if($return=="return requested"){
    $currentReturn = 0;
}
elseif($return=="picked up"){
    $currentReturn = 1;
}
elseif($return=="quality check"){
    $currentReturn = 2;
}

if($refund=="processing"){
    $currentReturn = 3;
}
elseif($refund=="completed"){
    $currentReturn = 4;
}

$returnHeight = 0;

if($currentReturn > 0){
$returnHeight = ($currentReturn * 60) ;
}

?>

<div class="timeline gold">

<?php if($currentReturn > 0){ ?>

<div class="progress" style="height:<?= $returnHeight ?>px;"></div>

<?php } ?>

<div class="step <?= ($currentReturn>=0)?'active':'' ?>">
Return Requested<br>
<small><?= $order['returned_at'] ?></small>
</div>

<div class="step <?= ($currentReturn>=1)?'active':'' ?>">
Picked Up<br>
<small><?= $order['pickup_at'] ?></small>
</div>

<div class="step <?= ($currentReturn>=2)?'active':'' ?>">
Quality Check<br>
<small><?= $order['quality_check_at'] ?></small>
</div>

<div class="step <?= ($currentReturn>=3)?'active':'' ?>">
Refund Initiated<br>
<small><?= $order['refund_initiated_at'] ?></small>
</div>

<div class="step <?= ($currentReturn>=4)?'active':'' ?>">
Refunded<br>
<small><?= $order['refund_completed_at'] ?></small>
</div>

</div>

</div>

<div class="card">

<a href="order-details.php?code=<?= $order_code ?>&view=original">
View Original Order
</a>

</div>

<?php } ?>


<div class="card">

<b>Delivery Address</b><br>

<?= $order['name'] ?><br>

<?= $order['address'] ?><br>

Phone: <?= $order['mobile'] ?>

</div>


<div class="card">

<b>Payment Details</b><br><br>

Order Amount: ₹<?= $order['total'] ?>

<?php if($order['voucher_code'] != ''){ ?>
<p style="color:green;">
Discount Applied (<?= $order['voucher_code'] ?>)
</p>
<?php } ?>

Total: ₹<?= $order['total_amount'] ?>
<hr>

Payment Mode:
<?= $order['payment_method'] ?>

</div>
<?php include 'footer.php'; ?>
</body>
</html>