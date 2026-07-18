<?php
session_start();

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin-login.php");
    exit;
}

include "db.php";

if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: admin-products.php");
    exit;
}

$id = (int)$_GET['id'];

$product = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM products
WHERE id='$id'
"));

if(!$product){
    die("Product Not Found");
}

/* FETCH SIZES */
$sizes = mysqli_query($conn,"
SELECT * FROM product_sizes
WHERE product_id='$id'
");

/* FETCH COLORS */
$colors = mysqli_query($conn,"
SELECT * FROM product_colors
WHERE product_id='$id'
");

if(isset($_POST['update'])){

    $name     = mysqli_real_escape_string($conn,$_POST['name']);
    $price    = $_POST['price'];
    $stock    = $_POST['stock'];
    $brand    = mysqli_real_escape_string($conn,$_POST['brand']);
    $occasion = mysqli_real_escape_string($conn,$_POST['occasion']);
    $popular     = isset($_POST['popular']) ? 1 : 0;
    $best_seller = isset($_POST['best_seller']) ? 1 : 0;

    /* UPDATE MAIN PRODUCT */
$update = mysqli_query($conn,"
UPDATE products
SET
name='$name',
price='$price',
stock='$stock',
brand='$brand',
occasion='$occasion',
popular='$popular',
best_seller='$best_seller'
WHERE id='$id'
");

if(!$update){
    die("Product Update Error: ".mysqli_error($conn));
}

    /* UPDATE SIZE STOCK + PRICE */
    if(isset($_POST['size_stock'])){
        foreach($_POST['size_stock'] as $size_id => $qty){

          $size_update = mysqli_query($conn,"
UPDATE product_sizes
SET stock='$qty'
WHERE id='$size_id'
");

if(!$size_update){
    die("Size Update Error: ".mysqli_error($conn));
}
        }
    }

    /* UPDATE COLORS */
    if(isset($_POST['color_name'])){
        foreach($_POST['color_name'] as $color_id => $color){

            $color = mysqli_real_escape_string($conn,$color);

            /* IF IMAGE CHANGED */
            if(!empty($_FILES['color_image']['name'][$color_id])){

              $imageName = $_FILES['color_image']['name'][$color_id];

                move_uploaded_file(
                    $_FILES['color_image']['tmp_name'][$color_id],
                    "uploads/" . $imageName
                );

               $color_update = mysqli_query($conn,"
UPDATE product_colors
SET color_name='$color',
    color_image='$imageName'
WHERE id='$color_id'
");

if(!$color_update){
    die("Color Update Error: ".mysqli_error($conn));
}

   }else{

   $color_update = mysqli_query($conn,"
UPDATE product_colors
SET color_name='$color'
WHERE id='$color_id'
");

   if(!$color_update){
       die("Color Update Error: ".mysqli_error($conn));
   }

}
}
    header("Location: admin-products.php");
    exit;
}
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Product</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
    padding:30px;
}
.box{
    background:#fff;
    padding:30px;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
    max-width:900px;
    margin:auto;
}
</style>
</head>
<body>

<div class="box">

<h2 class="mb-4">✏️ Edit Product</h2>

<a href="admin-products.php" class="btn btn-secondary mb-4">
← Back
</a>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label>Product Name</label>
<input type="text"
name="name"
value="<?= htmlspecialchars($product['name']) ?>"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Price</label>
<input type="number"
name="price"
value="<?= $product['price'] ?>"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Total Stock</label>
<input type="number"
name="stock"
value="<?= $product['stock'] ?>"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Brand</label>
<input type="text"
name="brand"
value="<?= htmlspecialchars($product['brand']) ?>"
class="form-control">
</div>

<div class="mb-3">
<label>Occasion</label>
<input type="text"
name="occasion"
value="<?= htmlspecialchars($product['occasion']) ?>"
class="form-control">
</div>

<div class="mb-3">
<label>Show In Popular Products</label>
<br>
<input type="checkbox"
name="popular"
value="1"
<?= ($product['popular'] == 1 ? 'checked' : '') ?>>
 Add to Popular Products
</div>

<div class="mb-3">
<label>Show In Best Seller</label>
<br>
<input type="checkbox"
name="best_seller"
value="1"
<?= ($product['best_seller'] == 1 ? 'checked' : '') ?>>
 Add to Best Seller
</div>

<hr>

<h4>Size Wise Stock</h4>

<?php while($size = mysqli_fetch_assoc($sizes)){ ?>

<div class="mb-3">
<label>Size <?= strtoupper($size['size']) ?></label>

<input type="number"
name="size_stock[<?= $size['id'] ?>]"
value="<?= $size['stock'] ?>"
class="form-control">
</div>

<?php } ?>

<hr>

<h4>Colors</h4>

<?php while($color = mysqli_fetch_assoc($colors)){ ?>

<div class="mb-3">
<label>Color Name</label>

<input type="text"
name="color_name[<?= $color['id'] ?>]"
value="<?= htmlspecialchars($color['color_name']) ?>"
class="form-control">
</div>

<div class="mb-3">
<label>Change Color Image</label>

<input type="file"
name="color_image[<?= $color['id'] ?>]"
class="form-control">

<br>

<?php if(!empty($color['color_image'])){ ?>
<img src="uploads/<?= $color['color_image'] ?>" width="80">
<?php } ?>

</div>

<?php } ?>

<button type="submit" name="update" class="btn btn-success">
Update Product
</button>

</form>

</div>

</body>
</html>