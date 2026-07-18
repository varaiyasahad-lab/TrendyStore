<?php
session_start();
include "db.php";

/* ❌ Only POST allowed */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: index.php");
  exit;
}

/* 🔍 GET PRODUCT ID */
$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
  die("Invalid product ID");
}

/* 🔥 FETCH PRODUCT FROM DB */
$stmt = $conn->prepare("
  SELECT id, name, price, image 
  FROM products 
  WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
  die("Product not found");
}

/* 🛒 INIT CART */
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

/* 🔥 DEFAULT VALUES (size & color optional) */
$size  = $_POST['size']  ?? 'M';
$color = $_POST['color'] ?? 'black';

/* 🔥 ADD / UPDATE CART */
if (isset($_SESSION['cart'][$id])) {

  // Increase quantity
  $_SESSION['cart'][$id]['qty'] += 1;

} else {

  // Add new product
  $_SESSION['cart'][$id] = [
    'id'    => $product['id'],
    'name'  => $product['name'],
    'price' => $product['price'],
    'image' => $product['image'],
    'qty'   => 1,
    'size'  => $size,
    'color' => $color
  ];
}

/* 🔁 REDIRECT TO CART PAGE */
header("Location: view-cart.php");
exit;