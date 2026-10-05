<?php
session_start();
include "db.php";



$gender   = $_GET['gender'] ?? 'men';
$cat      = $_GET['cat'] ?? 'all';
$price    = $_GET['price'] ?? '';
$color    = $_GET['color'] ?? '';
$size     = $_GET['size'] ?? '';
$discount = $_GET['discount'] ?? '';
$brand    = $_GET['brand'] ?? '';
$occasion = $_GET['occasion'] ?? '';
$sort     = $_GET['sort'] ?? '';



$sql = "SELECT * FROM products WHERE gender='$gender'";




if($cat != '' && $cat != 'all'){

$catArray = explode(',', $cat);

$catValues = "'" .
implode("','", $catArray)
. "'";

$sql .= "
AND category IN ($catValues)
";

}


if($price != ''){

$priceData = explode('-',$price);

$minPrice = $priceData[0];
$maxPrice = $priceData[1];

$sql .= "
AND price BETWEEN
'$minPrice'
AND
'$maxPrice'
";

}



if($color != ''){

$colorArray = explode(',', strtolower($color));

$colorValues = "'" .
implode("','", $colorArray)
. "'";

$sql .= "
AND id IN (

SELECT product_id
FROM product_colors

WHERE LOWER(color_name)
IN ($colorValues)

)
";

}


if($size != ''){

$sizeArray = explode(',', $size);

$sizeValues = "'" .
implode("','", $sizeArray)
. "'";

$sql .= "
AND id IN (

SELECT product_id
FROM product_sizes

WHERE size IN ($sizeValues)

)
";

}




if($discount != ''){

$discountArray = explode(',', $discount);

$discountValues = "'" .
implode("','", $discountArray)
. "'";

$sql .= "
AND discount IN ($discountValues)
";

}




if($brand != ''){

$brandArray = explode(',', $brand);

$brandValues = "'" .
implode("','", $brandArray)
. "'";

$sql .= "
AND brand IN ($brandValues)
";

}





if($occasion != ''){

$occasionArray = explode(',', $occasion);

$occasionValues = "'" .
implode("','", $occasionArray)
. "'";

$sql .= "
AND occasion IN ($occasionValues)
";

}



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



$result = mysqli_query($conn,$sql);

$totalProducts = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="
width=device-width,
initial-scale=1,
maximum-scale=1,
user-scalable=no
">

<title>

<?= strtoupper($gender) ?>

Collection

</title>

<style>



*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
-webkit-tap-highlight-color:transparent;
}

body{
background:#f5f5f5;
overflow-x:hidden;
padding-bottom:140px;
padding-top:95px;
}



.header{
width:100%;
background:#2874f0;
color:#fff;
text-align:center;
padding:18px;
font-size:34px;
font-weight:700;
}


.total-products{
padding:18px;
font-size:28px;
font-weight:bold;
background:#fff;
margin-bottom:10px;
}



.products{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
padding:20px;
}



.product{
background:#fff;
border-radius:20px;
overflow:hidden;
box-shadow:0 2px 10px rgba(0,0,0,.08);
position:relative;
}

.product img{
width:100%;
height:340px;
object-fit:contain;
display:block;
}



.discount-tag{
position:absolute;
top:10px;
left:10px;
background:red;
color:#fff;
padding:6px 10px;
font-size:14px;
font-weight:bold;
border-radius:30px;
}
    
    .wishlist-icon{
position:absolute;
top:10px;
right:10px;
z-index:99;
}

.wishlist-icon a{
width:42px;
height:42px;
background:#fff;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
font-size:22px;
text-decoration:none;
box-shadow:0 2px 10px rgba(0,0,0,.15);
transition:.3s;
}

.wishlist-icon a:hover{
transform:scale(1.1);
}



.product-data{
padding:14px;
}

.brand{
font-size:15px;
font-weight:bold;
color:#777;
margin-bottom:6px;
}

.product h4{
font-size:24px;
margin-bottom:10px;
line-height:1.3;
height:62px;
overflow:hidden;
}

.price{
font-size:34px;
font-weight:bold;
}

.old-price{
font-size:20px;
color:#777;
text-decoration:line-through;
margin-left:8px;
}



.btn{
margin-top:14px;
background:#111;
color:#fff;
padding:14px;
text-align:center;
border-radius:14px;
font-size:18px;
font-weight:600;
transition:.3s;
}

.btn:hover{
background:#2874f0;
}
    
.link{
text-decoration:none;
color:#000;
display:block;
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
font-size:22px;
font-weight:700;
cursor:pointer;
}


.popup{
position:fixed;
top:0;
left:-100%;
width:100%;
height:100%;
background:#fff;
z-index:999999;
transition:.3s;
display:flex;
flex-direction:column;
}

.popup-header{
padding:22px;
display:flex;
align-items:center;
gap:20px;
border-bottom:1px solid #eee;
font-size:30px;
font-weight:bold;
}

.popup-body{
flex:1;
display:flex;
overflow:hidden;
}

/* LEFT */

.left-menu{
width:35%;
background:#f3f3f3;
overflow-y:auto;
}

.left-menu div{
padding:22px 16px;
border-bottom:1px solid #e5e5e5;
cursor:pointer;
font-size:22px;
}

.left-menu .active{
background:#fff;
font-weight:bold;
}



.right-data{
width:65%;
padding:22px;
overflow-y:auto;
}

.filter-box{
display:none;
}

.filter-box.active{
display:block;
}


.check-box{
display:flex;
align-items:center;
gap:14px;
margin-bottom:24px;
font-size:24px;
cursor:pointer;
}

.check-box input{
width:24px;
height:24px;
}



.color-circle{
width:28px;
height:28px;
border-radius:50%;
border:1px solid #ccc;
}



.popup-footer{
display:flex;
gap:10px;
padding:15px;
border-top:1px solid #ddd;
}

.reset-btn{
width:40%;
height:56px;
border:1px solid #aaa;
background:#fff;
border-radius:12px;
font-size:20px;
cursor:pointer;
}

.apply-btn{
width:60%;
height:56px;
background:#000;
color:#fff;
border:none;
border-radius:12px;
font-size:20px;
cursor:pointer;
}



.sort-popup{
position:fixed;
bottom:-100%;
left:0;
width:100%;
background:#fff;
z-index:999999;
border-radius:24px 24px 0 0;
transition:.3s;
padding:22px;
box-shadow:0 -5px 20px rgba(0,0,0,.2);
}

.sort-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
font-size:28px;
font-weight:bold;
}

.sort-item{
display:flex;
align-items:center;
gap:12px;
padding:16px 0;
font-size:24px;
border-bottom:1px solid #eee;
cursor:pointer;
}

.sort-item input{
width:22px;
height:22px;
}

.sort-apply{
width:100%;
background:#000;
color:#fff;
border:none;
padding:16px;
border-radius:14px;
font-size:22px;
margin-top:20px;
cursor:pointer;
}


@media(max-width:576px){

.header{
font-size:22px;
padding:15px;
}

.total-products{
font-size:18px;
padding:14px;
}

.products{
grid-template-columns:repeat(2,1fr);
gap:10px;
padding:10px;
}

.product img{
height:190px;
}

.product-data{
padding:10px;
}

.brand{
font-size:12px;
}

.product h4{
font-size:15px;
min-height:40px;
}



.btn{
font-size:14px;
padding:10px;
}

.bottom-bar{
width:100%;
left:0;
bottom:0;
transform:none;
border-radius:0;
}

.bottom-btn{
font-size:16px;
padding:15px;
}

.popup-header{
font-size:22px;
padding:16px;
}

.left-menu div{
font-size:15px;
padding:18px 12px;
}

.check-box{
font-size:16px;
margin-bottom:18px;
}

.sort-header{
font-size:22px;
}

.sort-item{
font-size:16px;
}

}
.range-price{
padding:10px;
}

.range-values{
font-size:22px;
font-weight:bold;
margin-bottom:20px;
}

.price-inputs{
display:flex;
gap:10px;
margin-bottom:20px;
}

.price-inputs input{
width:50%;
height:46px;
border:1px solid #ccc;
border-radius:10px;
padding:10px;
font-size:18px;
outline:none;
}

.range-price input[type=range]{
width:100%;
margin-bottom:20px;
accent-color:#000;
height:6px;
}
</style>

</head>

<body>
<?php include 'header.php'; ?>





<div class="total-products">

<?= $totalProducts ?>

Products

</div>


<div class="products">

<?php while($p = mysqli_fetch_assoc($result)){ ?>

<?php

$oldPrice = 0;

if($p['discount'] > 0){

$oldPrice =
round(
$p['price'] +
($p['price']*$p['discount']/100)
);

}

?>

<div class="product">
    
 <?php
$isWish = 0;

if(isset($_SESSION['user_id'])){

$wishCheck = mysqli_query($conn,"
SELECT *
FROM wishlist
WHERE user_id='".$_SESSION['user_id']."'
AND product_id='".$p['id']."'
");

$isWish = mysqli_num_rows($wishCheck);

}
  
?>
   <div class="wishlist-icon">

<a href="
<?= isset($_SESSION['user_id'])
? 'add-wishlist.php?id='.$p['id']
: 'auth.php'
?>
">

<?= $isWish > 0 ? '❤️' : '🤍' ?>

</a>

</div>
    
    
<?php if($p['discount'] > 0){ ?>

<div class="discount-tag">

<?= $p['discount'] ?>% OFF

</div>

<?php } ?>

<a href="product-detail.php?id=<?= $p['id'] ?>"
class="link">

<img src="uploads/<?= $p['image'] ?>">

<div class="product-data">

<div class="brand">

TRENDY

</div>

<h4>

<?= $p['name'] ?>

</h4>

<div class="price">

₹<?= $p['price'] ?>

<?php if($oldPrice > 0){ ?>

<span class="old-price">

₹<?= $oldPrice ?>

</span>

<?php } ?>

</div>

<div class="btn">

View Product

</div>

</div>

</a>

</div>

<?php } ?>

</div>



<div class="bottom-bar">

<div class="bottom-btn"
onclick="openFilter()">

☰ Filters

</div>

<div class="bottom-btn"
onclick="openSort()">

⇅ Sort By

</div>

</div>



<div class="popup"
id="filterPopup">

<div class="popup-header">

<span onclick="closeFilter()">

←

</span>

Filters

</div>

<div class="popup-body">



<div class="left-menu">

<div class="active">Gender</div>
<div>Category</div>
<div>Price</div>
<div>Colors</div>
<div>Size</div>
<div>Discount</div>
<div>Brand</div>
<div>Occasion</div>   
    

</div>



<div class="right-data">


<div class="filter-box active">

<label class="check-box">

<input type="radio"
name="gender"
value="men"
<?= $gender=='men'?'checked':'' ?>

>

Men
(<?= mysqli_num_rows(mysqli_query($conn,"
SELECT id FROM products
WHERE gender='men'
")) ?>)

</label>

<label class="check-box">

<input type="radio"
name="gender"
value="women"
<?= $gender=='women'?'checked':'' ?>

>

    Women
(<?= mysqli_num_rows(mysqli_query($conn,"
SELECT id FROM products
WHERE gender='women'
")) ?>)

</label>

</div>



<div class="filter-box">

<?php

if($gender == "men"){

$menCategories = [
"Tshirts",
"Shirts",
"Jeans",
"Trackpants",
"Nightwear",
"Hoodies",
"Pants",
"Polos"
];

foreach($menCategories as $menCat){

$countQuery = mysqli_query($conn,"
SELECT COUNT(*) as total
FROM products
WHERE gender='men'
AND category='$menCat'
");

$countData = mysqli_fetch_assoc($countQuery);

?>
  
<label class="check-box">

<input type="checkbox"
name="category[]"
value="<?= $menCat ?>"
<?= ($cat!='all' && in_array($menCat, explode(',', $cat))) ? 'checked' : '' ?>

>

<?= $menCat ?>

(<?= $countData['total'] ?>)


</label>

<?php
}

}else{

$womenCategories = [
"Tshirts",
"Shirts",
"Dresses",
"Jeans",
"Trackpants",
"Nightwear",
"Hoodies",
"Tops"
];

foreach($womenCategories as $womenCat){

$countQuery = mysqli_query($conn,"
SELECT COUNT(*) as total
FROM products
WHERE gender='women'
AND category='$womenCat'
");

$countData = mysqli_fetch_assoc($countQuery);

?>

<label class="check-box">

<input type="checkbox"
name="category[]"
value="<?= $womenCat ?>"
<?= ($cat!='all' && in_array($womenCat, explode(',', $cat))) ? 'checked' : '' ?>

>

<?= $womenCat ?>

(<?= $countData['total'] ?>)


</label>

<?php
}

}
?>

</div>


<div class="filter-box">

<div class="range-price">

<div class="range-values">

₹<span id="minPriceText">0</span>

-

₹<span id="maxPriceText">5000</span>

</div>

<div class="price-inputs">

<input type="number"
id="minInput"
placeholder="Min"
value="<?= $price!='' ? explode('-',$price)[0] : 0 ?>">

<input type="number"
id="maxInput"
placeholder="Max"
value="<?= $price!='' ? explode('-',$price)[1] : 5000 ?>">

</div>

<input type="range"
id="minPrice"
min="0"
max="5000"
value="<?= $price!='' ? explode('-',$price)[0] : 0 ?>"
step="100">

<input type="range"
id="maxPrice"
min="0"
max="5000"
value="<?= $price!='' ? explode('-',$price)[1] : 5000 ?>"
step="100">

</div>

</div>



<div class="filter-box">

<?php

$clr = mysqli_query($conn,"
SELECT pc.color_name,
COUNT(*) as total
FROM product_colors pc
JOIN products p
ON pc.product_id = p.id
WHERE p.gender='$gender'
GROUP BY pc.color_name
");

while($row = mysqli_fetch_assoc($clr)){

?>

<label class="check-box">

<input type="checkbox"
name="color[]"
value="<?= $row['color_name'] ?>"
<?= ($color!='' && in_array($row['color_name'], explode(',', $color))) ? 'checked' : '' ?>

>

<div class="color-circle"
style="
background:
<?= strtolower($row['color_name']) ?>
">
</div>

<?= ucfirst($row['color_name']) ?>

(<?= $row['total'] ?>)

</label>

<?php } ?>

</div>
    
    
 

<div class="filter-box">

<?php

$sizes = mysqli_query($conn,"
SELECT ps.size,
COUNT(*) as total
FROM product_sizes ps
JOIN products p
ON ps.product_id = p.id
WHERE p.gender='$gender'
GROUP BY ps.size
");

while($row = mysqli_fetch_assoc($sizes)){

?>

<label class="check-box">

<input type="checkbox"
name="size[]"
value="<?= $row['size'] ?>"
<?= ($size!='' && in_array($row['size'], explode(',', $size))) ? 'checked' : '' ?>

>

<?= strtoupper($row['size']) ?>

(<?= $row['total'] ?>)

</label>

<?php } ?>

</div>



<div class="filter-box">

<label class="check-box">

<input type="checkbox"
name="discount[]"
value="10"
<?= ($discount!='' && in_array('10', explode(',', $discount))) ? 'checked' : '' ?>

>

10% OFF

</label>

<label class="check-box">

<input type="checkbox"
name="discount[]"
value="20"
<?= ($discount!='' && in_array('20', explode(',', $discount))) ? 'checked' : '' ?>

>

20% OFF

</label>

<label class="check-box">

<input type="checkbox"
name="discount[]"
value="50"
<?= ($discount!='' && in_array('50', explode(',', $discount))) ? 'checked' : '' ?>

>

50% OFF

</label>

</div>


   

<div class="filter-box">

<?php

$brands = mysqli_query($conn,"
SELECT brand,
COUNT(*) as total
FROM products
WHERE gender='$gender'
GROUP BY brand
");

while($row = mysqli_fetch_assoc($brands)){

?>

<label class="check-box">

<input type="checkbox"
name="brand[]"
value="<?= $row['brand'] ?>"
<?= ($brand!='' && in_array($row['brand'], explode(',', $brand))) ? 'checked' : '' ?>

>

<?= $row['brand'] != '' 
? ucfirst($row['brand']) 
: 'Other' ?>
(<?= $row['total'] ?>)
</label>

<?php } ?>

</div>
    
   

<div class="filter-box">

<?php

$occasions = mysqli_query($conn,"
SELECT occasion,
COUNT(*) as total
FROM products
WHERE gender='$gender'
GROUP BY occasion
");

while($row = mysqli_fetch_assoc($occasions)){

?>

<label class="check-box">

<input type="checkbox"
name="occasion[]"
value="<?= $row['occasion'] ?>"
<?= ($occasion!='' && in_array($row['occasion'], explode(',', $occasion))) ? 'checked' : '' ?>

>

<?= $row['occasion'] != '' 
? ucfirst($row['occasion']) 
: 'Other' ?>

(<?= $row['total'] ?>)

</label>

<?php } ?>

</div>
    </div>

</div>

    
<div class="popup-footer">

<button class="reset-btn"
onclick="
window.location=
'category.php?gender=<?= $gender ?>'
">

Reset

</button>

<button class="apply-btn"
onclick="applyFilters()">

Apply Filter

</button>

</div>

</div>



<div class="sort-popup"
id="sortPopup">

<div class="sort-header">

Sort By

<span onclick="closeSort()">

✕

</span>

</div>

<label class="sort-item">

<input type="radio"
name="sort"
value="latest">

Whats New

</label>

<label class="sort-item">

<input type="radio"
name="sort"
value="high">

Price High To Low

</label>

<label class="sort-item">

<input type="radio"
name="sort"
value="low">

Price Low To High

</label>

<label class="sort-item">

<input type="radio"
name="sort"
value="discount">

Best Discount

</label>

<button class="sort-apply"
onclick="applySort()">

Apply

</button>

</div>

<script>



function openFilter(){

document
.getElementById("filterPopup")
.style.left = "0";

}

function closeFilter(){

document
.getElementById("filterPopup")
.style.left = "-100%";

}



function openSort(){

document
.getElementById("sortPopup")
.style.bottom = "0";

}

function closeSort(){

document
.getElementById("sortPopup")
.style.bottom = "-100%";

}



const menu =
document.querySelectorAll(
".left-menu div"
);

const boxes =
document.querySelectorAll(
".filter-box"
);

menu.forEach((item,index)=>{

item.onclick = ()=>{

document
.querySelector(
".left-menu .active"
)
.classList.remove("active");

item.classList.add("active");

boxes.forEach(box=>{

box.classList.remove("active");

});

boxes[index]
.classList.add("active");

};

});



function applyFilters(){

let gender =
document.querySelector(
'input[name="gender"]:checked'
);

let categories =
document.querySelectorAll(
'input[name="category[]"]:checked'
);

let minPrice =
document.getElementById(
'minPrice'
);

let maxPrice =
document.getElementById(
'maxPrice'
);

let color =
document.querySelectorAll(
'input[name="color[]"]:checked'
);


let sizes =
document.querySelectorAll(
'input[name="size[]"]:checked'
);

let discount =
document.querySelectorAll(
'input[name="discount[]"]:checked'
);
let brand =
document.querySelectorAll(
'input[name="brand[]"]:checked'
);

let occasion =
document.querySelectorAll(
'input[name="occasion[]"]:checked'
);
    
    
let url = "category.php?";



if(gender){

url +=
"gender="+gender.value;

}else{

url +=
"gender=men";

}



let catArray = [];

categories.forEach((cat)=>{

catArray.push(cat.value);

});

if(catArray.length > 0){

url +=
"&cat="+catArray.join(',');

}else{

url +=
"&cat=all";

}


url +=
"&price="+
minPrice.value+
"-"+
maxPrice.value;



let colorArray = [];

color.forEach((clr)=>{

colorArray.push(clr.value);

});

if(colorArray.length > 0){

url +=
"&color="+colorArray.join(',');

}



let sizeArray = [];

sizes.forEach((s)=>{

sizeArray.push(s.value);

});

if(sizeArray.length > 0){

url +=
"&size="+sizeArray.join(',');

}



let discountArray = [];

discount.forEach((d)=>{

discountArray.push(d.value);

});

if(discountArray.length > 0){

url +=
"&discount="+discountArray.join(',');

}
   


let brandArray = [];

brand.forEach((b)=>{

brandArray.push(b.value);

});

if(brandArray.length > 0){

url +=
"&brand="+brandArray.join(',');

}



let occasionArray = [];

occasion.forEach((o)=>{

occasionArray.push(o.value);

});

if(occasionArray.length > 0){

url +=
"&occasion="+occasionArray.join(',');

}
window.location = url;

}

    


function applySort(){

let sort =
document.querySelector(
'input[name="sort"]:checked'
);

let url =
window.location.pathname +
window.location.search;

if(sort){

if(url.includes("&sort=")){

url =
url.replace(
/&sort=[^&]*/,
"&sort="+sort.value
);

}else{

url +=
"&sort="+sort.value;

}

}

window.location = url;

}
const minSlider =
document.getElementById(
'minPrice'
);

const maxSlider =
document.getElementById(
'maxPrice'
);

const minText =
document.getElementById(
'minPriceText'
);

const maxText =
document.getElementById(
'maxPriceText'
);

const minInput =
document.getElementById(
'minInput'
);

const maxInput =
document.getElementById(
'maxInput'
);

if(minSlider && maxSlider){

function updatePrice(){

minText.innerText =
minSlider.value;

maxText.innerText =
maxSlider.value;

minInput.value =
minSlider.value;

maxInput.value =
maxSlider.value;

}

updatePrice();

minSlider.oninput = updatePrice;

maxSlider.oninput = updatePrice;

minInput.oninput = ()=>{

if(
parseInt(minInput.value)
<
parseInt(maxSlider.value)
){

minSlider.value =
minInput.value;

updatePrice();

}

}

maxInput.oninput = ()=>{

if(
parseInt(maxInput.value)
>
parseInt(minSlider.value)
){

maxSlider.value =
maxInput.value;

updatePrice();

}

}

}

</script>
<?php include 'footer.php'; ?>
</body>
</html>