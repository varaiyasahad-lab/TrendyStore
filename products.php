<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "db.php";

$id = $_GET['id'] ?? 0;

if(!$id){
  die("❌ No Product ID");
}

/* PRODUCT */
$res = $conn->query("SELECT * FROM products WHERE id=$id");
if(!$res || $res->num_rows == 0){
  die("❌ Product not found");
}
$product = $res->fetch_assoc();

/* COLORS + IMAGES */
$images = $conn->query("SELECT * FROM product_colors WHERE product_id=$id");

/* SIZES */
$sizes = $conn->query("SELECT size FROM product_sizes WHERE product_id=$id");

/* COLORS DROPDOWN */
$colorData = $conn->query("SELECT DISTINCT color_name FROM product_colors WHERE product_id=$id");
?>
<?php
/* ADD TO CART */
if(isset($_POST['cart'])){

    $image = $product['image'];
    $color = strtolower($_POST['color']);

    $res = $conn->query("
      SELECT color_image 
      FROM product_colors 
      WHERE product_id=".$product['id']." 
      AND LOWER(color_name)='$color'
      LIMIT 1
    ");

    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();
        $image = $row['color_image'];
    }

    /* 🔥 SMART CART (duplicate fix) */
    $found = false;

    if(!empty($_SESSION['cart'])){
      foreach($_SESSION['cart'] as &$item){
        if($item['id'] == $product['id'] &&
           $item['size'] == $_POST['size'] &&
           strtolower($item['color']) == strtolower($_POST['color'])){

            $item['qty'] += 1;
            $found = true;
            break;
        }
      }
    }

    if(!$found){
        $_SESSION['cart'][] = [
            "id"    => $product['id'],
            "name"  => $product['name'],
            "price" => $product['price'],
            "image" => $image,
            "size"  => $_POST['size'],
            "color" => $_POST['color'],
            "qty"   => 1
        ];
    }

    header("Location: view-cart.php");
    exit;
}

/* BUY NOW */
if(isset($_POST['buy'])){

    $image = $product['image'];
    $color = strtolower($_POST['color']);

    $res = $conn->query("
      SELECT color_image 
      FROM product_colors 
      WHERE product_id=".$product['id']." 
      AND LOWER(color_name)='$color'
      LIMIT 1
    ");

    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();
        $image = $row['color_image'];
    }

    $_SESSION['buy_now'] = [
        "id"    => $product['id'],
        "name"  => $product['name'],
        "price" => $product['price'],
        "image" => $image,
        "size"  => $_POST['size'],
        "color" => $_POST['color'],
        "qty"   => 1
    ];

    header("Location: checkout.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?= $product['name'] ?></title>

<style>
body{
  font-family:Arial;
  background:#f6f6f6;
  padding:0 ;
}

.product{
  background:#fff;
  padding:20px;
  border-radius:12px;
  max-width:420px;
  width:100%;  
  margin:10px auto;
  text-align:center;
}

#mainImage{
  width:250px;
  height:250px;
  object-fit:contain;
}

.thumbs{
  display:flex;
  gap:10px;
  margin-top:10px;
  justify-content:center;
}

.thumbs img{
  width:60px;
  height:60px;
  object-fit:contain;
  cursor:pointer;
  border:2px solid #ddd;
  border-radius:6px;
}

.price{
  color:#0a7d5f;
  font-size:20px;
  font-weight:bold;
}

select{
  padding:10px;
  margin-top:10px;
  width:100%;
}

.btn{
  display:block;
  margin-top:10px;
  padding:10px;
  background:#000;
  color:#fff;
  border:none;
  border-radius:20px;
  cursor:pointer;
}

.btn-buy{
  background:#e63946;
}
    @media(max-width:576px){

  body{
    padding:10px;
  }

  .product{
    max-width:100%;
    padding:15px;
  }

  #mainImage{
    width:100%;
    height:auto;
  }

  .thumbs img{
    width:90px;
    height:90px;
  }

  select{
    font-size:14px;
  }

  .btn{
    width:100%;
    padding:12px;
  }
}
    .btn-group{
  display:flex;
  gap:10px;
  margin-top:10px;
}

.btn-cart{
  flex:1;
  background:#000;
  color:#fff;
  border:none;
  border-radius:25px;
  padding:12px;
  cursor:pointer;
}

.btn-buy{
  flex:1;
  background:#e63946;
  color:#fff;
  border:none;
  border-radius:25px;
  padding:12px;
  cursor:pointer;
}
    .btn-group{
  flex-direction:row;
}

.btn-cart, .btn-buy{
  width:50%;
  font-size:14px;
  padding:10px;
}
</style>
    
</head>

<body>

<div class="product">

  <!-- MAIN IMAGE -->
  <img id="mainImage" src="uploads/<?= $product['image'] ?>">

  <!-- THUMBS -->
  <div class="thumbs">
  <?php 
  if($images){ 
    while($img = $images->fetch_assoc()){ ?>
    
    <img 
      src="uploads/<?= $img['color_image'] ?>" 
      data-color="<?= strtolower($img['color_name']) ?>"
      onclick="changeImage(this.src)">
  
  <?php }} ?>
  </div>

  <h2><?= $product['name'] ?></h2>
  <p class="price">₹<?= $product['price'] ?></p>

  <form method="post">

    <!-- SIZE -->
    <select name="size" required>
      <option value="">Select Size</option>
      <?php while($s = $sizes->fetch_assoc()){ ?>
        <option value="<?= $s['size'] ?>">
          <?= strtoupper($s['size']) ?>
        </option>
      <?php } ?>
    </select>

    <!-- COLOR -->
    <select name="color" id="colorSelect" onchange="filterColor()" required>
      <option value="">Select Color</option>

      <?php while($c = $colorData->fetch_assoc()){ ?>
        <option value="<?= strtolower($c['color_name']) ?>">
          <?= ucfirst($c['color_name']) ?>
        </option>
      <?php } ?>
    </select>

    <input type="hidden" name="id" value="<?= $product['id'] ?>">
    <input type="hidden" name="qty" value="1">

    <div class="btn-group">
  <button name="cart" class="btn btn-cart">Add to Cart</button>
  <button name="buy" class="btn btn-buy">Buy Now</button>
</div>
  </form>

  <p><?= $product['description'] ?></p>

</div>

<script>
function changeImage(src){
  document.getElementById("mainImage").src = src;
}

function filterColor(){
  let selected = document.getElementById("colorSelect").value;
  let thumbs = document.querySelectorAll(".thumbs img");

  let firstMatch = null;

  thumbs.forEach(img => {

    if(selected === "" || img.dataset.color === selected){
      img.style.display = "block";

      if(!firstMatch){
        firstMatch = img.src;
      }

    } else {
      img.style.display = "none";
    }

  });

  if(firstMatch){
    document.getElementById("mainImage").src = firstMatch;
  }
}

window.onload = function(){
  let thumbs = document.querySelectorAll(".thumbs img");
  if(thumbs.length > 0){
    document.getElementById("mainImage").src = thumbs[0].src;
  }
}
</script>

</body>
</html>

