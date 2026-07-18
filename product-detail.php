    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();
    require_once "db.php";

    if(!isset($conn)){
        die("Database not connected");
    }

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if($id <= 0){ die("Invalid Product"); }

    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    if(!$product){ die("Product Not Found"); }

    $sizes = [];
    $stmt2 = $conn->prepare("SELECT * FROM product_sizes WHERE product_id=?");
    $stmt2->bind_param("i",$id);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    while($row = $res2->fetch_assoc()){ $sizes[] = $row; }

    $currentCategory = trim($product['category']);
$currentGender   = trim($product['gender']);

    $relatedStmt = $conn->prepare("
SELECT *
FROM products
WHERE category = ?
AND gender = ?
AND id != ?
ORDER BY RAND()
LIMIT 10
");

$relatedStmt->bind_param(
    "ssi",
    $currentCategory,
    $currentGender,
    $id
);

$relatedStmt->execute();
$related = $relatedStmt->get_result();


    /* FETCH REVIEWS */
    $reviewStmt = $conn->prepare("SELECT * FROM reviews WHERE product_id=? ORDER BY id DESC");
    $reviewStmt->bind_param("i",$id);
    $reviewStmt->execute();
    $reviews = $reviewStmt->get_result();

    /* AVG RATING */
    $avgResult = $conn->query("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviews WHERE product_id=$id");
    $avgData = $avgResult->fetch_assoc();

    $avgRating = $avgData['avg_rating'] !== null
        ? round($avgData['avg_rating'], 1)
        : 0;

    $totalReviews = (int)($avgData['total'] ?? 0);

    ?>

    <!DOCTYPE html>
    <html>
    <head>
    <title><?= htmlspecialchars($product['name']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>


    body{
    margin:0;
    background:#f1f3f6;
    font-family:Arial;
    animation:pageFade .6s ease-in-out;
       padding-top:90px;
   
}

    @keyframes pageFade{
    from{opacity:0;}
    to{opacity:1;}
    }

    .container{
    max-width:1200px;
    margin:30px auto;
    background:#fff;
    padding:30px;
    display:flex;
    gap:40px;
    border-radius:10px;
    box-shadow:0 5px 20px rgba(0,0,0,.08)
    }

    .left{position:relative;text-align:center}

    .main-img{
    width:380px;
    transform:none !important;
    transition:none !important;
    }

    .lens{
    position:absolute;
    border:1px solid #ccc;
    width:120px;
    height:120px;
    display:none;
    background-repeat:no-repeat;
    }

    .colors img.active{
    border:2px solid #2874f0;
    }

    .right{
    flex:1;
    display:flex;
    flex-direction:column;
    }

    .price{
    font-size:26px;
    color:#e63946;
    margin:10px;
    }

    .stock{
    margin:20px 0;
    font-weight:bold;
    }

    .size span{
    border:1px solid #ccc;
    padding:8px 14px;
    margin:5px;
    cursor:pointer;
    border-radius:6px;
    transition:.3s;
    }

    /* FIXED */
    .size span:hover{
    background:#eee;
    }

    .size span.active{
    background:#2874f0;
    color:#fff;
    }

    .size{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    }

    .qty-box{
    display:flex;
    align-items:center;
    gap:10px;
    margin:15px 0;
    }

    .qty-box button{
    width:35px;
    height:35px;
    background:#2874f0;
    color:#fff;
    border:none;
    border-radius:5px;
    cursor:pointer;
    transition:.3s;
    }

    /* FIXED */
    .qty-box button:hover{
    background:#1a5ed9;
    }

    .qty-box input{
    width:50px;
    text-align:center;
    }

    button.main-btn{
    width:100%;
    padding:12px;
    margin-top:10px;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
    transition:all .3s ease;
    }

    /* FIXED */
    button.main-btn:hover{
    opacity:0.9;
    box-shadow:0 8px 20px rgba(0,0,0,.15);
    }

    .cart{background:#2874f0;color:#fff}
    .buy{background:#fb641b;color:#fff}

    .popup{
    position:fixed;
    top:20px;
    right:-300px;
    background:#28a745;
    color:#fff;
    padding:15px 25px;
    border-radius:6px;
    transition:.5s;
    }

    .popup.show{right:20px}

    .cart-count{
    position:fixed;
    top:20px;
    left:20px;
    background:red;
    color:#fff;
    width:30px;
    height:30px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    font-weight:bold;
    animation:bounce 1s infinite alternate;
    }

    /* FIXED */
    @keyframes bounce{
    from{opacity:1;}
    to{opacity:0.8;}
    }
/* ==========================
   RELATED PRODUCTS
========================== */

.related{
    width:100%;
    margin:40px 0;
    padding:0 20px;
}

.related h2{
    font-size:42px;
    font-weight:700;
    color:#111;
    margin-bottom:25px;
}

/* Slider */

.slider{
    display:flex;
    gap:20px;
    overflow-x:auto;
    overflow-y:hidden;
    scroll-behavior:smooth;
    padding:10px 0;
    margin:0;
    width:100%;
}

.slider::-webkit-scrollbar{
    height:8px;
}

.slider::-webkit-scrollbar-track{
    background:#e5e5e5;
    border-radius:20px;
}

.slider::-webkit-scrollbar-thumb{
    background:#999;
    border-radius:20px;
}

.slider::-webkit-scrollbar-thumb:hover{
    background:#666;
}

/* Card */

.card{
    min-width:260px;
    max-width:260px;
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    flex-shrink:0;
    box-shadow:0 4px 15px rgba(0,0,0,.08);
    transition:.3s;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 12px 25px rgba(0,0,0,.15);
}

.card a{
    text-decoration:none;
    color:#111;
    display:block;
}

/* Image */
.card img{
    width:100%;
    height:220px;
    object-fit:contain;
    background:#f8f8f8;
}
/* Name */

.card .name{
    padding:12px 15px 5px;
    font-size:18px;
    font-weight:600;
    line-height:1.4;
    min-height:55px;
}

/* Price */

.card .price{
    padding:0 15px 15px;
    font-size:28px;
    font-weight:700;
    color:#ff3f6c;
}

/* Mobile */

@media(max-width:768px){

.related{
    padding:0 10px;
}

.related h2{
    font-size:28px;
}

.card{
    min-width:180px;
    max-width:180px;
}

.card img{
    height:200px;
}

.card .name{
    font-size:14px;
    min-height:45px;
}

.card .price{
    font-size:20px;
}
}
    /* FIXED */
    .card:hover{
    box-shadow:0 10px 25px rgba(0,0,0,.15);
    }
.card img{
    width:100%;
    height:220px;
    object-fit:contain;
    background:#f8f8f8;
}
    /* REVIEW */
    .review-section{margin-top:50px}

    .stars{
    color:#ffc107;
    font-size:22px;
    animation:starGlow 2s infinite alternate;
    }

    @keyframes starGlow{
    from{text-shadow:0 0 5px #ffc107;}
    to{text-shadow:0 0 15px #ff9800;}
    }

    .review-card{
    background:#fff;
    padding:15px;
    margin-top:15px;
    border-radius:8px;
    box-shadow:0 3px 10px rgba(0,0,0,.05);
    animation:slideUp .6s ease;
    }

    /* FIXED */
    @keyframes slideUp{
    from{opacity:0;}
    to{opacity:1;}
    }

    .review-form input,
    .review-form textarea,
    .review-form select{
    width:100%;
    padding:8px;
    margin-top:8px;
    border:1px solid #ccc;
    border-radius:6px;
    }

    .review-form input:focus,
    .review-form textarea:focus,
    .review-form select:focus{
    outline:none;
    border-color:#2874f0;
    box-shadow:0 0 8px rgba(40,116,240,.3);
    }

    .review-form button{
    margin-top:10px;
    background:#2874f0;
    color:#fff;
    padding:10px;
    border:none;
    border-radius:6px;
    }

    @media(max-width:768px){
    .colors img:hover{
    border:2px solid #2874f0;
    }
    }

    .colors{
    display:flex !important;
    flex-direction:row !important;
    gap:20px;
    align-items:center;
    margin-bottom:25px; /* Select Size se gap */
}

    .color-box{
    display:flex;
    flex-direction:column;
    align-items:center;
    }

    .colors img{
    width:70px;
    cursor:pointer;
    border-radius:8px;
    border:2px solid transparent;
    transition:.3s;
    }

    .colors img.active{
    border:2px solid #2874f0;
    }

    .color-name{
    margin-top:5px;
    font-size:14px;
    }

    
    @media(max-width:768px){
      .container{
        flex-direction:column;
      }

      .main-img{
        width:100%;
      }
    }
        /* CATEGORY BOX EFFECT */
    .cat-box{
      transition:0.3s;
      border-radius:12px;
    }

    /* hover effect (NO ZOOM) */
    .cat-box:hover{
      box-shadow:0 10px 25px rgba(0,0,0,0.15);
      transform:translateY(-3px);
    }

    /* image shadow */
    .cat-box img{
      box-shadow:0 4px 10px rgba(0,0,0,0.1);
      transition:0.3s;
    }

    /* hover image shadow */
    .cat-box:hover img{
      box-shadow:0 10px 25px rgba(0,0,0,0.2);
    }

    /* text */
    .cat-box span{
      font-weight:500;
    }
        /* PRODUCT CARD EFFECT */
    .product{
      transition:0.3s;
      border-radius:12px;
    }

    .product:hover{
      box-shadow:0 12px 30px rgba(0,0,0,0.15);
    }
.breadcrumb{
    max-width:1200px;
    margin:20px auto 0;
    padding:0 10px;
    font-size:17px;
    color:#777;
}

.breadcrumb a{
    text-decoration:none;
    color:#555;
    font-weight:500;
}

.breadcrumb a:hover{
    color:#2874f0;
}

.breadcrumb span{
    color:#111;
    font-weight:600;
}
    </style>
    </head>

    <body>
<?php include 'header.php'; ?>


    <div class="cart-count" id="cartCount">
    <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
    </div>

    <?php
$gender   = strtolower($product['gender']);
$category = strtolower($product['category']);
?>

<div class="breadcrumb">

<a href="index.php">HOME</a>

<?php if($gender=='men'){ ?>
    > <a href="men-products.php?cat=<?=$category?>">
        MEN
      </a>
<?php } else { ?>
    > <a href="women-products.php?cat=<?=$category?>">
        WOMEN
      </a>
<?php } ?>

> <span><?= strtoupper($category) ?></span>

</div>
    <div class="container">

    <div class="left">
    <img src="uploads/<?= htmlspecialchars(trim($product['image'])) ?>" 
         class="main-img" 
         id="mainImage">
    <div class="lens" id="lens"></div>
    </div>

    <div class="right">
    <h2><?= htmlspecialchars($product['name']) ?></h2>
    <div class="price" id="priceDisplay">₹<?= (int)$product['price'] ?></div>
    <div class="stock" id="stockDisplay"></div>

    <form method="post" id="productForm">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="hidden" name="name" value="<?= htmlspecialchars($product['name']) ?>">
    <input type="hidden" name="price" id="finalPrice" value="<?= (int)$product['price'] ?>">
    <input type="hidden" name="size" id="size">
    <input type="hidden" name="color_name" id="selectedColor">
    <input type="hidden" name="color_image" id="selectedImage">

    <input type="hidden" name="qty" id="hiddenQty" value="1">

    <p><b>Select Color</b></p>

    <?php
    $colorStmt = $conn->prepare("SELECT * FROM product_colors WHERE product_id=?");
    $colorStmt->bind_param("i",$id);
    $colorStmt->execute();
    $colorRes = $colorStmt->get_result();
    ?>

    <div class="colors">
    <?php while($c = $colorRes->fetch_assoc()): ?>
        <div class="color-box">
           <img src="uploads/<?= htmlspecialchars(trim($c['color_image'])) ?>"
         data-color="<?= htmlspecialchars($c['color_name']) ?>"
         data-price="<?= (int)$product['price'] ?>"
         onclick="selectColor(this,'<?= htmlspecialchars(trim($c['color_image'])) ?>')">

            <div class="color-name">
                <?= htmlspecialchars($c['color_name']) ?>
            </div>
        </div>
    <?php endwhile; ?>
    </div>


    <p><b>Select Size</b></p>
    <div class="size">
    <?php foreach($sizes as $s): ?>
    <span onclick="selectSize(this,'<?= $s['size'] ?>',<?= (int)$s['price'] ?>,<?= (int)$s['stock'] ?>)">
    <?= htmlspecialchars($s['size']) ?>
    </span>
    <?php endforeach; ?>
    </div>

    <div class="qty-box">
    <button type="button" onclick="changeQty(-1)">-</button>
    <input type="text" id="qty" value="1" readonly>
    <button type="button" onclick="changeQty(1)">+</button>
    </div>

    <button type="button" class="main-btn cart" onclick="addToCart()">ADD TO CART</button>
    <button type="button" class="main-btn buy" onclick="buyNow()">BUY NOW</button>

    </form>
    </div>

    </div>
    <!-- Review Section -->
    <div class="review-section">
    <h2>Ratings & Reviews</h2>

    <div class="stars">
    <?php for($i=1;$i<=5;$i++){
    echo $i <= round($avgRating) ? "★" : "☆";
    } ?>
    <span style="font-size:16px;color:#333">
    (<?= $avgRating ?> / 5 | <?= $totalReviews ?> Reviews)
    </span>
    </div>

    <!-- REVIEW FORM -->
    <form method="post" action="submit-review.php" class="review-form">
    <input type="hidden" name="product_id" value="<?= $id ?>">

    <input type="text" name="name" placeholder="Your Name" required>

    <select name="rating" required>
    <option value="">Select Rating</option>
    <option value="5">★★★★★</option>
    <option value="4">★★★★</option>
    <option value="3">★★★</option>
    <option value="2">★★</option>
    <option value="1">★</option>
    </select>

    <textarea name="comment" placeholder="Write your review..." required></textarea>

    <button type="submit">Submit Review</button>
    </form>



    <?php while($r = $reviews->fetch_assoc()): ?>
    <div class="review-card">
    <strong><?= htmlspecialchars($r['name']) ?></strong>
    <div style="color:#ffc107">
    <?php for($i=1;$i<=5;$i++){
    echo $i <= $r['rating'] ? "★" : "☆";
    } ?>
    </div>
    <p><?= htmlspecialchars($r['comment']) ?></p>
    <small><?= $r['created_at'] ?></small>
    </div>
    <?php endwhile; ?>
    </div>

    <!-- Related -->
    <div class="related">

    <h2>Related Products</h2>

    <div class="slider">

        <?php while($r = $related->fetch_assoc()): ?>

        <div class="card">
            <a href="product-detail.php?id=<?= $r['id'] ?>">

                <img src="uploads/<?= $r['image'] ?>" alt="">

                <div class="name">
                    <?= htmlspecialchars($r['name']) ?>
                </div>

                <div class="price">
                    ₹<?= $r['price'] ?>
                </div>

            </a>
        </div>

        <?php endwhile; ?>

    </div>

</div>
    

    <div class="popup" id="popup">Added to Cart ✔</div>

    <script>

    function changeQty(v){
    let qty=document.getElementById("qty");
    let hidden=document.getElementById("hiddenQty");
    let val=parseInt(qty.value);
    if(val+v>=1){
    qty.value=val+v;
    hidden.value=val+v;
    }
    }

    function selectSize(el,size,price,stock){
    document.querySelectorAll(".size span").forEach(s=>s.classList.remove("active"));
    el.classList.add("active");
    document.getElementById("size").value=size;
    document.getElementById("finalPrice").value=price;
    document.getElementById("priceDisplay").innerHTML="₹"+price;
    document.getElementById("stockDisplay").innerHTML=
    stock<=5
    ? "<span style='color:red'>Only "+stock+" left!</span>"
    : "<span style='color:green'>"+stock+" available</span>";
    }

    function selectColor(el,img){

    document.querySelectorAll(".colors img").forEach(i=>i.classList.remove("active"));
    el.classList.add("active");

    let colorName  = el.getAttribute("data-color");
    let colorPrice = el.getAttribute("data-price");

    document.getElementById("mainImage").src="uploads/"+img;
    document.getElementById("selectedColor").value = colorName;
    document.getElementById("selectedImage").value = img;

    if(colorPrice){
    document.getElementById("priceDisplay").innerHTML="₹"+colorPrice;
    document.getElementById("finalPrice").value = colorPrice;
    }
    }

    function addToCart(){

    if(!document.getElementById("size").value){
    alert("Select Size");
    return;
    }

    if(!document.getElementById("selectedColor").value){
    alert("Select Color");
    return;
    }

    document.getElementById("productForm").action="add-to-cart.php";
    document.getElementById("productForm").submit();
    }

    function buyNow(){

    if(!document.getElementById("size").value){
    alert("Select Size");
    return;
    }

    if(!document.getElementById("selectedColor").value){
    alert("Select Color");
    return;
    }

    document.getElementById("productForm").action="add-to-cart.php";
    document.getElementById("productForm").submit();
    }



    /* AUTO SELECT FIRST COLOR + SIZE */
    window.onload=function(){

    let firstColor=document.querySelector(".colors img");
    if(firstColor){ firstColor.click(); }

    let firstSize=document.querySelector(".size span");
    if(firstSize){ firstSize.click(); }

    }

    </script>
    <?php include 'footer.php'; ?>
    </body>
    </html>