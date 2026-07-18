    <?php
    session_start();

    if(!isset($_SESSION['admin_logged_in'])){
        header("Location: admin-login.php");
        exit;
    }

    include "db.php";

    if(isset($_POST['add_product'])){

        $name        = mysqli_real_escape_string($conn,$_POST['name']);
        $category    = mysqli_real_escape_string($conn,$_POST['category']);
        $price       = $_POST['price'];
        $old_price   = $_POST['old_price'];
        $description = mysqli_real_escape_string($conn,$_POST['description']);
        $gender      = mysqli_real_escape_string($conn,$_POST['gender']);
        $stock       = $_POST['stock'];
        $brand       = mysqli_real_escape_string($conn,$_POST['brand']);
        $best_seller = isset($_POST['best_seller']) ? 1 : 0;
        $occasion = mysqli_real_escape_string($conn,$_POST['occasion']);
    $discount = mysqli_real_escape_string($conn,$_POST['discount']);

        $image = "";

        if(isset($_FILES['image']) && $_FILES['image']['error']==0){

            $image = time()."_".$_FILES['image']['name'];

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                "uploads/".$image
            );
        }

        mysqli_query($conn,"
        INSERT INTO products
        (
            name,
            category,
            price,
            image,
            description,
            gender,
            old_price,
            stock,
            brand,
            occasion,
            discount,
            best_seller

        )
        VALUES
        (
            '$name',
            '$category',
            '$price',
            '$image',
            '$description',
            '$gender',
            '$old_price',
            '$stock',
            '$brand',
            '$occasion',
            '$discount',
            '$best_seller'
        )
        ");

    $product_id = mysqli_insert_id($conn);

    for($i=0; $i<count($_POST['color_name']); $i++){

        $color_name = trim($_POST['color_name'][$i]);

        if($color_name != ""){

            $color_image = "";

            if(isset($_FILES['color_image']['name'][$i]) &&
            $_FILES['color_image']['error'][$i] == 0){

                $color_image = time()."_".$_FILES['color_image']['name'][$i];

                move_uploaded_file(
                    $_FILES['color_image']['tmp_name'][$i],
                    "uploads/".$color_image
                );
            }

            mysqli_query($conn,"
            INSERT INTO product_colors
            (product_id,color_name,color_image)
            VALUES
            ('$product_id','$color_name','$color_image')
            ");
        }
    }

    for($i=0; $i<count($_POST['size']); $i++){

        $size  = trim($_POST['size'][$i]);
        $price = $_POST['size_price'][$i];
        $stock = $_POST['size_stock'][$i];

        if($size != ""){

            mysqli_query($conn,"
            INSERT INTO product_sizes
            (product_id,size,price,stock)
            VALUES
            ('$product_id','$size','$price','$stock')
            ");
        }
    }

        header("Location: admin-products.php");
        exit;
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <title>Add Product</title>

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

    <h2 class="mb-4">➕ Add Product</h2>

    <a href="admin-products.php" class="btn btn-secondary mb-4">
    ← Back
    </a>

    <form method="POST" enctype="multipart/form-data">

    <div class="row">

    <div class="col-md-6 mb-3">
    <label>Product Name</label>
    <input type="text" name="name" class="form-control" required>
    </div>

    <div class="col-md-6 mb-3">
    <label>Category</label>
    <input type="text" name="category" class="form-control">
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
    <label>Product Image</label>
    <input type="file" name="image" class="form-control">
    </div>

    <div class="col-12 mb-3">
    <label>Description</label>
    <textarea
    name="description"
    class="form-control"
    rows="4"></textarea>
    </div>


    <div class="col-md-6 mb-3">
        <label>Occasion</label>
        <input type="text" name="occasion" class="form-control" placeholder="Casual, Party, Wedding">
    </div>

    <div class="col-md-6 mb-3">
        <label>Discount</label>
        <input type="text" name="discount" class="form-control" placeholder="10% OFF">
    </div>


    <h4 class="mt-4">Product Colors</h4>

    <input type="text"
    name="color_name[]"
    class="form-control mb-2"
    placeholder="">

    <input type="file"
    name="color_image[]"
    class="form-control mb-3">

    <input type="text"
    name="color_name[]"
    class="form-control mb-2"
    placeholder="">

    <input type="file"
    name="color_image[]"
    class="form-control mb-3">

    <input type="text"
    name="color_name[]"
    class="form-control mb-2"
    placeholder="">

    <input type="file"
    name="color_image[]"
    class="form-control mb-3">

    <input type="text"
    name="color_name[]"
    class="form-control mb-2"
    placeholder="">

    <input type="file"
    name="color_image[]"
    class="form-control mb-3">

    <h4 class="mt-4">Product Sizes</h4>

    <div class="row mb-2">
        <div class="col-md-3">
            <input type="text" name="size[]" value="" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="number" name="size_price[]" placeholder="Price" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="number" name="size_stock[]" placeholder="Stock" class="form-control">
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-3">
            <input type="text" name="size[]" value="" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="number" name="size_price[]" placeholder="Price" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="number" name="size_stock[]" placeholder="Stock" class="form-control">
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-3">
            <input type="text" name="size[]" value="" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="number" name="size_price[]" placeholder="Price" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="number" name="size_stock[]" placeholder="Stock" class="form-control">
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-3">
            <input type="text" name="size[]" value="" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="number" name="size_price[]" placeholder="Price" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="number" name="size_stock[]" placeholder="Stock" class="form-control">
        </div>
    </div>


    <div class="col-12 mb-3">

    <div class="form-check">

    <input
    type="checkbox"
    name="best_seller"
    class="form-check-input"
    id="best">

    <label class="form-check-label" for="best">
    Best Seller Product
    </label>

    </div>

    </div>

    </div>

    <button
    type="submit"
    name="add_product"
    class="btn btn-success">
    Save Product
    </button>


    </form>

    </div>

    </body>
    </html>