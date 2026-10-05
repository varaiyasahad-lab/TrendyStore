  <?php
  session_start();
  include "db.php";

  

  $sql = "SELECT * FROM products WHERE best_seller=1";

if(isset($_GET['gender']) && $_GET['gender']!=""){
    $gender = mysqli_real_escape_string($conn,$_GET['gender']);
    $sql .= " AND gender='$gender'";
}

if(isset($_GET['category']) && $_GET['category']!=""){

    $cats = explode(",",$_GET['category']);
    $catList = [];

    foreach($cats as $cat){
        $catList[] = "'" . mysqli_real_escape_string($conn,$cat) . "'";
    }

    $sql .= " AND category IN (" . implode(",",$catList) . ")";
}

if(isset($_GET['min_price']) && $_GET['min_price']!=""){
    $min = (int)$_GET['min_price'];
    $sql .= " AND price >= $min";
}

if(isset($_GET['max_price']) && $_GET['max_price']!=""){
    $max = (int)$_GET['max_price'];
    $sql .= " AND price <= $max";
}



if(isset($_GET['sort'])){

    switch($_GET['sort']){

        case "high":
            $sql .= " ORDER BY price DESC";
            break;

        case "low":
            $sql .= " ORDER BY price ASC";
            break;

        case "discount":
            $sql .= " ORDER BY discount DESC";
            break;

        default:
            $sql .= " ORDER BY id DESC";
    }

}else{
    $sql .= " ORDER BY id DESC";
}

$result = mysqli_query($conn,$sql);

  
  $categories = mysqli_query($conn,"
SELECT category, COUNT(*) total
FROM products
GROUP BY category
ORDER BY total DESC
");

$menCount = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM products
WHERE best_seller=1
AND gender='men'
"))['total'];

$womenCount = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM products
WHERE best_seller=1
AND gender='women'
"))['total'];


$genderFilter = $_GET['gender'] ?? '';
$where = "";

if($genderFilter!=""){
    $where = "WHERE gender='$genderFilter'";
}

$categories = mysqli_query($conn,"
SELECT category,
COUNT(*) total
FROM products
WHERE best_seller=1
" . ($genderFilter != "" ? " AND gender='$genderFilter'" : "") . "
GROUP BY category
ORDER BY total DESC
");

if($genderFilter!=''){

$brands = mysqli_query($conn,"
SELECT brand,
COUNT(*) total
FROM products
WHERE best_seller=1
AND gender='$genderFilter'
AND brand!=''
GROUP BY brand
ORDER BY total DESC
");

}else{

$brands = mysqli_query($conn,"
SELECT brand,
COUNT(*) total
FROM products
WHERE best_seller=1
AND brand!=''
GROUP BY brand
ORDER BY total DESC
");

}
if($genderFilter!=''){

$occasions = mysqli_query($conn,"
SELECT occasion,
COUNT(*) total
FROM products
WHERE best_seller=1
AND gender='$genderFilter'
AND occasion!=''
GROUP BY occasion
ORDER BY total DESC
");

}else{

$occasions = mysqli_query($conn,"
SELECT occasion,
COUNT(*) total
FROM products
WHERE best_seller=1
AND occasion!=''
GROUP BY occasion
ORDER BY total DESC
");

}

$discounts = mysqli_query($conn,"
SELECT discount,
COUNT(*) total
FROM products
WHERE best_seller=1
AND discount IS NOT NULL
" . ($genderFilter!='' ? " AND gender='$genderFilter'" : "") . "
GROUP BY discount
ORDER BY discount DESC
");

$colors = mysqli_query($conn,"
SELECT pc.color_name,
COUNT(*) total
FROM product_colors pc
JOIN products p ON pc.product_id=p.id
WHERE p.best_seller=1
" . ($genderFilter!='' ? " AND p.gender='$genderFilter'" : "") . "
GROUP BY pc.color_name
ORDER BY total DESC
");

$sizes = mysqli_query($conn,"
SELECT ps.size,
COUNT(*) total
FROM product_sizes ps
JOIN products p ON ps.product_id=p.id
WHERE p.best_seller=1
" . ($genderFilter!='' ? " AND p.gender='$genderFilter'" : "") . "
GROUP BY ps.size
ORDER BY total DESC
");
$totalProducts = mysqli_num_rows($result);
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Best Sellers</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>

  *{
    margin:0;
    padding:0;
    box-sizing:border-box;
  }

  body{
    font-family:'Poppins',sans-serif;
    background:#f5f5f5;
    padding-top: 95px;
  }

 

  .top{
    width:100%;
    padding:18px;
    background:#111;
    color:#fff;
    text-align:center;
    font-size:38px;
    font-weight:900;
    letter-spacing:2px;
    box-shadow:0 4px 15px rgba(0,0,0,0.2);
  }



  .best-section{
    padding:25px;
  }



  .best-grid{
    display:grid;
    grid-template-columns:
    repeat(auto-fit,minmax(220px,1fr));

    gap:22px;
  }


  .best-link{
    text-decoration:none;
  }



  .best-card{
    background:#fff;
    border-radius:22px;
    overflow:hidden;
    position:relative;
    transition:.35s ease;

    box-shadow:
    0 8px 20px rgba(0,0,0,0.08);
  }

  .best-card:hover{
    transform:translateY(-6px);

    box-shadow:
    0 18px 35px rgba(0,0,0,0.16);
  }



  .best-card img{
    width:100%;
    height:220px;
    object-fit:contain;
    background:#f7f7f7;
    padding:14px;
    transition:.4s ease;
  }

  .best-card:hover img{
    transform:scale(1.06);
  }


  .best-card::before{
    content:'HOT';
    position:absolute;
    top:12px;
    left:12px;

    background:
    linear-gradient(135deg,#ff0000,#ff7300);

    color:#fff;

    padding:6px 12px;
    border-radius:30px;

    font-size:10px;
    font-weight:800;
    letter-spacing:1px;

    z-index:5;

    box-shadow:
    0 5px 15px rgba(255,0,0,0.3);
  }


  .best-card h3{
    font-size:18px;
    font-weight:700;
    color:#111;
    text-align:center;
    margin-top:14px;
    padding:0 10px;
  }



  .price{
    text-align:center;
    font-size:28px;
    font-weight:900;

    background:
    linear-gradient(90deg,#ff0000,#ff7300);

    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;

    margin-top:10px;
    margin-bottom:14px;
  }

  

  .btn{
    width:82%;
    margin:0 auto 18px;
    padding:11px;

    display:block;
    text-align:center;

    border-radius:50px;

    background:#111;
    color:#fff;

    text-decoration:none;

    font-size:12px;
    font-weight:800;
    letter-spacing:1px;

    transition:.3s;
  }

  .best-card:hover .btn{
    background:
    linear-gradient(90deg,#ff0000,#ff7300);
  }


  @media(max-width:768px){

  .top{
    font-size:28px;
    padding:16px;
  }

  .best-section{
    padding:12px;
  }

  .best-grid{
    grid-template-columns:repeat(2,1fr);
    gap:12px;
  }

  .best-card{
    border-radius:18px;
  }

  .best-card img{
    height:160px;
    padding:10px;
  }

  .best-card h3{
    font-size:14px;
  }

  .price{
    font-size:20px;
  }

  .btn{
    width:90%;
    padding:9px;
    font-size:10px;
  }

  }
  .bottom-bar{
      position:fixed;
      bottom:20px;
      left:50%;
      transform:translateX(-50%);
      display:flex;
      background:#fff;
      border-radius:22px;
      overflow:hidden;
      z-index:99999;
      box-shadow:0 4px 20px rgba(0,0,0,.15);
  }

  .bottom-btn{
      background:#fff;
      color:#111;
      border:none;
      padding:18px 45px;
      font-size:18px;
      font-weight:600;
      cursor:pointer;
  }

  .bottom-btn:first-child{
      border-right:1px solid #ddd;
  }
  .filter-panel{
      display:none;
      position:fixed;
      top:0;
      left:0;
      width:100%;
      height:100%;
      background:#fff;
      z-index:999999;
  }

  .filter-header{
      padding:20px;
      display:flex;
      gap:15px;
      align-items:center;
      border-bottom:1px solid #ddd;
  }

  .filter-header button{
      border:none;
      background:none;
      font-size:30px;
      cursor:pointer;
  }

  .filter-body{
      display:flex;
      height:100%;
  }

 .filter-left{
    width:35%;
    border-right:1px solid #ddd;
    height:calc(100vh - 170px);
    overflow-y:auto;
}

  .filter-left div{
    padding:18px 20px;
    border-bottom:1px solid #eee;
    cursor:pointer;
    font-weight:600;
    transition:.3s;
}

.filter-left div:hover{
    background:#f5f5f5;
    color:#ff5500;
}

.filter-left div.active{
background:#fff;
font-weight:700;
border-left:5px solid #111;
}

 .filter-right{
    width:65%;
    padding:20px;
    height:calc(100vh - 170px);
    overflow-y:auto;
}

.filter-footer{
    position:absolute;
    bottom:0;
    left:0;
    width:100%;
    display:flex;
}

.reset-btn{
    width:50%;
    border:none;
    background:#fff;
    padding:18px;
    font-size:18px;
    border-top:1px solid #ddd;
    cursor:pointer;
}

.apply-btn{
    width:50%;
    border:none;
    background:#111;
    color:#fff;
    padding:18px;
    font-size:18px;
    cursor:pointer;
}
.result-count{
    font-size:18px;
    font-weight:700;
    margin-bottom:20px;
    color:#111;
    background:#fff;
    padding:12px 18px;
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
}
.filter-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:14px 0;
    font-size:18px;
}

.filter-item input{
    width:24px;
    height:24px;
}

.count{
    color:#666;
    font-weight:500;
}
.color-row{
display:flex;
align-items:center;
gap:14px;
padding:12px 0;
font-size:18px;
}

.color-dot{
width:30px;
height:30px;
border-radius:50%;
border:1px solid #ddd;
display:inline-block;
}
#sortPanel{
    position: fixed;
    left: 0;
    bottom: -100%;
    width: 100%;
    background: #fff;
    z-index: 999999;
    border-radius: 25px 25px 0 0;
    transition: .4s ease;
    box-shadow: 0 -5px 25px rgba(0,0,0,.15);
    display:block;
    height:auto;
}

#sortPanel .filter-header{
    justify-content: space-between;
    border-bottom: 1px solid #eee;
}

  </style>

  </head>

  <body>

  <?php include 'header.php'; ?>

  


  
<div class="result-count">
    <?php echo $totalProducts; ?> Products Found
</div>


  <div class="best-section">

  <div class="best-grid">

  <?php while($row = $result->fetch_assoc()) { ?>

  <a href="product-detail.php?id=<?php echo $row['id']; ?>" class="best-link">

  <div class="best-card">

  <img src="uploads/<?php echo $row['image']; ?>">

  <h3>
  <?php echo $row['name']; ?>
  </h3>

  <div class="price">
  ₹<?php echo $row['price']; ?>
  </div>

  <div class="btn">
  VIEW PRODUCT
  </div>

  </div>

  </a>

  <?php } ?>

  </div>

  </div>
  <div class="bottom-bar">

      <button id="openFilter" class="bottom-btn">
          ☰ Filters
      </button>

      <button id="openSort" class="bottom-btn">
          ⇅ Sort By
      </button>

  </div>
  <div class="filter-panel" id="filterPanel">

      <div class="filter-header">
          <button id="closeFilter">←</button>
          <h2>Filters</h2>
      </div>

      <div class="filter-body">

          <div class="filter-left">

  <div onclick="showFilter('gender')">Gender</div>

  <div onclick="showFilter('category')">Category</div>

  <div onclick="showFilter('price')">Price</div>

  <div onclick="showFilter('color')">Colors</div>

  <div onclick="showFilter('size')">Size</div>

  <div onclick="showFilter('discount')">Discount</div>

  <div onclick="showFilter('brand')">Brand</div>

  <div onclick="showFilter('occasion')">Occasion</div>

  </div>

          <div class="filter-right" id="filterContent">

  </div>

      </div>

  <div class="filter-footer">

<button id="resetFilter" class="reset-btn">
Reset
</button>

<button id="applyFilter" class="apply-btn">
Apply Filter
</button>

</div>

</div>
  <div id="sortPanel">

      <div class="filter-header">
          <button class="close-filter" id="closeSort">✕</button>
          <h2>Sort By</h2>
      </div>

      <div style="padding:20px">

          <label>
              <input type="radio" name="sort" value="new">
              What's New
          </label>

          <br><br>

          <label>
              <input type="radio" name="sort" value="high">
              Price High To Low
          </label>

          <br><br>

          <label>
              <input type="radio" name="sort" value="low">
              Price Low To High
          </label>

          <br><br>

          <label>
              <input type="radio" name="sort" value="discount">
              Best Discount
          </label>
          </div>
  <br><br>

  <div style="display:flex;gap:10px;">

      <button id="resetSort"
      style="
      width:50%;
      padding:15px;
      border:1px solid #ddd;
      background:#fff;
      font-size:18px;
      cursor:pointer;">
          Reset
      </button>

      <button id="applySort"
      style="
      width:50%;
      padding:15px;
      border:none;
      background:#111;
      color:#fff;
      font-size:18px;
      cursor:pointer;">
          Apply
      </button>

  </div>
      </div>

  </div>

  <script>
    const menCount = <?= $menCount ?>;
const womenCount = <?= $womenCount ?>;

const categories = <?php
$arr=[];
while($c=mysqli_fetch_assoc($categories)){
    $arr[] = [
        "name"=>$c['category'],
        "count"=>$c['total']
    ];
}
echo json_encode($arr);
?>;

const brands = <?php
$arr=[];
while($b=mysqli_fetch_assoc($brands)){
    $arr[]=[
        "name"=>$b['brand'],
        "count"=>$b['total']
    ];

}
echo json_encode($arr);
?>;

const occasions = <?php
$arr=[];
while($o=mysqli_fetch_assoc($occasions)){
    $arr[]=[
        "name"=>$o['occasion'],
        "count"=>$o['total']
    ];
}
echo json_encode($arr);
?>;

const discounts = <?php
$arr=[];
while($d=mysqli_fetch_assoc($discounts)){
    $arr[]=[
        "name"=>$d['discount'],
        "count"=>$d['total']
    ];
}
echo json_encode($arr);
?>;

const colors = <?php
$arr=[];
while($c=mysqli_fetch_assoc($colors)){
    $arr[]=[
        "name"=>$c['color_name'],
        "count"=>$c['total']
    ];
}
echo json_encode($arr);
?>;

const sizes = <?php
$arr=[];
while($s=mysqli_fetch_assoc($sizes)){
    $arr[]=[
        "name"=>$s['size'],
        "count"=>$s['total']
    ];
}
echo json_encode($arr);
?>;
  document.getElementById("openFilter").onclick = function(){
      document.getElementById("filterPanel").style.display = "block";
  }

  document.getElementById("closeFilter").onclick = function(){
      document.getElementById("filterPanel").style.display = "none";
  }
 
 function openSort(){
    document.getElementById("sortPanel").style.bottom = "0";
}

function closeSort(){
    document.getElementById("sortPanel").style.bottom = "-100%";
}

document.getElementById("openSort").onclick = openSort;
document.getElementById("closeSort").onclick = closeSort;
  
document.getElementById("resetSort").onclick = function(){
    let url = new URL(window.location.href);
    url.searchParams.delete("sort");
    window.location.href = url.toString();
}

document.getElementById("applySort").onclick = function(){

    let sort = document.querySelector('input[name="sort"]:checked');

    if(sort){
        let url = new URL(window.location.href);
        url.searchParams.set("sort", sort.value);
        window.location.href = url.toString();
    }

}


  function showFilter(type){

let html='';

if(type=='gender'){

html=`
<label class="filter-item">
<input type="radio" name="gender" value="men">
Men (${menCount})
</label>

<label class="filter-item">
<input type="radio" name="gender" value="women">
Women (${womenCount})
</label>
`;

}

if(type=='category'){

categories.forEach(function(item){

html += `
<label class="filter-item">

<input type="checkbox"
value="${item.name}">

${item.name}
<span class="count">
(${item.count})
</span>

</label>
`;

});

}
if(type=='price'){

html=`
<h3>₹0 - ₹5000</h3><br>

<input
type="number"
id="minPrice"
value="${selectedMinPrice}"
placeholder="Min Price"
style="width:48%;padding:10px">

<input
type="number"
id="maxPrice"
value="${selectedMaxPrice}"
placeholder="Max Price"
style="width:48%;padding:10px">
`;

}

if(type=='color'){

colors.forEach(function(item){

html += `
<label class="color-row">

<input type="checkbox" value="${item.name}">

<span class="color-dot"
style="background:${item.name};">
</span>

${item.name}

<span class="count">
(${item.count})
</span>

</label>
`;

});

}

if(type=='size'){

sizes.forEach(function(item){

html += `
<label class="filter-item">

<input type="checkbox" value="${item.name}">

${item.name}

<span class="count">
(${item.count})
</span>

</label>
`;

});

}

if(type=='discount'){

discounts.forEach(function(item){

html += `
<label class="filter-item">
<input type="checkbox" value="${item.name}">
${item.name}% OFF
<span class="count">(${item.count})</span>
</label>
`;

});

}

if(type=='brand'){

brands.forEach(function(item){

html += `
<label class="filter-item">
<input type="checkbox" value="${item.name}">
${item.name}
<span class="count">(${item.count})</span>
</label>
`;

});

}

if(type=='occasion'){

occasions.forEach(function(item){

html += `
<label class="filter-item">
<input type="checkbox" value="${item.name}">
${item.name}
<span class="count">(${item.count})</span>
</label>
`;

});

}

document.getElementById("filterContent").innerHTML = html;

}
showFilter('gender');

document.getElementById("resetFilter").onclick=function(){

    document.querySelectorAll("#filterContent input").forEach(function(el){
        el.checked=false;
    });

};

let selectedGender = "";
let selectedCategories = [];
let selectedMinPrice = "";
let selectedMaxPrice = "";


document.getElementById("applyFilter").onclick=function(){

    let params=[];



    let minPrice=document.getElementById("minPrice");
    let maxPrice=document.getElementById("maxPrice");

    if(minPrice){
        selectedMinPrice=minPrice.value;
    }

    if(maxPrice){
        selectedMaxPrice=maxPrice.value;
    }

    if(selectedGender!=""){
        params.push("gender="+selectedGender);
    }

    if(selectedCategories.length>0){
        params.push("category="+selectedCategories.join(","));
    }

    if(selectedMinPrice!=""){
        params.push("min_price="+selectedMinPrice);
    }

    if(selectedMaxPrice!=""){
        params.push("max_price="+selectedMaxPrice);
    }

    let url="best-seller.php";

    if(params.length>0){
        url+="?"+params.join("&");
    }

    window.location=url;
};



document.addEventListener("change",function(e){

    if(e.target.name=="gender"){
        selectedGender = e.target.value;
    }

    if(e.target.type=="checkbox"){

        if(e.target.checked){

            if(!selectedCategories.includes(e.target.value)){
                selectedCategories.push(e.target.value);
            }

        }else{

            selectedCategories =
            selectedCategories.filter(
                item => item != e.target.value
            );

        }

    }

});
  </script>
  <?php include 'footer.php'; ?>

  </body>
  </html>