<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Kids Collection</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{margin:0;font-family:Arial}
.banner{
  height:100px;
  background-image:url("images/kids-banner.jpg");
  background-size:cover;
  background-position:center;
  display:flex;
  align-items:center;
  justify-content:center;
}
.banner h1{
  background:rgba(0, 0, 0, 0.55);
  color:#fff;
  padding:10px 50px;
  border-radius:10px;
  font-size:40px;
}
.kids-slider {
  width: 100%;
  height: 780px;
  position: relative;
  overflow: hidden;
}

.kids-slides {
  position: absolute;
  width: 100%;
  height: 100%;
  opacity: 0;
  transition: 0.7s ease-in-out;
}

.kids-slides img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.kids-slides.active {
  opacity: 1;
}

.kids-caption {
  position: absolute;
  top: 40%;
  left: 8%;
  color: #1409f2;
}

.kids-caption h1 {
  font-size: 30px;
  margin-bottom: 10px;
}

.kids-caption p {
  font-size: 18px;
}

/* Arrows */
.kids-prev, .kids-next {
  position: absolute;
  top: 50%;
  font-size: 40px;
  color: #000;
  cursor: pointer;
  padding: 10px;
}

.kids-prev { left: 20px; }
.kids-next { right: 20px; }
.footer{
  background:#eee;
  margin-top:80px;
  font-family:Arial,Helvetica,sans-serif;
}

.footer-top{
  background:#e10600;
  color:#fff;
  text-align:center;
  padding:20px;
}

.footer-top h2{
  margin:0;
  letter-spacing:2px;
}

.footer-top p{
  font-size:26px;
  margin:10px 0 0;
}

.footer-links{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:40px;
  padding:50px 80px;
}

.footer-links h4{
  color:#e10600;
  margin-bottom:15px;
}

.footer-links a{
  display:block;
  color:#333;
  text-decoration:none;
  margin:8px 0;
  font-size:14px;
}

.footer-links a:hover{
  color:#e10600;
}

.footer-app{
  text-align:center;
  padding:30px;
}

.footer-app img{
  height:45px;
  margin:10px;
}

.footer-bottom{
  background:#ddd;
  text-align:center;
  padding:15px;
  font-size:14px;
}

/* Mobile */
@media(max-width:768px){
  .footer-links{
    grid-template-columns:repeat(2,1fr);
    padding:30px;
  }
}
.shop-category{
  padding:40px;
}

.shop-category h2{
  font-size:26px;
  margin-bottom:25px;
}

.category-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:25px;
}

.category-card{
  background:#fff;
  border-radius:16px;
  box-shadow:0 8px 20px rgba(0,0,0,0.08);
  text-decoration:none;
  color:#000;
  overflow:hidden;
}

.category-card img{
  width:100%;
  height:260px;
  object-fit:cover;
}

.category-card h3{
  text-align:center;
  margin:12px 0 0;
  font-size:18px;
}

.category-card p{
  text-align:left;
  font-size:13px;
  font-weight:600;
  padding:10px 15px 15px;
  text-transform:uppercase;
}

/* MOBILE */
@media(max-width:768px){
  .category-grid{
    grid-template-columns:repeat(2,1fr);
  }
}
.product-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:25px;
}

.product-card{
  background:#fff;
  border-radius:12px;
  padding:15px;
  text-align:center;
  transition:.3s;
  cursor:pointer;
}

.product-card img{
  width:100%;
  height:260px;
  object-fit:contain;
}

.product-card h5{
  margin:10px 0 5px;
}

.product-card:hover{
  transform:translateY(-5px);
  box-shadow:0 8px 25px rgba(0, 0, 0, 0.1);
}
/* ===================================== */
/* ADD ONLY – KIDS SLIDER NO CUT FIX */
/* ===================================== */

.kids-slide img{
  width:100%;
  height:100%;
  object-fit: contain !important;   /* 🔒 CUT BAND */
  background:#fff;
}

/* desktop height */
.kids-slider{
  height:900px !important;
  overflow:hidden;
}

/* mobile */
@media(max-width:768px){
  .kids-slider{
    height:320px !important;
  }
}

</style>
</head>

<body>

<div class="banner">
  <h1>KIDS COLLECTION</h1>
</div>

<div style="text-align:center;margin-top:10px">
  <p>Kids products coming soon…</p>
  <a href="index.php">⬅ Back to Home</a>
</div>
<!-- KIDS MAIN SLIDER -->
<div class="kids-slider">
  <div class="kids-slides active">
    <img src="uploads\kids 1.png" alt="Kids Winter">
    <div class="kids-caption">
      <h1></h1>
      <p><b></b></p>
    </div>
  </div>

  <div class="kids-slides">
    <img src="uploads\kids3.png" alt="Cartoon Wear">
    <div class="kids-caption">
      <h1></h1>
      <p></p>
    </div>
  </div>

  <div class="kids-slides">
    <img src="uploads\kids 5.png" alt="Party Wear">
    <div class="kids-caption">
      <h1></h1>
      <p></p>
    </div>
  </div>

  <div class="kids-slides">
    <img src="uploads\kids 4.png" alt="Party Wear">
    <div class="kids-caption">
      <h1></h1>
      <p></p>
    </div>
  </div>


  <!-- Arrows -->
  <span class="kids-prev">&#10094;</span>
  <span class="kids-next">&#10095;</span>
</div>
<!-- SHOP BY CATEGORY -->
<section class="shop-category">
  <h2>Shop By Category.</h2>

  <div class="category-grid">

    <a href="kids-products.php?cat=boys-tshirts" class="category-card">
      <img src="uploads\kids tshirtts.png" alt="">
      <p>BOYS T-SHIRTS</p>
    </a>

    <a href="kids-products.php?cat=boys-shirts" class="category-card">
      <img src="uploads\girls tshirts.png" alt="">
      <p>GIRLS SHIRTS</p>
    </a>

    <a href="kids-products.php?cat=girls-dresses" class="category-card">
      <img src="uploads\dresses (2).png" alt="">
      <p>GIRLS DRESSES</p>
    </a>

    <a href="kids-products.php?cat=boys-joggers" class="category-card">
      <img src="uploads\joggers (2).png" alt="">
      <p>BOYS JOGGERS</p>
    </a>

    <a href="kids-products.php?cat=kids-jeans" class="category-card">
      <img src="uploads\jeans (2).png" alt="">
      <p>KIDS JEANS</p>
    </a>

    <a href="kids-products.php?cat=kids-pants" class="category-card">
      <img src="uploads\pents.png" alt="">
      <p>KIDS PANTS</p>
    </a>

    <a href="kids-products.php?cat=kids-shorts" class="category-card">
      <img src="uploads\shorts'.png" alt="">
      <p>KIDS SHORTS</p>
    </a>

    <a href="kids-products.php?cat=kids-nightwear" class="category-card">
      <img src="uploads\nightwea.png" alt="">
      <p>KIDS NIGHTWEAR</p>
    </a>

  </div>
</section>
<!-- CATEGORY PRODUCTS -->
<div class="container mt-5">
  <h1 class="section-title"><b><u>Popular Products</u></b></h1>

  <div class="product-grid">

    <div class="product-card">
      <img src="uploads\printed.png">
      <h3>Printed T-Shirt</h3>
      <p>₹599</p>
    </div>

    <div class="product-card">
      <img src="uploads\hoodies.png">
      <h3>Winter Hoodie</h3>
      <p>₹1299</p>
    </div>

    <div class="product-card">
      <img src="uploads\jogger.png">
      <h3>Jogger Pant</h3>
      <p>₹999</p>
    </div>

    <div class="product-card">
      <img src="uploads\casual shirts'.png">
      <h3>Casual Shirt</h3>
      <p>₹1099</p>
    </div>

  </div>
</div>

<script>
let kIndex = 0;
const kSlides = document.querySelectorAll(".kids-slides");

function showKidsSlide(i) {
  kSlides.forEach(slide => slide.classList.remove("active"));
  kSlides[i].classList.add("active");
}

document.querySelector(".kids-next").onclick = () => {
  kIndex = (kIndex + 1) % kSlides.length;
  showKidsSlide(kIndex);
};

document.querySelector(".kids-prev").onclick = () => {
  kIndex = (kIndex - 1 + kSlides.length) % kSlides.length;
  showKidsSlide(kIndex);
};

// Auto Slide
setInterval(() => {
  kIndex = (kIndex + 1) % kSlides.length;
  showKidsSlide(kIndex);
}, 4000);
/* ===================================== */
/* ADD ONLY – KIDS SLIDER SAFE INIT */
/* ===================================== */

window.addEventListener("load", () => {
  if (typeof show === "function") {
    show(i);
  }
});

window.addEventListener("resize", () => {
  if (typeof show === "function") {
    show(i);
  }
});

</script>
<!-- FOOTER -->
<footer class="footer">

  <div class="footer-top">
    <h2>HOMEGROWN INDIAN BRAND</h2>
    <p>Over <b>6 Million</b> Happy Customers</p>
  </div>

  <div class="footer-links">

    <div>
      <h4>NEED HELP</h4>
      <a href="#">Contact Us</a>
      <a href="#">Track Order</a>
      <a href="#">Returns & Refunds</a>
      <a href="#">FAQs</a>
      <a href="#">My Account</a>
    </div>

    <div>
      <h4>COMPANY</h4>
      <a href="#">About Us</a>
      <a href="#">Careers</a>
      <a href="#">Gift Vouchers</a>
      <a href="#">Community</a>
    </div>

    <div>
      <h4>MORE INFO</h4>
      <a href="#">T&C</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Sitemap</a>
      <a href="#">Blogs</a>
    </div>

    <div>
      <h4>STORE NEAR ME</h4>
      <a href="#">Mumbai</a>
      <a href="#">Pune</a>
      <a href="#">Bangalore</a>
      <a href="#">View More</a>
    </div>

  </div>

  <div class="footer-app">
    <p>EXPERIENCE THE TRENDY STORE APP</p>
    <img src="uploads/google-play.png">
    <img src="uploads/app-store.png">
  </div>

  <div class="footer-bottom">
    © 2026 Trendy Store | Created by Sahad Varaiya
  </div>

</footer>


</body>
</html>
