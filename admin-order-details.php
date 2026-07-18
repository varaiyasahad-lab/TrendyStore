<?php
session_start();

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin-login.php");
    exit;
}

include "db.php";

if(!isset($_GET['id'])){
    header("Location: admin-orders.php");
    exit;
}

$orderId = (int)$_GET['id'];

$order = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM orders
WHERE id='$orderId'
"));

if(!$order){
    die("Order not found");
}

$items = mysqli_query($conn,"
SELECT oi.*, p.name AS product_name
FROM order_items oi
LEFT JOIN products p ON oi.product_id = p.id
WHERE oi.order_id='$orderId'
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Order Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
    font-family:'Segoe UI',sans-serif;
}

.container-box{
    max-width:1200px;
    margin:30px auto;
}

.card{
    border:none;
    border-radius:15px;
    box-shadow:0 3px 15px rgba(0,0,0,.08);
}

.table th{
    background:#111827;
    color:#fff;
}

.badge{
    font-size:14px;
    padding:8px 12px;
}
</style>
</head>
<body>

<div class="container-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>📦 Order Details</h2>

<a href="admin-orders.php" class="btn btn-dark">
← Back
</a>

</div>

<div class="card p-4 mb-4">

<h4 class="mb-3">Customer Information</h4>

<div class="row">

<div class="col-md-6 mb-3">
<b>Order Code:</b><br>
<?= $order['order_code'] ?>
</div>

<div class="col-md-6 mb-3">
<b>Customer Name:</b><br>
<?= $order['name'] ?>
</div>

<div class="col-md-6 mb-3">
<b>Mobile:</b><br>
<?= $order['mobile'] ?>
</div>

<div class="col-md-6 mb-3">
<b>Original Total:</b><br>
₹<?= $order['total'] ?>
</div>

<div class="col-md-6 mb-3">
<b>Voucher Discount:</b><br>
- ₹<?= $order['total'] - $order['total_amount'] ?>
</div>

<div class="col-md-6 mb-3">
<b>Final Total:</b><br>
₹<?= $order['total_amount'] ?>
</div>

<div class="col-md-6 mb-3">
<b>Payment Method:</b><br>
<?= $order['payment_method'] ?>
</div>

<div class="col-md-6 mb-3">
<b>Order Status:</b><br>

<span class="badge bg-primary">
<?= $order['order_status'] ?>
</span>

</div>

<div class="col-md-12">
<b>Address:</b><br>
<?= nl2br($order['address']) ?>
</div>

</div>

</div>

<div class="card p-4">

<h4 class="mb-3">Ordered Products</h4>

<div class="table-responsive">

<table class="table table-bordered">

<thead>
<tr>
<th>#</th>
<th>Product</th>
<th>Size</th>
<th>Color</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
</tr>
</thead>

<tbody>

<?php
$i = 1;

while($item = mysqli_fetch_assoc($items)){
?>

<tr>

<td><?= $i++ ?></td>

<td><?= $item['product_name'] ?></td>

<td><?= $item['size'] ?></td>

<td><?= $item['color'] ?></td>

<td><?= $item['qty'] ?></td>

<td>₹<?= $item['price'] ?></td>

<td>₹<?= $item['price'] * $item['qty'] ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</body>
</html>