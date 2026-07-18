<?php
session_start();
include "db.php";

/* LOGIN CHECK */
if(!isset($_SESSION['user_id'])){
  header("Location: auth.php");
  exit;
}

$user_id = $_SESSION['user_id'];

/* QUERY */
$sql = "SELECT products.* 
        FROM wishlist 
        JOIN products 
        ON wishlist.product_id = products.id
        WHERE wishlist.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>

<title>My Wishlist</title>

<meta name="viewport"
content="width=device-width,
initial-scale=1,
maximum-scale=1,
user-scalable=no">

<style>

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
}

html,body{
  width:100%;
  overflow-x:hidden;
}

body{
    background:#f5f5f5;
    font-family:Arial,sans-serif;
    padding-top:90px;
}

/* TITLE */

h2{
  margin:10px 5px 20px;
  font-size:28px;
  font-weight:bold;
}

/* GRID */

.product-grid{
  display:grid;
  grid-template-columns:
  repeat(auto-fill,minmax(240px,1fr));
  gap:20px;
  width:100%;
}

/* CARD */

.card{
  background:#fff;
  border-radius:16px;
  overflow:hidden;
  box-shadow:0 4px 15px rgba(0,0,0,.08);
  width:100%;
  display:flex;
  flex-direction:column;
}

/* IMAGE */

.img-box{
  position:relative;
  width:100%;
  height:320px;
  overflow:hidden;
  background:#fff;
}

.img-box a{
  width:100%;
  height:100%;
  display:block;
}

.img-box img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}

/* OUT STOCK */

.out-stock{
  position:absolute;
  bottom:0;
  left:0;
  width:100%;
  background:rgba(255,255,255,.9);
  text-align:center;
  color:red;
  font-weight:bold;
  padding:8px;
  font-size:14px;
}

/* DETAILS */

.details{
  padding:15px;
  flex:1;
  display:flex;
  flex-direction:column;
  justify-content:space-between;
}

.details h4{
  font-size:17px;
  line-height:22px;
  margin-bottom:10px;
  color:#222;
}

.price{
  font-size:22px;
  font-weight:bold;
  margin-bottom:15px;
}

/* BUTTONS */

.btn-row{
  display:flex;
  gap:10px;
}

.btn{
  flex:1;
  border:none;
  border-radius:10px;
  padding:12px;
  text-align:center;
  text-decoration:none;
  font-size:14px;
  font-weight:bold;
  cursor:pointer;
}

.remove{
  background:#fff;
  border:1px solid #ddd;
  color:#000;
}

.cart{
  background:#000;
  color:#fff;
}

/* POPUP */

.size-popup{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.5);
  display:none;
  align-items:flex-end;
  justify-content:center;
  z-index:9999;
}

.popup-box{
  width:100%;
  max-width:500px;
  background:#fff;
  border-radius:25px 25px 0 0;
  padding:25px;
}

.popup-title{
  font-size:26px;
  font-weight:bold;
  margin-bottom:25px;
}

.size-list{
  display:flex;
  gap:12px;
  flex-wrap:wrap;
  margin-bottom:30px;
}

.size-btn{
  min-width:60px;
  height:55px;
  border:1px solid #ddd;
  background:#fff;
  border-radius:10px;
  font-size:17px;
  font-weight:bold;
  cursor:pointer;
}

.size-btn.active{
  background:#000;
  color:#fff;
}

.popup-btns{
  display:flex;
  gap:15px;
}

.cancel-btn,
.add-btn{
  flex:1;
  height:55px;
  border:none;
  border-radius:12px;
  font-size:18px;
  font-weight:bold;
  cursor:pointer;
}

.cancel-btn{
  background:#fff;
  border:1px solid #ccc;
}

.add-btn{
  background:#000;
  color:#fff;
}

.close-popup{
  float:right;
  font-size:28px;
  cursor:pointer;
}

/* EMPTY */

.empty{
  text-align:center;
  margin-top:120px;
}

.empty h3{
  font-size:28px;
  margin-bottom:10px;
}

/* MOBILE */

@media(max-width:768px){

  body{
    padding:10px;
  }

  h2{
    font-size:24px;
  }

  .product-grid{
    grid-template-columns:
    repeat(2,minmax(0,1fr));
    gap:10px;
  }

  .img-box{
    height:260px;
  }

  .details{
    padding:8px;
  }

  .details h4{
    font-size:13px;
    line-height:18px;
    height:36px;
    overflow:hidden;
  }

  .price{
    font-size:18px;
    margin-bottom:10px;
  }

  .btn{
    padding:9px;
    font-size:11px;
  }

  .popup-box{
    padding:20px;
  }

  .popup-title{
    font-size:22px;
  }

  .size-btn{
    min-width:52px;
    height:50px;
    font-size:15px;
  }

}

</style>
</head>

<body>
    <?php include 'header.php'; ?>

<h2>Wishlist ❤️</h2>

<?php if($result->num_rows > 0){ ?>

<div class="product-grid">

<?php while($row = $result->fetch_assoc()){ ?>

<?php
$stock = !empty($row['stock'])
? (int)$row['stock']
: 999;

/* DB SIZE FETCH */

$sizes = [];

$size_query = mysqli_query(
$conn,
"SELECT size 
FROM product_sizes 
WHERE product_id='".$row['id']."'"
);

while($size_row = mysqli_fetch_assoc($size_query)){

   $sizes[] = $size_row['size'];

}

$size_string = implode(",",$sizes);
?>

<div class="card">

  <!-- IMAGE -->

  <div class="img-box">

    <a href="product-detail.php?id=<?= $row['id'] ?>">

      <img src="<?=
      strpos($row['image'],'uploads/') !== false
      ? $row['image']
      : 'uploads/'.$row['image']
      ?>">

    </a>

    <?php if($stock <= 0){ ?>

    <div class="out-stock">
      Out of Stock
    </div>

    <?php } ?>

  </div>

  <!-- DETAILS -->

  <div class="details">

    <div>

      <h4>
        <?= htmlspecialchars($row['name']) ?>
      </h4>

      <div class="price">
        ₹<?= $row['price'] ?>
      </div>

    </div>

    <div class="btn-row">

      <!-- REMOVE -->

      <a href="remove-wishlist.php?id=<?= $row['id'] ?>"
      class="btn remove">

      Remove

      </a>

      <!-- STOCK -->

      <?php if($stock > 0){ ?>

      <button
class="btn cart"
onclick="openPopup(
'<?= $row['id'] ?>',
'<?= $size_string ?>',
'<?= htmlspecialchars($row['name'],ENT_QUOTES) ?>',
'<?= $row['price'] ?>',
'<?= strpos($row['image'],'uploads/') !== false ? $row['image'] : 'uploads/'.$row['image'] ?>'
)">

Add to Bag

</button>
      <?php } else { ?>

      <a href="category.php?cat=<?= urlencode($row['category']) ?>"
      class="btn cart">

      Show Similar

      </a>

      <?php } ?>

    </div>

  </div>

</div>

<?php } ?>

</div>

<?php } else { ?>

<div class="empty">

<h3>Your Wishlist is Empty ❤️</h3>

<p>Add products you like</p>

</div>

<?php } ?>


<!-- SIZE POPUP -->

<div class="size-popup" id="sizePopup">

  <div class="popup-box">

    <span class="close-popup"
    onclick="closePopup()">✕</span>

    <div class="popup-title">

      Select Size

    </div>

    <div class="size-list"
    id="sizeList">

    </div>

    <div class="popup-btns">

      <button class="cancel-btn"
      onclick="closePopup()">

      Cancel

      </button>

      <button class="add-btn"
      onclick="addToCart()">

      Add to Bag

      </button>

    </div>

  </div>

</div>

<script>

let selectedProduct = "";
let selectedSize = "";
let productName = "";
let productPrice = "";
let productImage = "";

function openPopup(id,sizes,name,price,image){

  selectedProduct = id;
  productName = name;
  productPrice = price;
  productImage = image;

  document
  .getElementById("sizePopup")
  .style.display = "flex";

  let sizeArray = sizes.split(",");

  let html = "";

  sizeArray.forEach(size=>{

    if(size.trim()!=""){

      html += `
      <button
      type="button"
      class="size-btn"
      onclick="selectSize(this,'${size.trim()}')">

      ${size.trim()}

      </button>
      `;

    }

  });

  document
  .getElementById("sizeList")
  .innerHTML = html;
}

function closePopup(){

  document
  .getElementById("sizePopup")
  .style.display = "none";

  selectedSize = "";
}

function selectSize(btn,size){

  document
  .querySelectorAll(".size-btn")
  .forEach(button=>{
    button.classList.remove("active");
  });

  btn.classList.add("active");

  selectedSize = size;
}

function addToCart(){

  if(selectedSize==""){

    alert("Please Select Size");
    return;
  }

  let form = document.createElement("form");

  form.method = "POST";
  form.action = "add-to-cart.php";

  form.innerHTML = `

  <input type="hidden" name="id" value="${selectedProduct}">

  <input type="hidden" name="name" value="${productName}">

  <input type="hidden" name="price" value="${productPrice}">

  <input type="hidden" name="size" value="${selectedSize}">

  <input type="hidden" name="qty" value="1">

 <input type="hidden" name="color_name" value="Wishlist">

  <input type="hidden" name="color_image" value="${productImage}">

  `;

  document.body.appendChild(form);

  form.submit();
}

</script>
  <?php include 'footer.php'; ?>
</body>
</html>