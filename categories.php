<?php
session_start();
?>

<?php
include "db.php";

/* MEN */
$menCats = $conn->query("SELECT DISTINCT category FROM products WHERE gender='men'");

/* WOMEN */
$womenCats = $conn->query("SELECT DISTINCT category FROM products WHERE gender='women'");

/* IMAGE MAP */
$imgMap = [
  "men_tshirts" => "tshirts (7).png",
  "men_shirts" => "shirts3.png",
  "men_jeans" => "jeans (4).png",
  "men_trackpants" => "trackpants.png",
  "men_nightwear" => "nightwear1.png",
  "men_hoodies" => "hoodies1.png",
  "men_pants" => "men pants.png",
  "men_joggers" => "men jg (3).png",
  "men_polos" => "polos.png",
  "men_shorts" => "shorts.png",
   
  

  "women_tshirts" => "women tshirts.png",
  "women_shirts" => "women shirts.png",
  "women_jeans" => "women jeans.png",
  "women_trackpants" => "women t & p.png",
  "women_nightwear" => "dresses (3).png",
  "women_hoodies" => "women hoodies.png",
  "women_dresses" => "dresses (4).png",
  "women_tops" => "tops (5).png",
  "women_winter" => "winter (4).png",
  "women_joggers" => "joggers (7).png",
  "women_jumpsuits" => "jumpsuits (4).png",
];
?>

<!DOCTYPE html>
<html>
<head>
<title>Categories</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
  margin:0;
  font-family:'Poppins',sans-serif;
  background:#f7f7f7;
  padding-top: 95px;
}

/* SECTION */
.section{
  padding:18px;
}

.section h2{
  font-size:18px;
  font-weight:700;
  margin-bottom:12px;
  color:#222;
}

/* GRID */
.cat-row{
  display:grid;
  grid-template-columns:repeat(2,1fr);
  gap:14px;
}

/* CARD */
.cat-box{
  background:#fff;
  border-radius:18px;
  overflow:hidden;
  text-align:center;
  text-decoration:none;
  color:#000;
  transition:all 0.25s ease;
  box-shadow:0 2px 8px rgba(0,0,0,0.05);
}

/* HOVER */
.cat-box:hover{
  transform:translateY(-4px);
  box-shadow:0 6px 18px rgba(0,0,0,0.12);
}

/* IMAGE */
.cat-box img{
  width:100%;
  height:170px;
  object-fit:contain;
  display:block;
}

/* TEXT */
.cat-box span{
  display:block;
  padding:10px;
  font-size:14px;
  font-weight:600;
  color:#333;
}

/* 🔥 subtle divider between sections */
.section + .section{
  margin-top:10px;
}

/* DESKTOP */
@media(min-width:768px){
  .cat-row{
    grid-template-columns:repeat(7,1fr);
  }
}
</style>
</head>

<body>
  <?php include 'header.php'; ?>
<!-- MEN -->
<div class="section">
  <h2>👔 Men</h2>

  <div class="cat-row">
  <?php while($m = $menCats->fetch_assoc()){ 
    $cat = strtolower(trim($m['category']));
    $key = "men_" . $cat;
  ?>
    <a href="category.php?cat=<?= $cat ?>&gender=men" class="cat-box">
      <img src="uploads/<?= $imgMap[$key] ?? 'default.png' ?>">
      <span><?= ucfirst($cat) ?></span>
    </a>
  <?php } ?>
  </div>
</div>

<!-- WOMEN -->
<div class="section">
  <h2>👗 Women</h2>

  <div class="cat-row">
  <?php while($w = $womenCats->fetch_assoc()){ 
    $cat = strtolower(trim($w['category']));
    if($cat == "women"){
    continue;
}
    $key = "women_" . $cat;
  ?>
    <a href="category.php?cat=<?= $cat ?>&gender=women" class="cat-box">
      <?php if(isset($imgMap[$key])){ ?>
<img src="uploads/<?= $imgMap[$key] ?>">
<?php } ?>
      <span><?= ucfirst($cat) ?></span>
    </a>
  <?php } ?>
  </div>
</div>

<?php include "bottom-nav.php"; ?>
  <?php include 'footer.php'; ?>
</body>
</html> 