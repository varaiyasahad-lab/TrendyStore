<?php
session_start();
require_once __DIR__ . '/../db.php';

if(!isset($_SESSION['admin'])){
  header("Location: auth.php");
  exit;
}

$id = (int)$_GET['id'];

$product = $conn->query(
  "SELECT * FROM products WHERE id=$id"
)->fetch_assoc();

if(isset($_POST['update'])){

  $name = $_POST['name'];
  $cat  = $_POST['category'];
  $price = $_POST['price'];

  $conn->query("
    UPDATE products SET
    name='$name',
    category='$cat',
    price='$price'
    WHERE id=$id
  ");

  /* size stock */
  foreach($_POST['size'] as $size=>$stock){

    $check = $conn->query("
      SELECT id FROM product_sizes
      WHERE product_id=$id AND size='$size'
    ");

    if($check->num_rows){
      $conn->query("
        UPDATE product_sizes SET stock=$stock
        WHERE product_id=$id AND size='$size'
      ");
    }else{
      $conn->query("
        INSERT INTO product_sizes(product_id,size,stock)
        VALUES($id,'$size',$stock)
      ");
    }
  }

  header("Location: list-products.php");
}
?>

<h2>Edit Product</h2>

<form method="post">

Name <br>
<input name="name" value="<?= $product['name'] ?>"><br><br>

Category <br>
<select name="category">
  <option <?= $product['category']=="Men"?"selected":"" ?>>Men</option>
  <option <?= $product['category']=="Women"?"selected":"" ?>>Women</option>
  <option <?= $product['category']=="Kids"?"selected":"" ?>>Kids</option>
</select><br><br>

Price <br>
<input type="number" name="price" value="<?= $product['price'] ?>"><br><br>

<h3>Size Stock</h3>
S: <input type="number" name="size[S]" value="0"><br>
M: <input type="number" name="size[M]" value="0"><br>
L: <input type="number" name="size[L]" value="0"><br>
XL: <input type="number" name="size[XL]" value="0"><br><br>

<button name="update">UPDATE</button>
</form>
