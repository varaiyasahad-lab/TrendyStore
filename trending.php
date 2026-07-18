<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Trending Collection</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
  margin:0;
  font-family:Arial,Helvetica,sans-serif;
  background:#fff;
  padding-top:95px;
}


/* ===== BACK BUTTON ===== */

.back-home-wrap{
  text-align:center;
  margin:20px 0;
}

.back-home{
  display:inline-block;
  padding:12px 30px;
  border-radius:40px;
  background:#fff;
  border:2px solid #e63946;
  color:#e63946;
  font-weight:700;
  text-decoration:none;
  transition:0.3s;
}

.back-home:hover{
  background:#e63946;
  color:#fff;
}

/* ===== SLIDER ===== */

.hero-slider{
  width:100%;
  height:650px;
  overflow:hidden;
  background:#fff;
}

.hero-slide{
  width:100%;
  height:650px;
background-size:100% 100%;
  background-repeat:no-repeat;
  background-position:center;
  background-color:#fff;
}

.carousel-control-prev-icon,
.carousel-control-next-icon{
  background-color:#000;
  border-radius:50%;
  width:55px;
  height:55px;
  background-size:60%;
}

/* ===== PRODUCTS ===== */

.section{
  padding:60px 30px;
}

.section h2{
  font-size:34px;
  font-weight:900;
  margin-bottom:30px;
}

.product-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:25px;
}

.product-card{
  background:#fff;
  border-radius:18px;
  overflow:hidden;
  text-decoration:none;
  color:#000;
  box-shadow:0 8px 25px rgba(0,0,0,0.08);
  transition:0.3s;
}

.product-card:hover{
  transform:translateY(-8px);
  box-shadow:0 18px 40px rgba(0,0,0,0.15);
}

.product-card img{
  width:100%;
  height:320px;
  object-fit:contain;
  background:#f8f8f8;
}

.product-info{
  padding:15px;
  text-align:center;
}

.product-info h4{
  font-size:17px;
  font-weight:700;
  margin-bottom:8px;
}

.product-info p{
  color:#e63946;
  font-size:20px;
  font-weight:900;
  margin:0;
}

/* ===== MOBILE ===== */

@media(max-width:768px){

.hero-slider{
  height:300px;
}

.hero-slide{
  height:300px;
  background-size:contain;
  background-repeat:no-repeat;
  background-position:center;
  background-color:#fff;
}

.banner{
  height:70px;
}

.banner h1{
  font-size:26px;
}

.section{
  padding:35px 15px;
}

.section h2{
  font-size:24px;
}

.product-grid{
  grid-template-columns:repeat(2,1fr);
  gap:15px;
}

.product-card img{
  height:180px;
}

}

</style>
</head>

<body>

<?php include 'header.php'; ?>

<!-- ===== BACK BUTTON ===== -->

<div class="back-home-wrap">
  <a href="index.php" class="back-home">
    ⬅ Back To Home
  </a>
</div>

<!-- ===== SLIDER ===== -->

<div id="trendingSlider"
     class="carousel slide hero-slider"
     data-bs-ride="carousel"
     data-bs-interval="3500">

  <div class="carousel-indicators">

    <button type="button"
            data-bs-target="#trendingSlider"
            data-bs-slide-to="0"
            class="active"></button>

    <button type="button"
            data-bs-target="#trendingSlider"
            data-bs-slide-to="1"></button>

  </div>

  <div class="carousel-inner">

    <div class="carousel-item active">

      <div class="hero-slide"
      style="background-image:url('uploads/trnding (2).png');">
      </div>

    </div>

    <div class="carousel-item">

      <div class="hero-slide"
      style="background-image:url('uploads/trnding.png');">
      </div>

    </div>

  </div>

  <button class="carousel-control-prev"
          type="button"
          data-bs-target="#trendingSlider"
          data-bs-slide="prev">

    <span class="carousel-control-prev-icon"></span>

  </button>

  <button class="carousel-control-next"
          type="button"
          data-bs-target="#trendingSlider"
          data-bs-slide="next">

    <span class="carousel-control-next-icon"></span>

  </button>

</div>

<!-- ===== MEN SECTION ===== -->

<section class="section">

  <h2>👔 TRENDING MEN</h2>

  <div class="product-grid">

    <a href="men.php" class="product-card">

      <img src="uploads/tn ts.png">

      <div class="product-info">
        <h4>Printed T-Shirt</h4>
       
      </div>

    </a>

    <a href="men.php" class="product-card">

      <img src="uploads/tn sh.png">

      <div class="product-info">
        <h4>Casual Shirt</h4>
       
      </div>

    </a>

    <a href="men.php" class="product-card">

      <img src="uploads/tn hd.png">

      <div class="product-info">
        <h4>Winter Hoodie</h4>
      
      </div>

    </a>

    
    <a href="men.php" class="product-card">

      <img src="uploads/tn jn.png">

      <div class="product-info">
        <h4>Jogger Pants</h4>
      
      </div>

    </a>
    
    
    
  </div>

</section>

<!-- ===== WOMEN SECTION ===== -->

<section class="section">

  <h2>👗 TRENDING WOMEN</h2>

  <div class="product-grid">

    <a href="women.php" class="product-card">

      <img src="uploads/tn tp.png">

      <div class="product-info">
        <h4>Stylish Top</h4>
        
      </div>

    </a>

    <a href="women.php" class="product-card">

      <img src="uploads/tn ds.png">

      <div class="product-info">
        <h4>Casual Dress</h4>
       
      </div>

    </a>

    <a href="women.php" class="product-card">

      <img src="uploads/tn js.png">

      <div class="product-info">
        <h4>Denim Jeans</h4>
      
      </div>

    </a>

    <a href="women.php" class="product-card">

      <img src="uploads/tn hd (2).png">

      <div class="product-info">
        <h4>Winter Hoodie</h4>
       
      </div>

    </a>

  </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>