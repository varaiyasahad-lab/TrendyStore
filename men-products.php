<?php
session_start();
include "db.php";

/* CATEGORY FILTER */

$gender   = $_GET['gender'] ?? 'men';
$cat      = $_GET['cat'] ?? 'all';
$price    = $_GET['price'] ?? '';
$color    = $_GET['color'] ?? '';
$size     = $_GET['size'] ?? '';
$discount = $_GET['discount'] ?? '';
$brand    = $_GET['brand'] ?? '';
$occasion = $_GET['occasion'] ?? '';
$sort     = $_GET['sort'] ?? '';

/* MAIN QUERY */

$sql = "SELECT * FROM products WHERE gender='$gender'";
if(!empty($cat) && $cat != 'all'){

    if(is_array($cat)){
        $cat = $cat[0];
    }

    $sql .= " AND category='$cat'";
}



/* PRICE */

if(!empty($price)){

if(is_array($price)){
    $price = $price[0];
}

$priceData = explode('-', $price);

$minPrice = isset($priceData[0]) ? (int)$priceData[0] : 0;
$maxPrice = isset($priceData[1]) ? (int)$priceData[1] : 999999;

$sql .= " AND price >= $minPrice AND price <= $maxPrice";
}

/* COLOR */

if($color != ''){

$colorArray = is_array($color) ? $color : explode(',', $color);
$colorArray = array_map('strtolower', $colorArray);

$colorValues = "'" . implode("','", $colorArray) . "'";

$sql .= "
AND id IN (
SELECT product_id
FROM product_colors
WHERE LOWER(color_name) IN ($colorValues)
)";
}

/* SIZE */

if($size != ''){

$sizeArray = is_array($size) ? $size : explode(',', $size);
$sizeArray = array_map('strtoupper', $sizeArray);

$sizeValues = "'" . implode("','", $sizeArray) . "'";

$sql .= "
AND id IN (
SELECT product_id
FROM product_sizes
WHERE UPPER(size) IN ($sizeValues)
)";
}

/* DISCOUNT */

if($discount != ''){

$discountArray = is_array($discount) ? $discount : explode(',', $discount);

$discountValues = "'" . implode("','", $discountArray) . "'";

$sql .= " AND discount IN ($discountValues)";
}

/* BRAND */

if($brand != ''){

$brandArray = is_array($brand) ? $brand : explode(',', $brand);
$brandArray = array_map('strtolower', $brandArray);

$brandValues = "'" . implode("','", $brandArray) . "'";

$sql .= " AND LOWER(brand) IN ($brandValues)";
}

/* OCCASION */
if($occasion != ''){

$occasionArray = is_array($occasion) ? $occasion : explode(',', $occasion);
$occasionArray = array_map('strtolower', $occasionArray);

$occasionValues = "'" . implode("','", $occasionArray) . "'";

$sql .= " AND LOWER(occasion) IN ($occasionValues)";
}
/* SORT */

if($sort == 'low'){
$sql .= " ORDER BY price ASC";
}
elseif($sort == 'high'){
$sql .= " ORDER BY price DESC";
}
elseif($sort == 'discount'){
$sql .= " ORDER BY discount DESC";
}
else{
$sql .= " ORDER BY id DESC";
}

/* RESULT */

$result = mysqli_query($conn, $sql);


/* CART COUNT */

$cartCount = 0;

if(isset($_SESSION['cart'])){

foreach($_SESSION['cart'] as $item){

$cartCount += $item['qty'];

}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1, maximum-scale=1">

<title>
<?= strtoupper($cat ?: 'Men') ?> Collection
</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
font-family:Arial,sans-serif;
background:#f1f3f6;
overflow-x:hidden;
margin-top: 95px;
}

/* NAVBAR */

.navbar{
position:sticky;
top:0;
z-index:1000;
background:#111;
padding:14px 20px;
display:flex;
justify-content:space-between;
align-items:center;
color:#fff;
}

.logo{
font-size:28px;
font-weight:bold;
letter-spacing:1px;
}

.logo a{
color:#fff;
text-decoration:none;
}

.cart{
position:relative;
font-size:24px;
text-decoration:none;
color:#fff;
}

.cart-count{
position:absolute;
top:-8px;
right:-12px;
background:red;
color:#fff;
font-size:11px;
padding:3px 7px;
border-radius:50%;
}

/* TITLE */

.page-title{
text-align:center;
font-size:38px;
font-weight:800;
margin:30px 0;
color:#2874f0;
letter-spacing:2px;
}

/* GRID */

.grid{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:22px;
padding:20px;
}

/* CARD */

.card{
background:#fff;
border-radius:18px;
overflow:hidden;
box-shadow:0 5px 18px rgba(0,0,0,.08);
transition:.3s;
cursor:pointer;
position:relative;
}

.card:hover{
transform:translateY(-6px);
box-shadow:0 15px 30px rgba(0,0,0,.15);
}

/* IMAGE */

.image-box{
background:#fff;
padding:15px;
position:relative;
}

.image-box img{
width:100%;
height:300px;
object-fit:contain;
display:block;
}

/* WISHLIST */

.wishlist{
position:absolute;
top:14px;
right:14px;
width:40px;
height:40px;
background:#fff;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
box-shadow:0 3px 10px rgba(0,0,0,.15);
z-index:10;
}

.wishlist a{
text-decoration:none;
font-size:20px;
color:#000;
}

/* DETAILS */

.details{
padding:15px;
}

.product-name{
font-size:17px;
font-weight:700;
line-height:24px;
height:48px;
overflow:hidden;
}

.price{
margin-top:10px;
font-size:20px;
font-weight:bold;
}

.old-price{
text-decoration:line-through;
color:#888;
font-size:15px;
margin-right:6px;
}

.new-price{
color:#2874f0;
}

.discount{
font-size:13px;
color:green;
margin-left:5px;
}

.stock{
margin-top:8px;
font-size:14px;
font-weight:bold;
color:green;
}

/* BUTTON */

.buttons{
margin-top:18px;
}

.view-btn{
width:100%;
padding:12px;
border:none;
border-radius:30px;
font-size:15px;
font-weight:bold;
cursor:pointer;
background:#2874f0;
color:#fff;
transition:.3s;
}

.view-btn:hover{
opacity:.9;
}

/* MOBILE */

@media(max-width:768px){

.grid{
grid-template-columns:repeat(2,1fr);
gap:12px;
padding:12px;
}

.page-title{
font-size:26px;
}

.image-box img{
height:180px;
}

.product-name{
font-size:14px;
line-height:20px;
height:40px;
}

.price{
font-size:16px;
}

.old-price{
font-size:12px;
}

.discount{
font-size:10px;
}

.view-btn{
padding:10px;
font-size:13px;
}

.logo{
font-size:22px;
}

}
.bottom-bar{
position:fixed;
bottom:20px;
left:50%;
transform:translateX(-50%);
width:420px;
background:#fff;
display:flex;
border-radius:20px;
overflow:hidden;
z-index:99999;
box-shadow:0 4px 20px rgba(0,0,0,.18);
}

.bottom-btn{
flex:1;
padding:18px;
text-align:center;
font-size:18px;
font-weight:700;
cursor:pointer;
}

.popup{
position:fixed;
top:0;
left:-100%;
width:100vw;
height:100vh;
background:#fff;
z-index:999999;
transition:.3s;
overflow-y:auto;
}

.sort-popup{
position:fixed;
bottom:-100%;
left:0;
width:100%;
background:#fff;
z-index:999999;
transition:.4s;
padding:25px;
border-radius:25px 25px 0 0;
box-shadow:0 -5px 20px rgba(0,0,0,.2);
}

.sort-popup label{
display:block;
padding:18px 0;
font-size:20px;
border-bottom:1px solid #eee;
}

.sort-popup button{
width:100%;
padding:16px;
margin-top:20px;
background:#000;
color:#fff;
border:none;
border-radius:10px;
font-size:18px;
}
.popup h2{
font-size:28px;
margin-bottom:20px;
}

.popup h3{
font-size:18px;
margin:20px 0 10px;
color:#222;
}

.popup label{
display:flex;
align-items:center;
gap:10px;
padding:8px 0;
font-size:20px;
cursor:pointer;
}

.popup input[type="checkbox"]{
width:18px;
height:18px;
}

.popup hr{
border:none;
border-top:1px solid #eee;
margin:15px 0;
}

.filter-layout{
    display:flex;
    width:100%;
    height:calc(100vh - 160px);
    overflow:hidden;
    margin-top:0;   /* remove extra margin */
} 

.filter-left{
    width:35%;
    background:#f8f8f8;
    height:100%;
    overflow-y:auto;
    display:flex;
    flex-direction:column;
    position:relative;
}

.filter-left::after{
    content:"";
    position:absolute;
    top:0;
    right:0;
    width:2px;
    height:100%;
    background:#000;
}

.filter-left div{
padding:20px 22px;
font-size:20px;
border-bottom:1px solid #eee;
cursor:pointer;
margin:0;
}

.filter-left div.active{
background:#fff;
font-weight:bold;
}

.filter-right{
width:65%;
padding:25px;
overflow-y:auto;
background:#fff;
}

.tab-content{
font-size:18px;
}

.popup h2{
padding:20px;
font-size:28px;
font-weight:700;
border-bottom:1px solid #ddd;
}

.filter-buttons{
position:absolute;
bottom:0;
left:0;
width:100%;
display:flex;
gap:12px;
padding:15px;
background:#fff;
border-top:1px solid #ddd;
}

.filter-buttons button{
flex:1;
padding:16px;
border:none;
font-size:18px;
font-weight:600;
border-radius:8px;
cursor:pointer;
}

.filter-buttons button:first-child{
background:#fff;
border:1px solid #000;
}

.filter-buttons button:last-child{
background:#000;
color:#fff;
}
.filter-bottom{
display:flex;
gap:15px;
padding:20px;
position:sticky;
bottom:0;
background:#fff;
}

.reset-btn,
.apply-btn{
width:50%;
padding:18px;
border:none;
border-radius:10px;
font-size:20px;
font-weight:600;
cursor:pointer;
}

.reset-btn{
background:#f3f3f3;
color:#111;
}

.apply-btn{
background:#111;
color:#fff;
}   
.sort-popup{
position: fixed;
bottom: -100%;
left: 0;
width: 100%;
background: white;
padding: 20px;
box-shadow: 0 -4px 15px rgba(0,0,0,0.2);
transition: 0.3s;
z-index: 9999;
}   

.filter-header{
    display:flex;
    align-items:center;
    gap:15px;
    padding:20px;
    border-bottom:1px solid #ddd;
    background:#fff;
    height:70px;
}

.back-arrow{
font-size:28px;
font-weight:bold;
cursor:pointer;
}
.selected-filters{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin:20px 0;
}

.filter-tag{
    text-decoration:none;
    color:#000;
    background:#fff;
    border:1px solid #ddd;
    padding:8px 14px;
    border-radius:6px;
    font-weight:600;
}
.breadcrumb{
    font-size:14px;
    margin:20px 30px;
    color:#555;
    text-transform: uppercase;
}

.breadcrumb a{
    text-decoration:none;
    color:black;
    font-weight:500;
}

.breadcrumb span{
    color:#2874f0;
    font-weight:600;
}
</style>
</head>

<body>

<?php include 'header.php'; ?>
<!-- TITLE -->

<h1 class="page-title">
<?= strtoupper(!empty($cat) ? $cat : 'MEN') ?> COLLECTION
</h1>

<?php
$gender = $_GET['gender'] ?? 'men';
$cat = $_GET['cat'] ?? 'all';

/* save last selected category */
if($cat != 'all'){
    $_SESSION['last_cat'] = $cat;
}

$last_cat = $_SESSION['last_cat'] ?? '';
?>

<div class="breadcrumb">
    <a href="index.php">HOME</a> /

    <!-- MEN page -->
    <a href="<?= $gender ?>-products.php?cat=all">
        <?= strtoupper($gender) ?>
    </a>

    <!-- Last selected category -->
    <?php if($last_cat){ ?>
        /
        <a href="<?= $gender ?>-products.php?cat=<?= $last_cat ?>">
            <?= strtoupper(str_replace("-", " ", $last_cat)) ?>
        </a>
    <?php } ?>
</div>
<?php
if(!empty($_GET['occasion'])){

    $occasions = explode(",", $_GET['occasion']);

    foreach($occasions as $occ){

        $newOccasions = array_diff($occasions, [$occ]);

        $newUrl = "men-products.php?cat=".$cat;

        if(!empty($newOccasions)){
            $newUrl .= "&occasion=".implode(",", $newOccasions);
        }

        echo "<a href='$newUrl' class='filter-tag'>"
            .strtoupper($occ)." ✕</a>";
    }
}
?>
<div class="bottom-bar">

<div class="bottom-btn" onclick="openFilter()">
☰ Filters
</div>

<div class="bottom-btn" onclick="openSort()">
⇅ Sort By
</div>

</div>
<!-- PRODUCTS -->

<div class="grid">

<?php
if($result->num_rows > 0){

while($row = $result->fetch_assoc()){

$discount = 0;

if(!empty($row['old_price']) &&
$row['old_price'] > $row['price']){

$discount =
round(
(($row['old_price'] - $row['price'])
/$row['old_price']) * 100
);

}
?>

<div class="card"
onclick="window.location='product-detail.php?id=<?= $row['id'] ?>'">

<!-- IMAGE -->

<div class="image-box">

<div class="wishlist">
<a href="add-wishlist.php?id=<?= $row['id'] ?>">♡</a>
</div>

<img src="uploads/<?= $row['image'] ?>">

</div>

<!-- DETAILS -->

<div class="details">

<div class="product-name">
<?= htmlspecialchars($row['name']) ?>
</div>

<div class="price">

<?php if($discount > 0){ ?>

<span class="old-price">
₹<?= $row['old_price'] ?>
</span>

<?php } ?>

<span class="new-price">
₹<?= $row['price'] ?>
</span>

<?php if($discount > 0){ ?>

<span class="discount">
<?= $discount ?>% OFF
</span>

<?php } ?>

</div>

<div class="stock">
In Stock
</div>

<!-- BUTTON -->

<div class="buttons">

<button class="view-btn"
onclick="window.location='product-detail.php?id=<?= $row['id'] ?>'">

View Product

</button>

</div>

</div>
</div>

<?php
}}

else{

echo "
<h2 style='padding:20px'>
No Products Found
</h2>
";

}
?>

</div>
<div class="popup" id="filterPopup">
<form id="filterForm">
<div class="filter-header">
    <span onclick="goBack()" class="back-arrow">←</span>
    <h2>Filters</h2>
</div>

<div class="filter-layout">

    <!-- LEFT SIDE -->
    <div class="filter-left">
<div onclick="showTab('gender', this)">Gender</div>
<div onclick="showTab('category', this)">Category</div>
<div onclick="showTab('price', this)">Price</div>
<div onclick="showTab('colors', this)">Colors</div>
<div onclick="showTab('size', this)">Size</div>
<div onclick="showTab('discount', this)">Discount</div>
<div onclick="showTab('brand', this)">Brand</div>
<div onclick="showTab('occasion', this)">Occasion</div>

    </div>

    <!-- RIGHT SIDE -->

        <div class="filter-right">
<!-- GENDER -->
<div id="gender" class="tab-content">


<?php
$genderCount = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM products
WHERE gender='$gender'
AND category='$cat'
"));
?>

<label>
<input type="radio" name="gender" value="<?= $gender ?>" checked>
<?= ucfirst($gender) ?> (<?= $genderCount['total'] ?>)
</label>
</div>

<!-- CATEGORY -->
<div id="category" class="tab-content" style="display:none;">

<?php
$catQuery = mysqli_query($conn,"
SELECT category, COUNT(*) total
FROM products
WHERE gender='$gender'
AND category='$cat'
GROUP BY category
");
while($c = mysqli_fetch_assoc($catQuery)){
?>

<label>
<input type="checkbox" name="cat[]" value="<?= $c['category'] ?>" checked>

<?= ucfirst($c['category']) ?>
(<?= $c['total'] ?>)

</label>

<?php } ?>

</div>

<!-- PRICE -->
<div id="price" class="tab-content" style="display:none;">

<?php
$min = 0;
$max = 5000;

if(!empty($price)){
    $priceData = explode('-', $price);
    $min = $priceData[0];
    $max = $priceData[1] ?? 5000;
}
?>

<h3>₹<span id="minValue"><?= $min ?></span> - ₹<span id="maxValue"><?= $max ?></span></h3>

<div style="display:flex; gap:10px; margin:20px 0;">
    <input type="number" id="minPrice" value="<?= $min ?>" min="0" max="5000"
    style="width:50%; padding:12px; font-size:18px;">

    <input type="number" id="maxPrice" value="<?= $max ?>" min="0" max="5000"
    style="width:50%; padding:12px; font-size:18px;">
</div>

<input type="range" id="priceRangeMin"
min="0" max="5000" value="<?= $min ?>" style="width:100%;">

<input type="range" id="priceRangeMax"
min="0" max="5000" value="<?= $max ?>" style="width:100%;">

</div>
<div id="colors" class="tab-content" style="display:none;">

<?php
$colorQuery = mysqli_query($conn,"
SELECT color_name, COUNT(*) as total
FROM product_colors pc
JOIN products p ON p.id = pc.product_id
WHERE p.gender='$gender'
AND p.category='$cat'
GROUP BY color_name
");

if(!$colorQuery){
die(mysqli_error($conn));
}

while($clr = mysqli_fetch_assoc($colorQuery)){
?>

<label>
<input type="checkbox"
name="color[]"
value="<?= $clr['color_name'] ?>">

<span style="
width:20px;
height:20px;
border-radius:50%;
display:inline-block;
background:<?= strtolower($clr['color_name']) ?>;
border:1px solid #ccc;
margin-right:8px;
vertical-align:middle;
"></span>

<?= ucfirst($clr['color_name']) ?>

(<?= $clr['total'] ?>)

</label>
<br><br>

<?php } ?>

</div>
<div id="size" class="tab-content" style="display:none;">

<?php
$sizeQuery = mysqli_query($conn,"
SELECT ps.size, COUNT(*) as total
FROM product_sizes ps
JOIN products p ON p.id = ps.product_id
WHERE p.gender='$gender'
AND p.category='$cat'
GROUP BY ps.size
");

if(!$sizeQuery){
die(mysqli_error($conn));
}

while($s = mysqli_fetch_assoc($sizeQuery)){
?>

<label>
<input type="checkbox"
name="size[]"
value="<?= $s['size'] ?>">

<?= strtoupper($s['size']) ?>

(<?= $s['total'] ?>)

</label>
<br><br>

<?php } ?>

</div>


<div id="discount" class="tab-content" style="display:none;">

<?php
$discountQuery = mysqli_query($conn,"
SELECT discount, COUNT(*) total
FROM products
WHERE gender='$gender'
AND category='$cat'
GROUP BY discount
");

while($d = mysqli_fetch_assoc($discountQuery)){
?>

<label>
<input type="checkbox" name="discount[]" value="<?= $d['discount'] ?>">
<?= $d['discount'] ?>% OFF (<?= $d['total'] ?>)
</label><br><br>

<?php } ?>

</div>

<!-- BRAND -->
<div id="brand" class="tab-content" style="display:none;">

<?php
$brandQuery = mysqli_query($conn,"
SELECT brand, COUNT(*) total
FROM products
WHERE gender='$gender'
AND category='$cat'
GROUP BY brand
");

while($b = mysqli_fetch_assoc($brandQuery)){
?>

<label>
<input type="checkbox" name="brand[]" value="<?= $b['brand'] ?>">
<?= $b['brand'] ?> (<?= $b['total'] ?>)
</label>

<?php } ?>

</div>



<!-- OCCASION -->
<div id="occasion" class="tab-content" style="display:none;">

<?php
$occasionQuery = mysqli_query($conn,"
SELECT DISTINCT occasion, COUNT(*) as total
FROM products
WHERE gender='$gender'
AND category='$cat'
AND occasion!=''
GROUP BY occasion
ORDER BY occasion ASC
");

if(!$occasionQuery){
die(mysqli_error($conn));
}

while($o = mysqli_fetch_assoc($occasionQuery)){
?>

<label>
<input type="checkbox"
name="occasion[]"
value="<?= $o['occasion'] ?>">

<?= ucfirst($o['occasion']) ?>

(<?= $o['total'] ?>)

</label>
<br><br>

<?php } ?>
</div>
</div>
</div>

<div class="filter-bottom">

    <button class="reset-btn"
    onclick="window.location.href='men-products.php?cat=<?= $cat ?>&gender=<?= $gender ?>'">
        Reset
    </button>
<button type="button" class="apply-btn" onclick="applyFilter()">
    Apply
</button>
</div>

</form>
</div>  

<div class="sort-popup" id="sortPopup">

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
<h2>Sort By</h2>
<span onclick="closeSort()" style="font-size:35px;cursor:pointer;">×</span>
</div>

<label>
<input type="radio" name="sort" value="new"> Whats New
</label>

<label>
<input type="radio" name="sort" value="high"> Price High To Low
</label>

<label>
<input type="radio" name="sort" value="low"> Price Low To High
</label>

<label>
<input type="radio" name="sort" value="discount"> Best Discount
</label>

<button type="button" onclick="applySort()">Apply</button>

</div>
<?php include 'footer.php'; ?>
<script>

function openFilter(){
document.getElementById("filterPopup").style.left="0";
}

function closeFilter(){
document.getElementById("filterPopup").style.left="-100%";
}
function applyFilter(){

let params = new URLSearchParams();

/* CATEGORY */
let cats = [];
document.querySelectorAll("input[name='cat[]']:checked").forEach(function(el){
cats.push(el.value);
});
if(cats.length > 0){
params.set("cat", cats[0]);
}

/* PRICE */
let minPrice = document.getElementById("minPrice").value;
let maxPrice = document.getElementById("maxPrice").value;

if(
(minPrice !== "" && maxPrice !== "") &&
(minPrice != 0 || maxPrice != 5000)
){
params.set("price", minPrice + "-" + maxPrice);
}

/* COLOR */
let colors = [];
document.querySelectorAll("input[name='color[]']:checked").forEach(function(el){
colors.push(el.value);
});
if(colors.length > 0){
params.set("color", colors.join(","));
}

/* SIZE */
let sizes = [];
document.querySelectorAll("input[name='size[]']:checked").forEach(function(el){
sizes.push(el.value);
});
if(sizes.length > 0){
params.set("size", sizes.join(","));
}

/* DISCOUNT */
let discounts = [];
document.querySelectorAll("input[name='discount[]']:checked").forEach(function(el){
discounts.push(el.value);
});
if(discounts.length > 0){
params.set("discount", discounts.join(","));
}

/* BRAND */
let brands = [];
document.querySelectorAll("input[name='brand[]']:checked").forEach(function(el){
brands.push(el.value);
});
if(brands.length > 0){
params.set("brand", brands.join(","));
}

/* OCCASION */
let occasions = [];
document.querySelectorAll("input[name='occasion[]']:checked").forEach(function(el){
occasions.push(el.value);
});
if(occasions.length > 0){
params.set("occasion", occasions.join(","));
}

/* GENDER */
let gender = document.querySelector("input[name='gender']:checked");
if(gender){
params.set("gender", gender.value);
}

window.location.href = "men-products.php?" + params.toString();

}
function showTab(tabName, element){

document.querySelectorAll(".tab-content")
.forEach(function(tab){
tab.style.display = "none";
});

document.querySelectorAll(".filter-left div")
.forEach(function(item){
item.classList.remove("active");
});

document.getElementById(tabName).style.display = "block";

element.classList.add("active");

}
function openSort(){
document.getElementById("sortPopup").style.bottom="0";
}

function applySort(){

let sort =
document.querySelector('input[name="sort"]:checked');

if(sort){
window.location.href =
"men-products.php?gender=<?= $gender ?>&cat=<?= $cat ?>&sort=" + sort.value;
}

}

const minSlider = document.getElementById("priceRangeMin");
const maxSlider = document.getElementById("priceRangeMax");
const minInput = document.getElementById("minPrice");
const maxInput = document.getElementById("maxPrice");

if(minSlider && maxSlider){

minSlider.oninput = function(){
    minInput.value = this.value;
    document.getElementById("minValue").innerText = this.value;
}

maxSlider.oninput = function(){
    maxInput.value = this.value;
    document.getElementById("maxValue").innerText = this.value;
}

}
function openSort(){
document.getElementById("sortPopup").style.bottom = "0";
}

function closeSort(){
document.getElementById("sortPopup").style.bottom = "-100%";
}
function goBack(){
window.history.back();
}
</script>
</body>
</html>