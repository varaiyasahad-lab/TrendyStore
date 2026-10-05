<?php
session_start();
include "db.php";


if($_SERVER['REQUEST_METHOD'] !== 'POST'){
  header("Location: index.php");
  exit;
}


$id    = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$name  = isset($_POST['name']) ? trim($_POST['name']) : '';
$price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
$qty   = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
$size  = isset($_POST['size']) ? trim($_POST['size']) : '';
$colorName  = isset($_POST['color_name']) ? trim($_POST['color_name']) : '';
$colorImage = isset($_POST['color_image']) ? trim($_POST['color_image']) : '';


if($id <= 0){
  header("Location: index.php");
  exit;
}

if($size === ''){
  $_SESSION['error'] = "Please select size";
  header("Location: product-detail.php?id=".$id);
  exit;
}


if($colorName === '' && isset($_POST['from_product_page'])){
  $_SESSION['error'] = "Please select color";
  header("Location: product-detail.php?id=".$id);
  exit;
}


$product_query = mysqli_query(
$conn,
"SELECT * FROM products WHERE id='$id'"
);

$product = mysqli_fetch_assoc($product_query);

if($product){

   $name  = $product['name'];

   $price = $product['price'];

   if(empty($colorImage)){

      $colorImage =
      strpos($product['image'],'uploads/') !== false

      ? $product['image']

      : 'uploads/'.$product['image'];

   }

}


if($qty <= 0){
  $qty = 1;
}


if(!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])){
  $_SESSION['cart'] = [];
}


$key = $id . '_' . $size . '_' . $colorName;


if(isset($_SESSION['cart'][$key])){

    $_SESSION['cart'][$key]['qty'] += $qty;

}else{

    $_SESSION['cart'][$key] = [

        'id'    => $id,

        'name'  => !empty($name)
        ? $name
        : 'Product',

        'price' => !empty($price)
        ? $price
        : 0,

        'qty'   => $qty,

        'size'  => $size,

        'color' => !empty($colorName)
        ? $colorName
        : 'Default',

        'image' => !empty($colorImage)
        ? $colorImage
        : ''

    ];

}


header("Location: view-cart.php");
exit;
?>