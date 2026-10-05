<?php
session_start();
require_once "db.php";


if(!isset($_POST['id']) || !isset($_POST['size'])){
    die("Invalid Request");
}

$id   = (int)$_POST['id'];
$size = trim($_POST['size']);
$qty  = (int)($_POST['qty'] ?? 1);


$stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if(!$product){
    die("Product not found");
}


$price = $product['price'];
$total = $price * $qty;


$_SESSION['last_order'] = [
    "id"    => $id,
    "name"  => $product['name'],
    "size"  => $size,
    "price" => $price,
    "qty"   => $qty,
    "image" => $product['image']
];
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Confirmation</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
    font-family:Arial;
    background:#f4f4f4;
    margin:0;
}


.box{
    max-width:450px;
    margin:60px auto;
    background:#fff;
    padding:25px;
    text-align:center;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}


img{
    width:200px;
    height:200px;
    object-fit:contain;
    margin-bottom:15px;
}


h2{color:#28a745;}

p{
    margin:8px 0;
    font-size:15px;
}


a{
    display:inline-block;
    margin-top:20px;
    padding:10px 20px;
    background:#000;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
}
</style>
</head>

<body>

<div class="box">

    <h2>✅ Order Placed Successfully</h2>

    <img src="uploads/<?= htmlspecialchars($product['image']) ?>">

    <p><strong>Product:</strong> <?= htmlspecialchars($product['name']) ?></p>
    <p><strong>Size:</strong> <?= htmlspecialchars($size) ?></p>
    <p><strong>Qty:</strong> <?= $qty ?></p>
    <p><strong>Price:</strong> ₹<?= $price ?></p>
    <p><strong>Total:</strong> ₹<?= $total ?></p>

    <a href="index.php">Continue Shopping</a>

</div>

</body>
</html>