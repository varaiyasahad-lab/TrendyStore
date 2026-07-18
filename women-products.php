    <?php
    session_start();
    include "db.php";

    /* CATEGORY FILTER */

    $gender   = 'women';
    $cat      = $_GET['cat'] ?? 'all';
    $price    = $_GET['price'] ?? '';
    $color    = $_GET['color'] ?? '';
    $size     = $_GET['size'] ?? '';
    $discount = $_GET['discount'] ?? '';
    $brand    = $_GET['brand'] ?? '';
    $occasion = $_GET['occasion'] ?? '';
    $sort     = $_GET['sort'] ?? '';

    $sql = "SELECT * FROM products WHERE gender='$gender'";

    if(!empty($cat) && $cat != 'all'){
        $sql .= " AND category='$cat'";
    }

    if(!empty($price)){
        $priceData = explode('-', $price);
        $minPrice = (int)$priceData[0];
        $maxPrice = isset($priceData[1]) ? (int)$priceData[1] : 999999;

        $sql .= " AND price >= $minPrice AND price <= $maxPrice";
    }

    if($color != ''){
        $colorArray = explode(',', $color);
    $colorArray = array_map('strtolower', $colorArray);
    $colorValues = "'" . implode("','", $colorArray) . "'";
        $sql .= " AND id IN (
            SELECT product_id FROM product_colors
            WHERE LOWER(color_name) IN ($colorValues)
        )";
    }

    if($size != ''){
    $sizeArray = explode(',', $size);
    $sizeArray = array_map('strtoupper', $sizeArray);
    $sizeValues = "'" . implode("','", $sizeArray) . "'";
        $sql .= " AND id IN (
            SELECT product_id FROM product_sizes
            WHERE UPPER(size) IN ($sizeValues)
        )";
    }
     
    if($discount != ''){
    $discountArray = explode(',', $discount);
    $discountValues = "'" . implode("','", $discountArray) . "'";
    $sql .= " AND discount IN ($discountValues)";
}


if($brand != ''){
    $brandArray = explode(',', $brand);

    $brandArray = array_map(function($b){
        return trim(strtolower($b));
    }, $brandArray);

    $brandValues = "'" . implode("','", $brandArray) . "'";

    $sql .= " AND TRIM(LOWER(brand)) IN ($brandValues)";
}

    if($occasion != ''){
    $occasionArray = explode(',', $occasion);
    $occasionArray = array_map('strtolower', $occasionArray);
    $occasionValues = "'" . implode("','", $occasionArray) . "'";
        $sql .= " AND LOWER(occasion) IN ($occasionValues)";
    }

    if($sort == 'new'){
    $sql .= " ORDER BY id DESC";
}
elseif($sort == 'low'){
    $sql .= " ORDER BY price ASC";
}
elseif($sort == 'high'){
    $sql .= " ORDER BY price DESC";
}
elseif($sort == 'discount'){
    $sql .= " ORDER BY (old_price - price) DESC";
}
else{
    $sql .= " ORDER BY id DESC";
}

    $result = mysqli_query($conn,$sql);

    $filterWhere = " WHERE gender='$gender'";

if($cat != 'all'){
    $filterWhere .= " AND category='$cat'";
}


    /* FILTER DATA */

$colors = mysqli_query($conn,"
SELECT pc.color_name, COUNT(*) total
FROM product_colors pc
JOIN products p ON p.id = pc.product_id
$filterWhere
GROUP BY pc.color_name
");

$sizes = mysqli_query($conn,"
SELECT ps.size, COUNT(*) total
FROM product_sizes ps
JOIN products p ON p.id = ps.product_id
$filterWhere
GROUP BY ps.size
");

$brands = mysqli_query($conn,"
SELECT LOWER(TRIM(brand)) as brand, COUNT(*) as total
FROM products
$filterWhere
AND brand IS NOT NULL
AND TRIM(brand) != ''
GROUP BY LOWER(TRIM(brand))
");

$discounts = mysqli_query($conn,"
SELECT discount, COUNT(*) total
FROM products
$filterWhere
AND discount IS NOT NULL
AND discount != ''
GROUP BY discount
ORDER BY discount DESC
");

$occasions = mysqli_query($conn,"
SELECT occasion, COUNT(*) total
FROM products
$filterWhere
GROUP BY occasion
");

$categories = mysqli_query($conn,"
SELECT category, COUNT(*) total
FROM products
$filterWhere
GROUP BY category
");

$genderCounts = mysqli_query($conn,"
SELECT gender, COUNT(*) total
FROM products
WHERE gender='$gender'
AND category='$cat'
GROUP BY gender
");
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
    <?= strtoupper($cat ?: 'Women') ?> Collection
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
    color:#e10600;
    letter-spacing:2px;
    }

    /* GRID */

    .grid{
    display:grid;
    grid-template-columns:repeat(4,minmax(250px,1fr));
    gap:22px;
    padding:20px;
    align-items:start;
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
    color:#e10600;
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
    background:#e10600;
    color:#fff;
    transition:.3s;
    }

    .view-btn:hover{
    opacity:.9;
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

.filter-layout{
display:flex;
width:100%;
height:calc(100vh - 160px);
overflow:hidden;
}

.filter-left{
width:35%;
background:#f8f8f8;
overflow-y:auto;
position:relative;
border-right:none;
}

/* BLACK CENTER LINE */
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
padding:20px;
font-size:20px;
border-bottom:1px solid #eee;
cursor:pointer;
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

.filter-header{
display:flex;
align-items:center;
gap:15px;
padding:20px;
border-bottom:1px solid #ddd;
height:70px;
background:#fff;
}

.back-arrow{
font-size:28px;
cursor:pointer;
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
padding:16px;
border:none;
font-size:18px;
border-radius:10px;
cursor:pointer;
}

.reset-btn{
background:#f3f3f3;
}

.apply-btn{
background:#111;
color:#fff;
}

.sort-panel{
position:fixed;
left:0;
bottom:-100%;
width:100%;
background:#fff;
z-index:999999;
border-radius:25px 25px 0 0;
padding:20px;
box-shadow:0 -5px 20px rgba(0,0,0,.15);
transition:.3s;
}

.sort-panel.active{
bottom:0;
}

.sort-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.sort-header h3{
font-size:34px;
font-weight:700;
}

.close-sort{
font-size:35px;
cursor:pointer;
}

.sort-option{
padding:18px 0;
border-bottom:1px solid #eee;
font-size:20px;
display:flex;
align-items:center;
gap:10px;
}

.sort-btn{
width:100%;
margin-top:20px;
padding:18px;
background:#111;
color:#fff;
border:none;
border-radius:12px;
font-size:18px;
font-weight:600;
cursor:pointer;
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

    /* Bottom Bar */
    .bottom-bar{
    position:fixed;
    bottom:20px;
    left:50%;
    transform:translateX(-50%);
    width:450px;
    max-width:90%;
    background:#fff;
    border-radius:20px;
    display:flex;
    box-shadow:0 5px 20px rgba(0,0,0,.15);
    z-index:999999;
    overflow:hidden;
    }

    .bottom-btn{
    flex:1;
    padding:18px;
    text-align:center;
    font-size:20px;
    font-weight:600;
    cursor:pointer;
    }

    .bottom-btn:first-child{
    border-right:1px solid #ddd;
    }

    @media(max-width:768px){
    .bottom-bar{
    width:90%;
    bottom:15px;
    }
    .bottom-btn{
    padding:15px;
    font-size:18px;
    }
    }
    .color-box{
display:flex;
align-items:center;
gap:10px;
margin-bottom:35px;
}

.color-circle{
width:18px;
height:18px;
border-radius:50%;
border:1px solid #ccc;
display:inline-block;
}

.price-inputs{
display:flex;
gap:10px;
margin:20px 0;
}

.price-inputs input{
width:50%;
padding:15px;
font-size:18px;
border:1px solid #ccc;
border-radius:8px;
}

.range-box{
position:relative;
margin-top:20px;
}

.range-box input[type=range]{
width:100%;
margin:10px 0;
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
    <?= strtoupper($cat ?: 'Women') ?> COLLECTION
    </h1>
<?php
$gender = $_GET['gender'] ?? 'women';
$cat = $_GET['cat'] ?? 'all';

/* save last selected category */
if($cat != 'all'){
    $_SESSION['last_cat_women'] = $cat;
}

$last_cat = $_SESSION['last_cat_women'] ?? '';
?>

<div class="breadcrumb">
    <a href="index.php">HOME</a> /

    <!-- WOMEN page -->
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

        $newUrl = "women-products.php?cat=".$cat;

        if(!empty($newOccasions)){
            $newUrl .= "&occasion=".implode(",", $newOccasions);
        }

        echo "<a href='$newUrl' class='filter-tag'>"
            .strtoupper($occ)." ✕</a>";
    }
}
?>

<!-- PRODUCTS -->
<div class="grid">

<?php
if($result->num_rows > 0){

while($row = $result->fetch_assoc()){

$discountPercent = 0;

if(!empty($row['old_price']) && $row['old_price'] > $row['price']){
    $discountPercent = round(
        (($row['old_price'] - $row['price']) / $row['old_price']) * 100
    );
}
?>

<div class="card" onclick="window.location='product-detail.php?id=<?= $row['id'] ?>'">

    <div class="image-box">
        <div class="wishlist">
            <a href="add-wishlist.php?id=<?= $row['id'] ?>">♡</a>
        </div>

        <img src="uploads/<?= $row['image'] ?>">
    </div>

    <div class="details">

        <div class="product-name">
            <?= htmlspecialchars($row['name']) ?>
        </div>

        <div class="price">

            <?php if($discountPercent > 0){ ?>
            <span class="old-price">
                ₹<?= $row['old_price'] ?>
            </span>
            <?php } ?>

            <span class="new-price">
                ₹<?= $row['price'] ?>
            </span>

            <?php if($discountPercent > 0){ ?>
            <span class="discount">
                <?= $discountPercent ?>% OFF
            </span>
            <?php } ?>

        </div>

        <div class="stock">In Stock</div>

        <div class="buttons">
            <button class="view-btn">
                View Product
            </button>
        </div>

    </div>

</div>

<?php
}}

else{
echo "<h2 style='padding:20px'>No Products Found</h2>";
}
?>

</div>


<!-- FILTER PANEL -->

<div class="popup" id="filterPopup">

<div class="filter-header">
    <span onclick="closeFilter()" class="back-arrow">←</span>
    <h2>Filters</h2>
</div>

<div class="filter-layout">

<div class="filter-left">
    <div onclick="showTab('gender', this)" class="active">Gender</div>
    <div onclick="showTab('category', this)">Category</div>
    <div onclick="showTab('price', this)">Price</div>
    <div onclick="showTab('colors', this)">Colors</div>
    <div onclick="showTab('size', this)">Size</div>
    <div onclick="showTab('discount', this)">Discount</div>
    <div onclick="showTab('brand', this)">Brand</div>
    <div onclick="showTab('occasion', this)">Occasion</div>
</div>

<div class="filter-right">

    <div id="gender" class="tab-content">
        <?php while($g=mysqli_fetch_assoc($genderCounts)){ ?>
        <label>
            <input type="radio" name="gender" value="<?= $g['gender'] ?>">
            <?= ucfirst($g['gender']) ?> (<?= $g['total'] ?>)
        </label><br><br>
        <?php } ?>
    </div>

    <div id="category" class="tab-content" style="display:none;">
        <?php while($catRow=mysqli_fetch_assoc($categories)){ ?>
        <label>
            <input type="checkbox" name="cat[]" value="<?= $catRow['category'] ?>">
            <?= ucfirst($catRow['category']) ?> (<?= $catRow['total'] ?>)
        </label><br><br>
        <?php } ?>
    </div>

<div id="price" class="tab-content" style="display:none;">

<h3>₹<span id="minPriceText">0</span> - ₹<span id="maxPriceText">5000</span></h3>

<div class="price-inputs">
<input type="number" id="minPrice" value="0">
<input type="number" id="maxPrice" value="5000">
</div>

<div class="range-box">
<input type="range" id="rangeMin" min="0" max="5000" value="0">
<input type="range" id="rangeMax" min="0" max="5000" value="5000">
</div>

</div>

        <div id="colors" class="tab-content" style="display:none;">
<?php while($c=mysqli_fetch_assoc($colors)){ ?>
<label class="color-box">
<input type="checkbox" name="color[]" value="<?= $c['color_name'] ?>">

<span class="color-circle"
style="background:<?= strtolower($c['color_name']) ?>"></span>

<?= $c['color_name'] ?> (<?= $c['total'] ?>)
</label>
<?php } ?>
</div>

        <div id="size" class="tab-content" style="display:none;">
            <?php while($s=mysqli_fetch_assoc($sizes)){ ?>
            <label>
                <input type="checkbox" name="size[]" value="<?= $s['size'] ?>">
             <?= strtoupper($s['size']) ?> (<?= $s['total'] ?>)
            </label><br><br>
            <?php } ?>
        </div>

        <div id="discount" class="tab-content" style="display:none;">
<?php while($d=mysqli_fetch_assoc($discounts)){ ?>
<label>
    <input type="checkbox" name="discount[]" value="<?= $d['discount'] ?>">
    <?= $d['discount'] ?>% OFF (<?= $d['total'] ?>)
</label><br><br>
<?php } ?>
</div>

   <div id="brand" class="tab-content" style="display:none;">

<?php while($b = mysqli_fetch_assoc($brands)){ ?>
<label>
    <input type="checkbox" name="brand[]" value="<?= $b['brand'] ?>">
    <?= ucfirst($b['brand']) ?> (<?= $b['total'] ?>)
</label><br><br>
<?php } ?>

</div>

        <div id="occasion" class="tab-content" style="display:none;">
            <?php while($o=mysqli_fetch_assoc($occasions)){ ?>
            <label>
                <input type="checkbox" name="occasion[]" value="<?= $o['occasion'] ?>">
               <?= ucfirst($o['occasion']) ?> (<?= $o['total'] ?>)
            </label><br><br>
            <?php } ?>
        </div>

    </div>
</div>

<div class="filter-bottom">
    <button class="reset-btn">Reset</button>
    <button class="apply-btn" onclick="applyFilter()">Apply</button>
</div>

</div>


<!-- SORT PANEL -->

<div id="sortPanel" class="sort-panel">

<div class="sort-header">
    <h3>Sort By</h3>
    <span onclick="closeSort()" class="close-sort">✕</span>
</div>

<label class="sort-option">
    <input type="radio" name="sort" value="new"> Whats New
</label>

<label class="sort-option">
    <input type="radio" name="sort" value="high"> Price High To Low
</label>

<label class="sort-option">
    <input type="radio" name="sort" value="low"> Price Low To High
</label>

<label class="sort-option">
    <input type="radio" name="sort" value="discount"> Best Discount
</label>

<button onclick="applySort()" class="sort-btn">
Apply Sort
</button>

</div>


<!-- BOTTOM BAR -->

<div class="bottom-bar">

<div onclick="openFilter()" class="bottom-btn">
☰ Filters
</div>

<div onclick="openSort()" class="bottom-btn">
⇅ Sort By
</div>

</div>

<?php include 'footer.php'; ?>


<script>

function openFilter(){
document.getElementById("filterPopup").style.left="0";
}

function closeFilter(){
document.getElementById("filterPopup").style.left="-100%";
}
function openSort(){
document.getElementById("sortPanel").classList.add("active");
}

function closeSort(){
document.getElementById("sortPanel").classList.remove("active");
}

function applyFilter(){

let params = new URLSearchParams();

let selectedGender = document.querySelector("input[name='gender']:checked");

if(selectedGender){
params.set("gender", selectedGender.value);
}else{
params.set("gender","women");
}
params.set("cat","<?= $cat ?>");

let cats = [];
document.querySelectorAll("input[name='cat[]']:checked").forEach(el=>{
cats.push(el.value);
});

if(cats.length){
params.set("cat",cats.join(","));
}

let minPrice = document.getElementById("minPrice").value;
let maxPrice = document.getElementById("maxPrice").value;

params.set("price", minPrice + "-" + maxPrice);

let colors = [];
document.querySelectorAll("input[name='color[]']:checked").forEach(el=>{
colors.push(el.value);
});
if(colors.length) params.set("color",colors.join(","));

let sizes = [];
document.querySelectorAll("input[name='size[]']:checked").forEach(el=>{
sizes.push(el.value);
});
if(sizes.length) params.set("size",sizes.join(","));

let discounts = [];
document.querySelectorAll("input[name='discount[]']:checked").forEach(el=>{
    discounts.push(el.value);
});

if(discounts.length){
    params.set("discount", discounts.join(","));
}

let brands = [];
document.querySelectorAll("input[name='brand[]']:checked").forEach(el=>{
brands.push(el.value);
});
if(brands.length) params.set("brand",brands.join(","));

let occasions = [];
document.querySelectorAll("input[name='occasion[]']:checked").forEach(el=>{
occasions.push(el.value);
});
if(occasions.length) params.set("occasion",occasions.join(","));

window.location.href = "women-products.php?" + params.toString();
}

function applySort(){

let sort = document.querySelector("input[name='sort']:checked");

if(sort){
window.location.href =
"women-products.php?gender=women&cat=<?= $cat ?>&sort=" + sort.value;
}
}
window.onload = function(){
document.getElementById("filterPopup").style.left="-100%";
document.getElementById("sortPanel").classList.remove("active");
}

function showTab(tabName, element){

document.querySelectorAll(".tab-content").forEach(tab=>{
tab.style.display="none";
});

document.querySelectorAll(".filter-left div").forEach(item=>{
item.classList.remove("active");
});

document.getElementById(tabName).style.display="block";
element.classList.add("active");
}

let minInput = document.getElementById("minPrice");
let maxInput = document.getElementById("maxPrice");
let rangeMin = document.getElementById("rangeMin");
let rangeMax = document.getElementById("rangeMax");

function updatePrice(){

let min = parseInt(rangeMin.value);
let max = parseInt(rangeMax.value);

if(min > max){
[min,max] = [max,min];
}

minInput.value = min;
maxInput.value = max;

document.getElementById("minPriceText").innerText = min;
document.getElementById("maxPriceText").innerText = max;
}

rangeMin.oninput = updatePrice;
rangeMax.oninput = updatePrice;
</script>
    </body>
    </html>