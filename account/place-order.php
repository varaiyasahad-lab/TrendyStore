<?php
session_start();

/* 🔴 Direct access block */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  die("Invalid request ❌");
}

/* 🔴 Payment check */
if (!isset($_POST['payment_method'])) {
  die("Payment method not selected ❌");
}

$payment = $_POST['payment_method'];

/* 🔴 Cart check */
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
  die("Cart is empty ❌");
}

/* ✅ SUCCESS (for now) */
echo "<h2>✅ Order Placed Successfully</h2>";
echo "<p>Payment Method: <b>$payment</b></p>";

echo "<pre>";
print_r($_SESSION['cart']);
echo "</pre>";

/* 🔴 Later yaha DB insert hoga */

/* OPTIONAL: cart clear */
// unset($_SESSION['cart']);
