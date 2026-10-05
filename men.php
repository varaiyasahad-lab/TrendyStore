<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Men Collection</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<style>

body{
  margin:0;
  font-family:Arial,Helvetica,sans-serif;
  background:#fff;
  padding-top:80px;
}


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


.men-slider{
  width:100%;
  height:550px;
  position:relative;
  overflow:hidden;
  background:#fff;
}

.men-slide{
  position:absolute;
  width:100%;
  height:100%;
  opacity:0;
  transition:0.7s ease-in-out;
}

.men-slide.active{
  opacity:1;
}

.men-slide img{
  width:100%;
  height:100%;
  object-fit:conver;
  display:block;
  background:#fff;
}


.men-prev,
.men-next{
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

.men-prev{
  left:20px;
}

.men-next{
  right:20px;
}


.men-dots{
  text-align:center;
  margin:18px 0;
}

.men-dots .dot{
  display:inline-block;
  width:12px;
  height:12px;
  margin:0 5px;
  background:#ddd;
  border-radius:50%;
  cursor:pointer;
  transition:0.3s;
}

.men-dots .dot.active{
  background:#0a7d5f;
}


.men-categories{
  max-width:1800px;
  margin:30px auto;
  padding:0 20px;
}

.men-categories h1{
  text-align:center;
  margin-bottom:30px;
  font-size:38px;
}

.category-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:25px;
}

.cat-card{
  text-decoration:none;
  color:#000;
  border-radius:16px;
  overflow:hidden;
  background:#fff;
  box-shadow:0 8px 20px rgba(0,0,0,0.08);
  transition:0.3s;
}

.cat-card:hover{
  transform:translateY(-8px);
  box-shadow:0 15px 30px rgba(0,0,0,0.15);
}

.cat-card img{
  width:100%;
  height:220px;
  object-fit:cover;
}

.cat-card span{
  display:block;
  padding:15px;
  text-align:center;
  font-weight:700;
  letter-spacing:1px;
}


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

.men-slider{
  height:220px;
  margin-top:0;
}

.men-slide img{
  object-fit:cover;
}

.men-prev,
.men-next{
  width:42px;
  height:42px;
  font-size:32px;
}

.men-dots{
  margin:10px 0 15px;
}

.category-grid{
  grid-template-columns:repeat(2,1fr);
  gap:12px;
}

.cat-card img{
  height:150px;
}

.product-grid{
  grid-template-columns:repeat(2,1fr);
  gap:12px;
}

.product-card img{
  height:170px;
}

.men-categories{
  margin-top:10px;
}

.men-categories h1,
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


<div class="top-text">
  <p>Men products coming soon…</p>
  <a href="index.php">⬅ Back to Home</a>
</div>


<div class="men-slider">

  <div class="men-slide active">
    <img src="uploads/slider (5).png">
  </div>

  <div class="men-slide">
    <img src="uploads/sd.png">
  </div>

  <div class="men-slide">
    <img src="uploads/sd (3).png">
  </div>

  <div class="men-slide">
    <img src="uploads/sd (2).png">
  </div>

  <span class="men-prev">&#10094;</span>
  <span class="men-next">&#10095;</span>

</div>


<div class="men-dots">
  <span class="dot active"></span>
  <span class="dot"></span>
  <span class="dot"></span>
  <span class="dot"></span>
</div>


<div class="men-categories">

  <h1><b><u>Shop By Category</u></b></h1>

  <div class="category-grid">

    <a href="men-products.php?cat=tshirts" class="cat-card">
      <img src="uploads/cat tshirts.png">
      <span>T-SHIRTS</span>
    </a>

    <a href="men-products.php?cat=shirts" class="cat-card">
      <img src="uploads/cat shirts.png">
      <span>SHIRTS</span>
    </a>

    <a href="men-products.php?cat=joggers" class="cat-card">
      <img src="uploads/cat joggers.png">
      <span>JOGGERS</span>
    </a>

    <a href="men-products.php?cat=jeans" class="cat-card">
      <img src="uploads/cat jeans.png">
      <span>JEANS</span>
    </a>

    <a href="men-products.php?cat=polos" class="cat-card">
      <img src="uploads/cat polo.png">
      <span>POLOS</span>
    </a>

    <a href="men-products.php?cat=pants" class="cat-card">
      <img src="uploads/cat rectangle.png">
      <span>PANTS</span>
    </a>

    <a href="men-products.php?cat=shorts" class="cat-card">
      <img src="uploads/cat shorts.png">
      <span>SHORTS</span>
    </a>

    <a href="men-products.php?cat=hoodies" class="cat-card">
      <img src="uploads/cat hoodie.png">
      <span>Hoodies</span>
</a>

  </div>

</div>

<div class="container">

  <h1 class="section-title"><b><u>Popular Products</u></b></h1>

  <div class="product-grid">

    <a href="popular-products.php?type=men" class="product-card">
      <img src="uploads/p t-shirts.png">
      <h3>Printed T-Shirt</h3>
  
    </a>

    <a href="popular-products.php?type=men" class="product-card">
      <img src="uploads/winter hoodie.png">
      <h3>Winter Hoodie</h3>
     
    </a>

    <a href="popular-products.php?type=men" class="product-card">
      <img src="uploads/jooger.png">
      <h3>Jogger Pant</h3>
   
    </a>

    <a href="popular-products.php?type=men" class="product-card">
      <img src="uploads/casual.png">
      <h3>Casual Shirt</h3>
   
    </a>

  </div>

</div>





<script>

let i = 0;

const slides = document.querySelectorAll(".men-slide");
const dots = document.querySelectorAll(".dot");

function show(n){

  slides.forEach(s => s.classList.remove("active"));
  dots.forEach(d => d.classList.remove("active"));

  slides[n].classList.add("active");
  dots[n].classList.add("active");
}

document.querySelector(".men-next").onclick = () => {

  i = (i + 1) % slides.length;
  show(i);

};

document.querySelector(".men-prev").onclick = () => {

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

window.addEventListener("load",()=>{

  show(i);

});

</script>


<?php include 'footer.php'; ?>
</body>
</html>