<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Women Collection</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<style>

body{
  margin:0;
  font-family:Arial,Helvetica,sans-serif;
  background:#fff;
  padding-top:95px;
}



/* ===== TOP TEXT ===== */

.top-text{
  text-align:center;
  margin:15px 0;
}

.top-text p{
  margin:0 0 8px;
  font-size:16px;
}

.top-text a{
  text-decoration:none;
  color:#e10600;
  font-weight:700;
}

/* ===== SLIDER ===== */

.women-slider{
  width:100%;
  height:550px;
  position:relative;
  overflow:hidden;
  background:#fff;
}

.slides{
  position:absolute;
  width:100%;
  height:100%;
  opacity:0;
  transition:0.7s ease-in-out;
}

.slides.active{
  opacity:1;
}

.slides img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
  background:#fff;
}

/* ===== ARROWS ===== */

.prev,
.next{
  position:absolute;
  top:50%;
  transform:translateY(-50%);
  font-size:45px;
  cursor:pointer;
  color:#fff;
  background:rgba(0,0,0,0.45);
  width:55px;
  height:55px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  z-index:5;
}

.prev{
  left:20px;
}

.next{
  right:20px;
}

/* ===== DOTS ===== */

.women-dots{
  text-align:center;
  margin:18px 0;
}

.women-dots .dot{
  display:inline-block;
  width:12px;
  height:12px;
  margin:0 5px;
  background:#ddd;
  border-radius:50%;
  cursor:pointer;
  transition:0.3s;
}

.women-dots .dot.active{
  background:#0a7d5f;
}

/* ===== CATEGORIES ===== */

.women-categories{
  max-width:1800px;
  margin:30px auto;
  padding:0 20px;
}

.women-categories h1{
  text-align:center;
  margin-bottom:30px;
  font-size:38px;
}

.category-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:25px;
}

.category-card{
  text-decoration:none;
  color:#000;
  border-radius:16px;
  overflow:hidden;
  background:#fff;
  box-shadow:0 8px 20px rgba(0,0,0,0.08);
  transition:0.3s;
}

.category-card:hover{
  transform:translateY(-8px);
  box-shadow:0 15px 30px rgba(0,0,0,0.15);
}

.category-card img{
  width:100%;
  height:220px;
  object-fit:cover;
}

.category-card p{
  display:block;
  padding:15px;
  text-align:center;
  font-weight:700;
  letter-spacing:1px;
}

/* ===== PRODUCTS ===== */

.container{
  padding:50px 20px;
}

.section-title{
  text-align:center;
  font-size:38px;
  margin-bottom:30px;
}

.product-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:25px;
}

.product-card{
  background:#fff;
  border-radius:16px;
  overflow:hidden;
  text-align:center;
  box-shadow:0 8px 20px rgba(0,0,0,0.08);
  transition:0.3s;
}

.product-card:hover{
  transform:translateY(-8px);
  box-shadow:0 15px 30px rgba(0,0,0,0.15);
}

.product-card img{
  width:100%;
  height:320px;
  object-fit:contain;
  background:#f8f8f8;
}

.product-card h3{
  margin:15px 0 8px;
}

.product-card p{
  color:#e10600;
  font-size:22px;
  font-weight:800;
  margin-bottom:20px;
}

/* ===== TRENDING BUTTONS ===== */

.trending-buttons{
  display:flex;
  flex-wrap:wrap;
  gap:12px;
  justify-content:center;
  padding:20px;
}

.trending-buttons a{
  text-decoration:none;
  padding:10px 22px;
  border:2px solid #e11c2a;
  border-radius:30px;
  color:#e11c2a;
  font-weight:700;
  transition:0.3s;
}

.trending-buttons a:hover{
  background:#e11c2a;
  color:#fff;
}


/* ===== MOBILE ===== */

@media(max-width:768px){

.banner{
  height:75px;
}

.banner h1{
  font-size:22px;
  letter-spacing:1px;
}

.top-text{
  margin:8px 0 12px;
}

.top-text p{
  font-size:14px;
}

.top-text a{
  font-size:15px;
}

.women-slider{
  height:220px;
  margin-top:0;
}

.slides img{
  object-fit:cover;
}

.prev,
.next{
  width:42px;
  height:42px;
  font-size:32px;
}

.women-dots{
  margin:10px 0 15px;
}

.category-grid{
  grid-template-columns:repeat(2,1fr);
  gap:12px;
}

.category-card img{
  height:200px;
}

.product-grid{
  grid-template-columns:repeat(2,1fr);
  gap:12px;
}

.product-card img{
  height:170px;
}

.women-categories{
  margin-top:10px;
}

.women-categories h1,
.section-title{
  font-size:24px;
  margin-bottom:18px;
}

.footer-links{
  grid-template-columns:repeat(2,1fr);
  padding:25px 20px;
}

}
.product-card{
  text-decoration:none;
  color:#000;
}
</style>
</head>

<body>
<?php include 'header.php'; ?>
<!-- ===== TOP TEXT ===== -->

<div class="top-text">
  <p>Women products coming soon…</p>
  <a href="index.php">⬅ Back to Home</a>
</div>

<!-- ===== SLIDER ===== -->

<div class="women-slider">

  <div class="slides active">
    <img src="uploads/winter edit.png">
  </div>

  <div class="slides">
    <img src="uploads/oversized.png">
  </div>

  <div class="slides">
    <img src="uploads/denim.png">
  </div>

  <span class="prev">&#10094;</span>
  <span class="next">&#10095;</span>

</div>

<!-- ===== DOTS ===== -->

<div class="women-dots">
  <span class="dot active"></span>
  <span class="dot"></span>
  <span class="dot"></span>
</div>

<!-- ===== CATEGORIES ===== -->

<div class="women-categories">

  <h1><b><u>Shop By Category</u></b></h1>

  <div class="category-grid">

    <a href="women-products.php?cat=tshirts" class="category-card">
      <img src="uploads/tshirts.png">
      <p>T-SHIRTS</p>
    </a>

    <a href="women-products.php?cat=tops" class="category-card">
      <img src="uploads/tops.png">
      <p>TOPS</p>
    </a>

    <a href="women-products.php?cat=shirts" class="category-card">
      <img src="uploads/cat shirts (3).png">
      <p>SHIRTS</p>
    </a>

    <a href="women-products.php?cat=jeans" class="category-card">
      <img src="uploads/jeans.png">
      <p>JEANS</p>
    </a>

    <a href="women-products.php?cat=dresses" class="category-card">
      <img src="uploads/dresses.png">
      <p>DRESSES</p>
    </a>

    <a href="women-products.php?cat=winter" class="category-card">
      <img src="uploads/winterwear.png">
      <p>WINTERWEAR</p>
    </a>

    <a href="women-products.php?cat=joggers" class="category-card">
      <img src="uploads/joggers.png">
      <p>JOGGERS</p>
    </a>

    <a href="women-products.php?cat=jumpsuits" class="category-card">
      <img src="uploads/jumsuits.png">
      <p>JUMPSUITS</p>
    </a>

  </div>

</div>

<!-- ===== PRODUCTS ===== -->

<div class="container">

  <h1 class="section-title"><b><u>Popular Products</u></b></h1>

  <div class="product-grid">

    <a href="popular-products.php?type=women" class="product-card">
      <img src="uploads/dress.png">
      <h3>DRESSES</h3>
     
    </a>

    <a href="popular-products.php?type=women" class="product-card">
      <img src="uploads/hoddies.png">
      <h3>Winter Hoodie</h3>
      
    </a>

    <a href="popular-products.php?type=women" class="product-card">
      <img src="uploads/joggers (3).png">
      <h3>Jogger Pant</h3>
   
    </a>

    <a href="popular-products.php?type=women" class="product-card">
      <img src="uploads/shirts.png">
      <h3>Casual Shirt</h3>
     
    </a>

  </div>

</div>

<script>

let i = 0;

const slides = document.querySelectorAll(".slides");
const dots = document.querySelectorAll(".dot");

function show(n){

  slides.forEach(s => s.classList.remove("active"));
  dots.forEach(d => d.classList.remove("active"));

  slides[n].classList.add("active");
  dots[n].classList.add("active");

}

document.querySelector(".next").onclick = () => {

  i = (i + 1) % slides.length;
  show(i);

};

document.querySelector(".prev").onclick = () => {

  i = (i - 1 + slides.length) % slides.length;
  show(i);

};

dots.forEach((dot,index)=>{

  dot.onclick = () => {

    i = index;
    show(i);

  };

});

setInterval(()=>{

  i = (i + 1) % slides.length;
  show(i);

},4000);

</script>

<?php include 'footer.php'; ?>

</body>
</html>