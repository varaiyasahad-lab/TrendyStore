<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

include "db.php";

error_reporting(E_ALL);
ini_set('display_errors',1);

if(isset($_POST['save_product']))
{

    // PRODUCT DETAILS

    $name        = mysqli_real_escape_string($conn,$_POST['name']);
    $category    = mysqli_real_escape_string($conn,$_POST['category']);
    $gender      = mysqli_real_escape_string($conn,$_POST['gender']);
    $brand       = mysqli_real_escape_string($conn,$_POST['brand']);
    $description = mysqli_real_escape_string($conn,$_POST['description']);
    $occasion    = mysqli_real_escape_string($conn,$_POST['occasion']);

    $price       = !empty($_POST['price']) ? $_POST['price'] : 0;
    $old_price   = !empty($_POST['old_price']) ? $_POST['old_price'] : 0;
    $stock       = !empty($_POST['stock']) ? $_POST['stock'] : 0;
    $discount    = !empty($_POST['discount']) ? $_POST['discount'] : 0;

    $best_seller = isset($_POST['best_seller']) ? 1 : 0;

    // ======================
    // MAIN IMAGE
    // ======================

    $image="";

    if(isset($_FILES['image']) && $_FILES['image']['error']==0)
    {
        $image = basename($_FILES['image']['name']);

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "uploads/".$image
        );
    }

    // ======================
    // INSERT PRODUCT
    // ======================

    $insert = mysqli_query($conn,"
    INSERT INTO products
    (
        name,
        category,
        gender,
        brand,
        description,
        occasion,
        price,
        old_price,
        stock,
        discount,
        image,
        best_seller
    )
    VALUES
    (
        '$name',
        '$category',
        '$gender',
        '$brand',
        '$description',
        '$occasion',
        '$price',
        '$old_price',
        '$stock',
        '$discount',
        '$image',
        '$best_seller'
    )
    ");

    if(!$insert)
    {
        die(mysqli_error($conn));
    }

    $product_id = mysqli_insert_id($conn);
        // ======================
    // SAVE COLORS
    // ======================

    if (!empty($_POST['color_name'])) {

        foreach ($_POST['color_name'] as $i => $color_name) {

            $color_name = trim(mysqli_real_escape_string($conn, $color_name));

            if ($color_name == "") {
                continue;
            }

            $color_image = "";

            if (
                isset($_FILES['color_image']['name'][$i]) &&
                $_FILES['color_image']['error'][$i] == 0
            ) {

                // Original filename
                $color_image = basename($_FILES['color_image']['name'][$i]);

                move_uploaded_file(
                    $_FILES['color_image']['tmp_name'][$i],
                    "uploads/" . $color_image
                );
            }

            $insertColor = mysqli_query($conn,"
                INSERT INTO product_colors
                (
                    product_id,
                    color_name,
                    color_image
                )
                VALUES
                (
                    '$product_id',
                    '$color_name',
                    '$color_image'
                )
            ");

            if(!$insertColor){
                die("Color Error : ".mysqli_error($conn));
            }
        }
    }


    // ======================
    // SAVE SIZES
    // ======================

    if (!empty($_POST['size'])) {

        foreach ($_POST['size'] as $i => $size) {

            $size = trim(mysqli_real_escape_string($conn, $size));

            if ($size == "") {
                continue;
            }

            $size_price = !empty($_POST['size_price'][$i])
                ? (float)$_POST['size_price'][$i]
                : 0;

            $size_stock = !empty($_POST['size_stock'][$i])
                ? (int)$_POST['size_stock'][$i]
                : 0;

            $insertSize = mysqli_query($conn,"
                INSERT INTO product_sizes
                (
                    product_id,
                    size,
                    price,
                    stock
                )
                VALUES
                (
                    '$product_id',
                    '$size',
                    '$size_price',
                    '$size_stock'
                )
            ");

            if(!$insertSize){
                die("Size Error : ".mysqli_error($conn));
            }
        }
    }


    header("Location: admin-products.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Add Product</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
    padding:30px;
}

.box{
    max-width:900px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.section-title{
    font-size:20px;
    color:#0d6efd;
    font-weight:600;
    margin-top:25px;
}

</style>

</head>

<body>

<div class="box">

<h2 class="mb-4">➕ Add Product</h2>

<a href="admin-products.php" class="btn btn-secondary mb-4">
← Back
</a>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="save_product" value="1">

<div class="row">

<div class="col-md-6 mb-3">
<label>Product Name</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Category</label>
<input type="text" name="category" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Gender</label>
<select name="gender" class="form-control">
<option value="Men">Men</option>
<option value="Women">Women</option>
<option value="Unisex">Unisex</option>
</select>
</div>

<div class="col-md-6 mb-3">
<label>Brand</label>
<input type="text" name="brand" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Price</label>
<input type="number" name="price" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Old Price</label>
<input type="number" name="old_price" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Stock</label>
<input type="number" name="stock" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Main Product Image</label>
<input type="file" name="image" class="form-control" accept="image/*" required>
</div>

<div class="col-12 mb-3">
<label>Description</label>
<textarea name="description" class="form-control" rows="4"></textarea>
</div>

<div class="col-md-6 mb-3">
<label>Occasion</label>
<input type="text" name="occasion" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Discount (%)</label>
<input type="number" name="discount" class="form-control">
</div>

<div class="col-12">

<h4 class="section-title">
Product Colors (Max 4)
</h4>

<hr>

</div>

<?php for($i=0;$i<4;$i++){ ?>

<div class="col-md-6 mb-3">

<input
type="text"
name="color_name[]"
class="form-control"
placeholder="Color Name">

</div>

<div class="col-md-6 mb-3">

<input
type="file"
name="color_image[]"
class="form-control"
accept="image/*">

</div>

<?php } ?>

<div class="col-12">

<h4 class="section-title">
Product Sizes (Max 4)
</h4>

<hr>

</div>

<?php for($i=0;$i<4;$i++){ ?>

<div class="col-md-4 mb-3">

<input
type="text"
name="size[]"
class="form-control"
placeholder="Size">

</div>

<div class="col-md-4 mb-3">

<input
type="number"
name="size_price[]"
class="form-control"
placeholder="Price">

</div>

<div class="col-md-4 mb-3">

<input
type="number"
name="size_stock[]"
class="form-control"
placeholder="Stock">

</div>

<?php } ?>

<div class="col-12">

<div class="form-check mt-3">

<input
class="form-check-input"
type="checkbox"
name="best_seller"
id="best">

<label
class="form-check-label"
for="best">

Best Seller

</label>

</div>

<button
type="submit"
class="btn btn-success w-100 mt-4">

💾 Save Product

</button>

</div>

</div>

</form>

</div>

</body>
</html>