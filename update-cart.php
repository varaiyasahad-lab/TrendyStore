<?php
session_start();

$key    = $_GET['key']    ?? '';
$action = $_GET['action'] ?? '';

if ($key === '' || !isset($_SESSION['cart'][$key])) {
  header("Location: view-cart.php");
  exit;
}

/* 🔐 SAFETY: ensure cart item is array */
if (!is_array($_SESSION['cart'][$key])) {
  // old broken cart item → remove it
  unset($_SESSION['cart'][$key]);
  header("Location: view-cart.php");
  exit;
}

switch ($action) {

  case "plus":
    $_SESSION['cart'][$key]['qty'] =
      (int)($_SESSION['cart'][$key]['qty'] ?? 1) + 1;
    break;

  case "minus":
    $_SESSION['cart'][$key]['qty'] =
      (int)($_SESSION['cart'][$key]['qty'] ?? 1) - 1;

    if ($_SESSION['cart'][$key]['qty'] <= 0) {
      unset($_SESSION['cart'][$key]);
    }
    break;

  case "remove":
    unset($_SESSION['cart'][$key]);
    break;

  default:
    // invalid action → do nothing
    break;
}

header("Location: view-cart.php");
exit;
