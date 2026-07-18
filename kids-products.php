<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<title>Product Detail</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{font-family:Arial;background:#fff;margin:0}

/* LAYOUT */
.product-page{
  max-width:1100px;
  margin:20px auto;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:40px;
}

/* SLIDER */
.slider{
  display:flex;
  overflow-x:auto;
  scroll-snap-type:x mandatory;
}
.slide{
  min-width:100%;
  scroll-snap-align:start;
}
.slide img{
  width:100%;
  display:block;
}

/* COLOR */
.selected-color{margin:15px 0 8px;font-weight:bold}
.thumb-row{display:flex;gap:10px;overflow-x:auto}
.thumb{
  width:70px;height:90px;object-fit:cover;
  border-radius:8px;border:2px solid #ccc;
  cursor:pointer;
}
.thumb.active{border:3px solid #000}

/* SIZE */
.size-box{
  display:inline-block;
  padding:8px 14px;
  border:1px solid #aaa;
  margin:5px;
  cursor:pointer;
}
.size-box.active{
  border:2px solid #e63946;
  font-weight:bold;
}

/* BUTTON */
button{
  width:100%;
  background:#e63946;
  color:#fff;
  padding:14px;
  border:none;
  font-size:16px;
  margin-top:20px;
}
button:disabled{background:#aaa}

/* PRICE BOX */
.price-box{margin-top:12px;}
.discount{color:#1a7f37;font-weight:bold;margin-right:8px;}
.old-price{color:#888;text-decoration:line-through;}
.new-price{font-size:26px;font-weight:bold;margin-top:6px;}

/* SERVICE ICONS */
.service-row{
  display:flex;
  justify-content:space-between;
  margin-top:15px;
  padding:12px 0;
  border-top:1px solid #eee;
  border-bottom:1px solid #eee;
}
.service{text-align:center;font-size:13px;}
.service i{
  display:block;
  font-size:20px;
  margin-bottom:6px;
  color:#1a7f37;
}
</style>
</head>

<body>

<form method="post" action="add-to-cart.php">

<!-- ✅ ADDED: PRODUCT ID (MANDATORY) -->
<input type="hidden" name="id" value="5">

<div class="product-page">

<!-- LEFT -->
<div>
  <div class="slider" id="imageSlider">
    <div class="slide"><img src="uploads/grey.png"></div>
    <div class="slide"><img src="uploads/grey-highlight.png"></div>
    <div class="slide"><img src="uploads/grey-front.png"></div>
    <div class="slide"><img src="uploads/grey-back.png"></div>
    <div class="slide"><img src="uploads/grey-extra.png"></div>
  </div>
</div>

<!-- RIGHT -->
<div>
  <h2>Men Casual Shirt</h2>

  <div class="selected-color" id="colorText">
    Selected Color: GREY
  </div>

  <div class="thumb-row">
    <img src="uploads/grey.png" class="thumb active" onclick="changeColor(this,'grey')">
    <img src="uploads/black.png" class="thumb" onclick="changeColor(this,'black')">
    <img src="uploads/brown.png" class="thumb" onclick="changeColor(this,'brown')">
    <img src="uploads/blue.png" class="thumb" onclick="changeColor(this,'blue')">
    <img src="uploads/white.png" class="thumb" onclick="changeColor(this,'white')">
  </div>

  <div class="price-box">
    <span class="discount">81% OFF</span>
    <span class="old-price">₹2,499</span>
    <div class="new-price">₹473</div>
  </div>

  <div class="service-row">
    <div class="service"><i class="fa-solid fa-rotate-left"></i>7 Days Return</div>
    <div class="service"><i class="fa-solid fa-money-bill-wave"></i>Cash on Delivery</div>
    <div class="service"><i class="fa-solid fa-headset"></i>24×7 Support</div>
  </div>

  <h4 style="margin-top:20px;">Select Size</h4>
  <span class="size-box" onclick="selectSize(this,'S')">S</span>
  <span class="size-box" onclick="selectSize(this,'M')">M</span>
  <span class="size-box" onclick="selectSize(this,'L')">L</span>
  <span class="size-box" onclick="selectSize(this,'XL')">XL</span>

  <input type="hidden" name="color" id="color" value="grey">
  <input type="hidden" name="size" id="size">

  <!-- ✅ FIXED -->
  <button type="submit" id="cartBtn" disabled>ADD TO CART</button>
</div>

</div>
</form>

<script>
const imageSets = {
  grey:["uploads/grey.png","uploads/highlight.png","uploads/front.png","uploads/back.png","uploads/extra.png"],
  black:["uploads/black.png","uploads/highlight (2).png","uploads/front (2).png","uploads/back (2).png","uploads/extra (2).png"],
  brown:["uploads/brown.png","uploads/highlight (3).png","uploads/front (4).png","uploads/back (4).png","uploads/extra (3).png"],
  blue:["uploads/blue.png","uploads/highlight (4).png","uploads/front (3).png","uploads/back (5).png","uploads/extra (4).png"],
  white:["uploads/white.png","uploads/highlight (5).png","uploads/front (5).png","uploads/back (3).png","uploads/extra (5).png"]
};

const slider=document.getElementById("imageSlider");
const colorInp=document.getElementById("color");
const sizeInp=document.getElementById("size");
const cartBtn=document.getElementById("cartBtn");

function changeColor(el,color){
  if(!imageSets[color]) return;   // ✅ SAFETY
  document.getElementById("colorText").innerText="Selected Color: "+color.toUpperCase();
  colorInp.value=color;

  document.querySelectorAll(".thumb").forEach(t=>t.classList.remove("active"));
  el.classList.add("active");

  slider.innerHTML="";
  imageSets[color].forEach(src=>{
    slider.innerHTML+=`<div class="slide"><img src="${src}"></div>`;
  });
  checkEnable();
}

function selectSize(el,size){
  document.querySelectorAll(".size-box").forEach(s=>s.classList.remove("active"));
  el.classList.add("active");
  sizeInp.value=size;
  checkEnable();
}

function checkEnable(){
  cartBtn.disabled = !(colorInp.value && sizeInp.value);
}
</script>

</body>
</html>