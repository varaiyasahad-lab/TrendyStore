<?php
session_start();
/* DB CONNECT */
include "db.php";

/* VISITOR TRACKER */
include "visitor-tracker.php";

/* CART COUNT */
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $c) {
    if (isset($c['qty'])) {
      $cartCount += (int)$c['qty'];
    }
  }
}


/* CATEGORY FETCH */
$menCats = $conn->query("SELECT DISTINCT category FROM products WHERE gender='men' ORDER BY FIELD(category,'tshirts','shirts','jeans','trackpants','nightwear','hoodies','pants')");

$womenCats = $conn->query("SELECT DISTINCT category FROM products WHERE gender='women' ORDER BY FIELD(category,'tshirts','shirts','dresses','jeans','trackpants','nightwear','hoodies')");

/* IMAGE MAP */
$imgMap = [

 

  // MEN
  "men_tshirts" => "tshirts (7).png",
  "men_shirts" => "shirts3.png",
  "men_jeans" => "jeans (4).png",
  "men_trackpants" => "trackpants.png",
  "men_nightwear" => "nightwear1.png",
  "men_hoodies" => "hoodies1.png",
  "men_pants" => "men pants.png",
   "men_polos" => "polos.png",

  // WOMEN
  "women_tshirts" => "women tshirts.png",
  "women_shirts" => "women shirts.png",
  "women_jeans" => "women jeans.png",
  "women_trackpants" => "women t & p.png",
  "women_nightwear" => "nightwear (5).png",
  "women_hoodies" => "women hoodies.png",
  "women_dresses" => "dresses (4).png",
  "women_tops" => "tops (5).png",
];
$allowedMen = ["tshirts","shirts","jeans","trackpants","nightwear","hoodies","pants","polos"];

$allowedWomen = ["tshirts","shirts","dresses","jeans","trackpants","nightwear","hoodies","tops"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Trendy Store</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{margin:0;font-family:Arial,Helvetica,sans-serif;}
body{
  margin:0;
  padding:0;
  overflow-x:hidden; 
    /* 🔥 side white space हटेगा */
}
    *{
  box-sizing:border-box;
  max-width:100%;
}
/* NAVBAR */
.navbar{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:15px 40px;
  border-bottom:1px solid #eee;
  background:#fff;
    position:sticky;
  top:0;
  z-index:99999;
    
}
.nav-left{display:flex;align-items:center;gap:10px;}
.nav-logo{height:60px;}
.store-name{font-size:28px;font-weight:900;}

.nav-menu{
  display:flex;
  gap:30px;
  list-style:none;
  margin:0;
  padding:0;
}
.nav-menu li a{text-decoration:none;color:#000;font-weight:600;}
.nav-menu li a:hover{color:#e63946;}

.nav-icons{
  display:flex;
  align-items:center;
  gap:20px;
}
.nav-icons a{text-decoration:none;color:#000;font-weight:600;}
.nav-icons a:hover{color:#e63946;}
   @media(max-width:768px){

body{
  margin:0;
  padding:0;
  overflow-x:hidden;
}

/* NAVBAR */
.navbar{
  padding:8px 12px;
  width:100%;
  display:flex;
  flex-wrap:wrap;
  gap:6px;
  align-items:center;
    position:sticky;
  top:0;
  z-index:99999;
  background:#fff;
}

/* LOGO */
.nav-logo{
  height:48px;
}

.store-name{
  font-size:22px;
  font-weight:800;
}

/* MENU */
.nav-menu{
  width:100%;
  justify-content:space-around;
  gap:0;
  margin:0;
  padding:0;
}

.nav-menu li a{
  font-size:14px;
}

/* SEARCH */
.search-box{
  width:100% !important;
  margin-top:0 !important;
}

.search-box input{
  width:100% !important;
  font-size:14px;
}

/* LOGIN CART */
.nav-icons{
  width:100%;
  justify-content:space-between;
  margin-top:0;
}

/* SLIDER */
.hero-slider{
  height:250px !important;
  margin-top:5px !important;
}

.hero-slide{
  height:250px !important;
  background-size:cover !important;
  background-position:center;
}

/* REMOVE EXTRA SPACE */
.carousel,
.carousel-inner,
.carousel-item{
  margin:0 !important;
  padding:0 !important;
}

}

/* 🔍 SEARCH BAR */
.search-box{
  display:flex;
  align-items:center;
  border:1px solid #ddd;
  border-radius:30px;
  padding:5px 12px;
  position:relative;
  transition:.3s ease;
}
.search-box:focus-within{
  transform:scale(1.05);
  box-shadow:0 0 0 3px rgba(0,0,0,0.1);
}
.search-box input{
  border:none;
  outline:none;
  padding:6px 10px;
  width:180px;
}
.search-box button{
  border:none;
  background:transparent;
  cursor:pointer;
  font-size:18px;
}

/* AUTOCOMPLETE */
#suggestions{
  position:absolute;
  top:110%;
  left:0;
  width:100%;
  background:#fff;
  border-radius:10px;
  box-shadow:0 10px 30px rgba(0,0,0,0.15);
  display:none;
  z-index:999;
  max-height:280px;
  overflow-y:auto;
}
#suggestions div{
  padding:10px 15px;
  cursor:pointer;
}
#suggestions div:hover{
  background:#f2f2f2;
}

@media(max-width:768px){
  .search-box{
    display:flex !important;
    width:380px;
    margin-top:8px;
  }

  .search-box input{
    width:90px;
    font-size:12px;
  }

  .navbar{
    flex-wrap:wrap;
    gap:8px;
  }
}
.search-box{
  display:flex !important;
}


/* ===== SLIDER ===== */
/* ===== SLIDER NEW CLEAN ===== */

/* slider container */
.hero-slider{
  width:100%;
  height:550px;
  overflow:hidden;
  background:#fff;
}

.hero-slide{
  width:100%;
  height:100%;
  background-size:100% 100%;
  background-repeat:no-repeat;
  background-position:center;
  background-color:#fff;
}
@media(max-width:768px){
  .hero-slide{
    background-size:contain;  
  }
}
/* mobile fix */
@media(max-width:768px){

  .hero-slider{
    height:300px;
  }

}
    @media(max-width:768px){

body{
  margin:0 !important;
  padding:0 !important;
}

.offer-bar{
  margin-bottom:0 !important;
}

.hero-slider{
  margin-top:0 !important;
  padding-top:0 !important;
  height:260px;
}

.carousel,
.carousel-inner,
.carousel-item{
  margin-top:0 !important;
  padding-top:0 !important;
}

}
    /* FIX BOOTSTRAP HEIGHT ISSUE */
.carousel-item{
  height:100%;
}

.carousel-inner{
  height:100%;
}
.carousel-control-prev-icon,
.carousel-control-next-icon{
  background-color:#000;
  border-radius:50%;
  width:55px;
  height:55px;
  background-size:60%;
}
.slide-link{display:block;width:100%;height:100%;text-decoration:none;}
.carousel-indicators{
  position:absolute;
  bottom:10px;
  margin-bottom:0;
}
.carousel-indicators button{
  width:10px;height:10px;border-radius:50%;
  background:#cfcfcf;opacity:1;border:0;
}
.carousel-indicators .active{background:#0a7d5f;}
/* =========================
TRENDING COLLECTION
========================= */

.new-collection{
  width:100%;
  padding:35px 40px;
  background:#fff;
  box-sizing:border-box;
}

.new-collection h2{
  font-size:32px;
  font-weight:900;
  margin-bottom:25px;
  color:#111;
}

/* GRID */

.new-grid{
  display:flex;
  flex-wrap:wrap;
  justify-content:flex-start;
  gap:60px;
  width:100%;
}

/* CARD */

.new-card{
  width:220px;
  background:#fff;
  border-radius:16px;
  overflow:hidden;
  box-shadow:0 8px 20px rgba(0,0,0,0.08);
  transition:0.3s;
}

.new-card:hover{
  transform:translateY(-6px);
  box-shadow:0 12px 28px rgba(0,0,0,0.12);
}

/* IMAGE */

.new-card img{
  width:100%;
  height:300px;
  object-fit:cover;
  display:block;
}

/* =========================
BEST SELLERS CLEAN PREMIUM
========================= */

.best-sellers{
  width:100%;
  padding:28px 20px;
  background:#f5f5f5;
  border-radius:24px;
  margin-top:25px;
}

/* HEADING */

.best-sellers h2{
  font-size:42px;
  font-weight:900;
  margin-bottom:22px;
  letter-spacing:1px;
}

.best-sellers h2 a{
  text-decoration:none;
  color:#111;
  transition:.3s;
}

.best-sellers h2 a:hover{
  color:#ff4d00;
}

/* LINE */

.best-sellers h2::after{
  content:'';
  display:block;
  width:120px;
  height:5px;
  border-radius:20px;
  margin-top:6px;

  background:
  linear-gradient(90deg,#ff0000,#ff7300);
}

/* GRID */

.best-grid{
  display:grid;

  grid-template-columns:
  repeat(auto-fit,minmax(210px,1fr));

  gap:18px;
}

/* CARD */

.best-card{
  background:#fff;
  border-radius:18px;
  overflow:hidden;
  position:relative;

  transition:.3s ease;

  box-shadow:
  0 5px 18px rgba(0,0,0,0.08);
}

.best-card:hover{
  transform:translateY(-5px);

  box-shadow:
  0 12px 28px rgba(0,0,0,0.14);
}

/* IMAGE */

.best-card img{
  width:100%;
  height:210px;
  object-fit:contain;
  background:#fafafa;
  padding:12px;
  transition:.3s;
}

.best-card:hover img{
  transform:scale(1.04);
}

/* HOT TAG */

.best-card::before{
  content:'HOT';
  position:absolute;

  top:10px;
  left:10px;

  background:
  linear-gradient(90deg,#ff0000,#ff7300);

  color:#fff;

  padding:5px 12px;

  border-radius:30px;

  font-size:10px;
  font-weight:800;
  letter-spacing:1px;

  z-index:5;
}

/* TITLE */

.best-card h4{
  font-size:17px;
  font-weight:700;
  text-align:center;
  margin-top:12px;
  color:#111;
}

/* PRICE */

.best-card p{
  text-align:center;

  font-size:26px;
  font-weight:900;

  margin-top:8px;
  margin-bottom:14px;

  color:#ff3c00;
}

/* BUTTON */

.btn{
  width:80%;
  margin:0 auto 16px;

  padding:10px;

  display:block;

  border-radius:40px;

  text-align:center;

  text-decoration:none;

  background:#111;
  color:#fff;

  font-size:11px;
  font-weight:800;

  letter-spacing:1px;

  transition:.3s;
}

.best-card:hover .btn{
  background:
  linear-gradient(90deg,#ff0000,#ff7300);
}

/* MOBILE */

@media(max-width:768px){

.best-sellers{
  padding:18px 10px;
  border-radius:0;
}

.best-sellers h2{
  font-size:30px;
}

.best-grid{
  grid-template-columns:repeat(2,1fr);
  gap:12px;
}

.best-card{
  border-radius:16px;
}

.best-card img{
  height:150px;
  padding:8px;
}

.best-card h4{
  font-size:13px;
}

.best-card p{
  font-size:20px;
}

.btn{
  width:88%;
  font-size:10px;
  padding:8px;
}

}
/* =========================
MOBILE RESPONSIVE
========================= */

@media(max-width:768px){

  .new-collection,
  .best-sellers{
    padding:25px 12px;
  }

  .new-grid,
  .best-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:12px;
  }

  .new-card,
  .best-card{
    width:100%;
  }

  .new-card img{
    height:210px;
  }

  .best-card img{
    height:180px;
  }

  .best-card h4{
    font-size:15px;
  }

  .best-card p{
    font-size:18px;
  }

  .buy-btn{
    width:100%;
    padding:8px;
    font-size:12px;
  }

}
/* ===== MOBILE BOTTOM NAV ===== */
.mobile-nav{
  position:fixed;
  bottom:0;
  left:0;
  width:100%;
  height:65px;
  background:#fff;
  display:flex;
  justify-content:space-around;
  align-items:center;
  box-shadow:0 -2px 10px rgba(0,0,0,0.1);
  z-index:999;
}

.mobile-nav a{
  text-decoration:none;
  color:#444;
  text-align:center;
  font-size:12px;
  flex:1;
}

.mobile-nav span{
  display:block;
  font-size:22px;
}

.mobile-nav a:hover{
  color:#e10600;
}


 /* Mobile */
@media(max-width:768px){
  .mobile-nav{
    display:flex;
  }
}

/* Desktop + Mobile Common */
.cat-row{
  display:flex;
  flex-wrap:nowrap;
  overflow-x:auto;
  gap:14px;
  padding:12px;
  background:#fff;
  scroll-behavior:smooth;
}

/* scrollbar hide */
.cat-row::-webkit-scrollbar{
  display:none;
}

.cat-box{
  min-width:120px;
  flex:0 0 auto;
  text-align:center;
}

/* ===== CATEGORY ROW ===== */

.cat-row{
  display:flex;
  flex-wrap:wrap;
  justify-content:center;
  gap:18px;
  padding:10px;
  background:#fff;
}

/* scrollbar hide */
.cat-row::-webkit-scrollbar{
  display:none;
}

/* ===== CATEGORY CARD ===== */

.cat-box{
  width:140px;
  flex:0 0 auto;
  text-align:center;
  border-radius:16px;
  overflow:hidden;
  background:#fff;
  box-shadow:0 4px 15px rgba(0,0,0,0.08);
  transition:0.3s;
}

.cat-box:hover{
  transform:translateY(-5px);
  box-shadow:0 8px 25px rgba(0,0,0,0.12);
}

/* ===== CATEGORY IMAGE ===== */

.cat-box img{
  width:120px;
  height:120px;
  object-fit:cover;
  border-radius:12px;
  background:#f2f2f2;
}

/* ===== CATEGORY TEXT ===== */

.cat-box span{
  font-size:14px;
  font-weight:600;
  color:#333;
  display:block;
  margin-top:8px;
  margin-bottom:10px;
}

/* ===== MOBILE ===== */

@media(max-width:768px){

  .cat-row{
    flex-wrap:nowrap;
    overflow-x:auto;
    justify-content:flex-start;
    scroll-behavior:smooth;
    padding:12px;
  }

  .cat-box{
    min-width:100px;
    width:100px;
  }

  .cat-box img{
    height:100px;
    border-radius:20px;
  }

  .cat-box span{
    font-size:15px;
  }

}

/* ===== BOTTOM SPACE ===== */

body{
  padding-bottom:70px;
}

.section{
  margin-top:30px;
}

.footer{
  background:#24384d;
  color:#fff;
  padding:50px 40px 120px;
  margin-top:40px;
  width:100%;
}

.footer-top{
  display:flex;
  justify-content:space-around;
  text-align:center;
  padding-bottom:40px;
  border-bottom:1px solid rgba(255,255,255,0.15);
}

.footer-top .icon-box{
  width:250px;
}

.footer-top .icon{
  font-size:60px;
  margin-bottom:12px;
}

.footer-top h4{
  font-size:16px;
  font-weight:700;
}
body{
  margin:0;
  padding:0;
  overflow-x:hidden;
}

footer{
  width:100%;
} 
/* LINKS */

.footer-links{
  display:flex;
  justify-content:space-between;
  flex-wrap:wrap;
  gap:40px;
  margin-top:40px;
}

.footer-links h3{
  font-size:22px;
  margin-bottom:18px;
}

.footer-links a{
  display:block;
  color:#ddd;
  text-decoration:none;
  margin-bottom:10px;
  transition:.3s;
}

.footer-links a:hover{
  color:#fff;
  padding-left:5px;
}

/* PAYMENT */

.footer-bottom{
  margin-top:40px;
  padding-top:25px;
  border-top:1px solid rgba(255,255,255,0.2);

  display:flex;
  justify-content:space-between;
  flex-wrap:wrap;
  gap:20px;
}
.payment-icons{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.payment-icons img{
    width:125px;
    height:85px;
    object-fit:contain;
    background:#fff;
    padding:6px;
    border-radius:8px;
}

.security-icons img{
    width:125px;
    height:85px;
    object-fit:contain;
    background:#fff;
    padding:6px;
    border-radius:8px;
}

/* MOBILE */

@media(max-width:768px){

.footer{
  padding:35px 18px 90px;
}

.footer-top{
  flex-direction:column;
  align-items:center;
  gap:25px;
}

.footer-links{
  flex-direction:column;
}

.footer-bottom{
  flex-direction:column;
}

}

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
  <div class="nav-left">
    <img src="uploads/logo.png" class="nav-logo">
    <span class="store-name">Trendy Store</span>
  </div>

  <ul class="nav-menu">
    <li><a href="men.php">👔 MEN</a></li>
<li><a href="women.php">👗 WOMEN</a></li>
      <li><a href="trending.php">🔥 TRENDING</a></li>

  </ul>

  <!-- 🔍 ADVANCED SEARCH -->
  <form action="search.php" method="get" class="search-box" autocomplete="off">
    <input type="text" name="q" id="searchInput" placeholder="Search products...">
    <button type="submit">🔍</button>
    <div id="suggestions"></div>
  </form>
<!-- ✅ FIXED NAV-ICONS (NO DUPLICATION) -->
 <div class="nav-icons">

  <?php if(isset($_SESSION['user_id'])){ ?>
    <a href="account.php">Hi, <?php echo $_SESSION['user_name']; ?></a>
  <?php } else { ?>
    <a href="auth.php">Login</a>
  <?php } ?>

  <a href="view-cart.php">🛒 Cart (<b><?php echo $cartCount; ?></b>)</a>

</div>
    </div>
    
<!-- ===== SLIDER ===== -->
<div id="homeSlider"
     class="carousel slide hero-slider"
     data-bs-ride="carousel"
     data-bs-interval="4000"
     data-bs-pause="hover"
     data-bs-wrap="true"
     data-bs-touch="true">

  <!-- DOTS (Bootstrap controlled) -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#homeSlider" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#homeSlider" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#homeSlider" data-bs-slide-to="2"></button>
    <button type="button" data-bs-target="#homeSlider" data-bs-slide-to="3"></button>
  </div>

  <div class="carousel-inner">

    <div class="carousel-item active">
      <a href="men.php" class="slide-link">
        <div class="hero-slide" style="background-image:url('uploads/index (5).png');"></div>
      </a>
    </div>

    

    <div class="carousel-item">
      <a href="men.php" class="slide-link">
        <div class="hero-slide" style="background-image:url('uploads/slider (4).png');"></div>
      </a>
    </div>

    <div class="carousel-item">
      <a href="women.php" class="slide-link">
        <div class="hero-slide" style="background-image:url('uploads/index (4).png');"></div>
      </a>
    </div>

  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#homeSlider" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>

  <button class="carousel-control-next" type="button" data-bs-target="#homeSlider" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>

</div>
    <div class="cat-row">

  <a href="category.php?cat=all&gender=men" class="cat-box">
    <img src="uploads/extra (2).png">
    <span>Men</span>
  </a>

 <?php while($m = $menCats->fetch_assoc()){ 

  $cat = strtolower(trim($m['category']));

  if(!in_array($cat, $allowedMen)) continue;
?>

    <a href="category.php?cat=<?= $m['category'] ?>&gender=men" class="cat-box">

   <?php $key = "men_" . $cat; ?>
<img src="uploads/<?= $imgMap[$key] ?? 'default.png' ?>">
      <span><?= ucfirst($m['category']) ?></span>

    </a>

  <?php } ?>

</div>
    <div class="cat-row">

  <a href="category.php?cat=all&gender=women" class="cat-box">
    <img src="uploads/women (2).png">
    <span>Women</span>
  </a>

 <?php while($w = $womenCats->fetch_assoc()){ 

  $cat = strtolower(trim($w['category']));

  if(!in_array($cat, $allowedWomen)) continue;
?>

    <a href="category.php?cat=<?= $w['category'] ?>&gender=women" class="cat-box">

     <?php $key = "women_" . $cat; ?>
<img src="uploads/<?= $imgMap[$key] ?? 'default.png' ?>">

      <span><?= ucfirst($w['category']) ?></span>

    </a>

  <?php } ?>

</div>
    
<!-- ===== TRENDING COLLECTION ===== -->

<section class="new-collection">

<h2>TRENDING COLLECTION</h2>

<div class="new-grid">

<!-- CARD 1 -->

<a href="trending.php" class="trending-link">

<div class="new-card">

<img src="uploads/tn (2).png"
alt="Trending Product 1">

</div>

</a>

<!-- CARD 2 -->

<a href="trending.php" class="trending-link">

<div class="new-card">

<img src="uploads/tn.png"
alt="Trending Product 2">

</div>

</a>

<!-- CARD 3 -->

<a href="trending.php" class="trending-link">

<div class="new-card">

<img src="uploads/Trending.png"
alt="Trending Product 3">

</div>

</a>

<!-- CARD 5 -->

<a href="trending.php" class="trending-link">

<div class="new-card">

<img src="uploads/trending (2).png"
alt="Trending Product 5">

</div>

</a>
    
    <!-- CARD 4 -->

<a href="trending.php" class="trending-link">

<div class="new-card">

<img src="uploads/trending (3).png"
alt="Trending Product 4">

</div>

</a>

    
    
    
</div>

</section>
<!-- ===== BEST SELLERS ===== -->

<section class="best-sellers">

<h2 class="best-heading">
<a href="best-seller.php">
BEST SELLERS →
</a>
</h2>

<div class="best-grid">

<?php

$best = $conn->query("
SELECT * FROM products
WHERE best_seller = 1
ORDER BY id DESC
LIMIT 5
");

while($b = $best->fetch_assoc()){

?>

<div class="best-card">

<img src="uploads/<?php echo $b['image']; ?>">

<h4><?php echo $b['name']; ?></h4>

<p>₹<?php echo $b['price']; ?></p>

<a href="product-detail.php?id=<?php echo $b['id']; ?>" class="btn">
VIEW PRODUCT
</a>

</div>

<?php } ?>

</div>

</section>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* INPUT & SUGGESTION BOX */
const input = document.getElementById("searchInput");
const box   = document.getElementById("suggestions");

/* 🔍 TYPE KARTE HI SUGGESTION */
input.addEventListener("keyup", function(){
  let q = this.value.trim();

  if(q.length < 2){
    box.style.display = "none";
    return;
  }

  fetch("search-suggest.php?q=" + q)
    .then(res => res.text())
    .then(data => {
      if(data){
        box.innerHTML = data;
        box.style.display = "block";
      } else {
        box.style.display = "none";
      }
    });
});

/* 👉 SUGGESTION CLICK */
function pickSearch(val){
  input.value = val;
  box.style.display = "none";
  input.form.submit();   // direct search
}

/* 🧹 SEARCH KE BAAD INPUT CLEAR */
input.form.addEventListener("submit", ()=>{
  setTimeout(()=>{
    input.value = "";
    box.style.display = "none";
  },200);
});

/* ❌ BAHAR CLICK KARO TO CLOSE */
document.addEventListener("click", function(e){
  if(!e.target.closest(".search-box")){
    box.style.display = "none";
  }
});
input.addEventListener("focus", function(){
  if(this.value.trim() === ""){
    fetch("search-suggest.php?q=shirts")
      .then(res => res.text())
      .then(data => {
        if(data){
          box.innerHTML = data;
          box.style.display = "block";
        }
      });
  }
});
</script>
    <!-- MOBILE BOTTOM NAV -->
<div class="mobile-nav">
  
  <a href="index.php">
    <span>🏠</span>
    <p>Home</p>
  </a>

  <a href="wishlist.php">
    <span>❤</span>
    <p>Wishlist</p>
  </a>

  <a href="categories.php">
    <span>☰</span>
    <p>Categories</p>
  </a>

  <a href="account.php">
    <span>👤</span>
    <p>Account</p>
  </a>

</div>
<footer class="footer">

  <!-- TOP ICONS -->

  <div class="footer-top">

    <div class="icon-box">
      <div class="icon">✔</div>
      <h4>ASSURED QUALITY</h4>
    </div>

    <div class="icon-box">
      <div class="icon">↩</div>
      <h4>EASY RETURNS</h4>
    </div>

    <div class="icon-box">
      <div class="icon">🚚</div>
      <h4>FREE SHIPPING</h4>
    </div>

  </div>

  <!-- LINKS -->

  <div class="footer-links">

    <div>
      <h3>Trendy Store</h3>

      <a href="about.php">About Us</a>
      <a href="terms.php">Terms & Conditions</a>
      <a href="privacy-policy.php">Privacy Policy</a>
      <a href="refund-policy.php">Returns Policy</a>
    </div>

    <div>
      <h3>Help</h3>

      <a href="track.php">Track Order</a>
      <a href="faq.php">FAQs</a>
      <a href="refund-policy.php">Returns</a>
      <a href="fees-payments.php">Payments</a>
    </div>

    <div>
      <h3>Shop</h3>

      <a href="men.php">Men</a>
      <a href="women.php">Women</a>
      <a href="trending.php">Trending</a>
    </div>

  </div>

  <!-- BOTTOM -->

  <div class="footer-bottom">

    <div>
      <h4>Payment Methods</h4>

      <div class="payment-icons">

        <img src="uploads/visa.png">
        <img src="uploads/mastercard.png">
        <img src="uploads/cod.png">
        <img src="uploads/paytm.png">

      </div>
    </div>

    <div>
      <h4>Secure Payments</h4>

      <div class="security-icons">

        <img src="uploads/ssl.png">

      </div>
    </div>

  </div>

</footer>

</body>
</html>