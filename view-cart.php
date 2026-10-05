<?php
session_start();
include "db.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$checkAddress = mysqli_query($conn,"
SELECT id
FROM addresses
WHERE user_id='$user_id'
LIMIT 1
");

$hasAddress = mysqli_num_rows($checkAddress);

$userRes = $conn->query("
SELECT first_name,last_name
FROM users
WHERE id=$user_id
");

$user = ($userRes && $userRes->num_rows)
        ? $userRes->fetch_assoc()
        : [];

$addressRes = $conn->query("
SELECT *
FROM addresses
WHERE user_id=$user_id
ORDER BY id DESC
LIMIT 1
");

$address = ($addressRes && $addressRes->num_rows)
        ? $addressRes->fetch_assoc()
        : [];

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html>

<head>

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>My Cart</title>

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
    background:#f1f3f6;
    margin:0;
    padding-top:95px;
}

.header{
    background:#fff;
    padding:15px;
    font-size:20px;
    font-weight:bold;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

.address{
    background:#fff;
    padding:12px;
    font-size:14px;
    border-bottom:1px solid #ddd;
}

.cart-card{
    background:#fff;
    margin:10px;
    padding:12px;
    border-radius:10px;
}

.row{
    display:flex;
    gap:12px;
}

.cart-img{
    width:90px;
    height:90px;
    object-fit:contain;
    border-radius:8px;
}

.details{
    flex:1;
}

.details h4{
    margin:0;
    font-size:16px;
}

.rating{
    color:#388e3c;
    font-size:13px;
    margin:4px 0;
}

.price{
    font-size:20px;
    font-weight:bold;
}

.old{
    text-decoration:line-through;
    color:#777;
    font-size:13px;
    margin-left:5px;
}

.discount{
    color:green;
    font-size:14px;
    font-weight:bold;
    margin-left:5px;
}

.qty{
    margin-top:10px;
    display:flex;
    align-items:center;
    gap:8px;
}

.qty a{
    padding:4px 10px;
    background:#ddd;
    border-radius:5px;
    text-decoration:none;
    color:#000;
    font-weight:bold;
}

.qty a.disabled{
    background:#eee;
    color:#aaa;
    cursor:not-allowed;
    pointer-events:none;
}

.stock-text{
    font-size:12px;
    color:#e53935;
    margin-top:5px;
}

.actions{
    display:flex;
    justify-content:space-between;
    margin-top:12px;
    border-top:1px solid #eee;
    padding-top:10px;
}

.actions a{
    text-decoration:none;
    color:#555;
    font-weight:bold;
}

.price-box{
    background:#fff;
    margin:10px;
    padding:15px;
    border-radius:10px;
}

.price-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:10px;
}

.place-order{
    background:#fff;
    padding:15px;
    margin:10px;
    border-radius:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.place-order button{
    background:#ff9f00;
    border:none;
    padding:12px 25px;
    color:#fff;
    font-weight:bold;
    border-radius:5px;
    cursor:pointer;
}

.empty-cart{
    text-align:center;
    margin-top:50px;
    color:#555;
}

</style>

</head>

<body>

<?php include 'header.php'; ?>

<div class="header">
My Cart
</div>

<div class="address">

<b>
Deliver to:
<?= htmlspecialchars(($user['first_name'] ?? '').' '.($user['last_name'] ?? '')) ?>
</b>

<br>

<small>

<?php

if(!empty($address)){

    echo htmlspecialchars(
        $address['address'].", ".
        $address['city'].", ".
        $address['state']." - ".
        $address['pincode']
    );

}else{

    echo "No address added";

}

?>

</small>

</div>


<?php if(empty($cart)): ?>

<div class="empty-cart">

<h3>Cart is Empty</h3>

</div>

<?php endif; ?>


<?php foreach($cart as $key => $item): ?>

<?php

$id = isset($item['id']) ? (int)$item['id'] : 0;

if($id <= 0){
    continue;
}

$qty = isset($item['qty']) ? (int)$item['qty'] : 1;

$price = isset($item['price']) ? (int)$item['price'] : 0;

$name = $item['name'] ?? 'Product';

$size = $item['size'] ?? '';

$productRes = $conn->query("
SELECT *
FROM products
WHERE id=$id
");

$product = ($productRes && $productRes->num_rows)
        ? $productRes->fetch_assoc()
        : [];

$stock = 0;

if($size !== ''){

    $safeSize = $conn->real_escape_string($size);

    $sizeRes = $conn->query("
    SELECT stock
    FROM product_sizes
    WHERE product_id=$id
    AND size='$safeSize'
    LIMIT 1
    ");

    if($sizeRes && $sizeRes->num_rows){

        $sizeData = $sizeRes->fetch_assoc();

        $stock = (int)$sizeData['stock'];

    }

}else{

    $stockRes = $conn->query("
    SELECT SUM(stock) AS total_stock
    FROM product_sizes
    WHERE product_id=$id
    ");

    if($stockRes && $stockRes->num_rows){

        $stockData = $stockRes->fetch_assoc();

        $stock = (int)$stockData['total_stock'];

    }

}

if($stock > 0 && $qty > $stock){

    $qty = $stock;

    $_SESSION['cart'][$key]['qty'] = $stock;

}

$cartImage = $item['image'] ?? '';

if(empty($cartImage)){
    $cartImage = 'uploads/no-image.png';
}

if(strpos($cartImage, 'uploads/') === false){
    $cartImage = 'uploads/' . $cartImage;
}

$revRes = $conn->query("
SELECT AVG(rating) as avg_rating, COUNT(*) as total
FROM reviews
WHERE product_id=$id
");

$rev = ($revRes)
        ? $revRes->fetch_assoc()
        : [];

$rating = isset($rev['avg_rating'])
        ? round($rev['avg_rating'],1)
        : 0;

$total_reviews = $rev['total'] ?? 0;

$stars = "";

for($i=1; $i<=5; $i++){

    $stars .= ($i <= floor($rating))
        ? "⭐"
        : "☆";

}

$old = (isset($product['old_price']) && $product['old_price'] > 0)
        ? $product['old_price']
        : $price;

$discount = ($old > $price)
            ? round((($old - $price) / $old) * 100)
            : 0;

$sub = $price * $qty;

$total += $sub;

?>

<div class="cart-card">

<div class="row">

<a href="product-detail.php?id=<?= $id ?>">

<img src="<?= htmlspecialchars($cartImage) ?>" class="cart-img">

</a>

<div class="details">

<a href="product-detail.php?id=<?= $id ?>" style="text-decoration:none;color:#000;">

<h4>
<?= htmlspecialchars($name) ?>
</h4>

</a>

<?php if($size !== ''): ?>

<div style="font-size:13px;margin-top:4px;">
Size: <?= htmlspecialchars($size) ?>
</div>

<?php endif; ?>

<div class="rating">

<?= $stars ?>

<?= $rating ?>

(<?= $total_reviews ?>)

</div>

<div class="price">

₹<?= $price ?>

<span class="old">
₹<?= $old ?>
</span>

<span class="discount">
<?= $discount ?>% off
</span>

</div>


<div class="qty">

<a href="update-cart.php?key=<?= $key ?>&action=minus">
−
</a>

<span>
<?= $qty ?>
</span>

<?php if($stock > 0 && $qty < $stock): ?>

<a href="update-cart.php?key=<?= $key ?>&action=plus">
+
</a>

<?php else: ?>

<a class="disabled">
+
</a>

<?php endif; ?>

</div>


<?php if($stock > 0 && $stock <= 5): ?>

<div class="stock-text">

Only <?= $stock ?> left in stock

</div>

<?php elseif($stock <= 0): ?>

<div class="stock-text">

Out of stock

</div>

<?php endif; ?>

</div>

</div>


<div class="actions">

<a href="update-cart.php?key=<?= $key ?>&action=remove">
Remove
</a>

<a href="product-detail.php?id=<?= $id ?>">
Buy now
</a>

</div>

</div>

<?php endforeach; ?>


<?php if(!empty($cart)){ ?>

<div class="price-box">

<div class="price-row">

<span>MRP</span>

<span>
₹<?= $total ?>
</span>

</div>

<div class="price-row">

<span>Discount</span>

<span style="color:green;">

-₹<?= round($total * 0.2) ?>

</span>

</div>

<div class="price-row">

<b>Total</b>

<b>
₹<?= $total ?>
</b>

</div>

</div>


<div class="place-order">

<b style="font-size:20px;">

₹<?= $total ?>

</b>


<?php if($hasAddress > 0){ ?>

<button onclick="location.href='payment.php'">
Place Order
</button>

<?php }else{ ?>

<button onclick="location.href='adresses1.php'">
Place Order
</button>

<?php } ?>

</div>

<?php } ?>

<?php include 'footer.php'; ?>

</body>

</html>