<?php
session_start();
include "db.php";


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: index.php");
  exit;
}


$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
  die("Invalid product ID");
}


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


if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}


$size  = $_POST['size']  ?? 'M';
$color = $_POST['color'] ?? 'black';


if (isset($_SESSION['cart'][$id])) {


  $_SESSION['cart'][$id]['qty'] += 1;

} else {


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


header("Location: view-cart.php");
exit;