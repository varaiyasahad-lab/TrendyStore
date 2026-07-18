    <?php
    session_start();
    include "db.php";

    /* Category Filter */

    $gender   = $_GET['type'] ?? 'men';
    $cat      = $_GET['cat'] ?? 'all';
    $price    = $_GET['price'] ?? '';
    $color    = $_GET['color'] ?? '';
    $size     = $_GET['size'] ?? '';
    $discount = $_GET['discount'] ?? '';
    $brand    = $_GET['brand'] ?? '';
    $occasion = $_GET['occasion'] ?? '';
    $sort     = $_GET['sort'] ?? '';

    $sql = "SELECT * FROM products WHERE gender='$gender'";

    /* CATEGORY */

    if($cat != '' && $cat != 'all'){

        $catArray = explode(',', $cat);

        $catValues = "'" . implode("','", $catArray) . "'";

        $sql .= " AND category IN ($catValues)";
    }

    /* PRICE RANGE */

    if($price != ''){

        $priceData = explode('-', $price);

        $minPrice = $priceData[0];
        $maxPrice = $priceData[1];

        $sql .= " AND price BETWEEN '$minPrice' AND '$maxPrice'";
    }

    /* COLOR */

    if($color != ''){

        $colorArray = explode(',', strtolower($color));

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

        $sizeArray = explode(',', $size);

        $sizeValues = "'" . implode("','", $sizeArray) . "'";

        $sql .= "
        AND id IN (
            SELECT product_id
            FROM product_sizes
            WHERE size IN ($sizeValues)
        )";
    }

    /* DISCOUNT */

    if($discount != ''){

        $discountArray = explode(',', $discount);

        $discountValues = "'" . implode("','", $discountArray) . "'";

        $sql .= " AND discount IN ($discountValues)";
    }

    /* BRAND */

    if($brand != ''){

        $brandArray = explode(',', $brand);

        $brandValues = "'" . implode("','", $brandArray) . "'";

        $sql .= " AND brand IN ($brandValues)";
    }

    /* OCCASION */

    if($occasion != ''){

        $occasionArray = explode(',', $occasion);

        $occasionValues = "'" . implode("','", $occasionArray) . "'";

        $sql .= " AND occasion IN ($occasionValues)";
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

    $query = mysqli_query($conn, $sql);

    $totalProducts = mysqli_num_rows($query);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Products</title>

    <style>

    body{
        margin:0;
        font-family:Arial, sans-serif;
        background:#f8f8f8;
        padding-top:90px;
    }

    .container{
        width:95%;
        margin:auto;
        padding:20px 0;
    }

    .page-title{
        text-align:center;
        font-size:35px;
        margin-bottom:30px;
    }

    .back-btn{
        display:inline-block;
        margin-bottom:25px;
        text-decoration:none;
        color:#fff;
        background:#e10600;
        padding:10px 20px;
        border-radius:30px;
        font-weight:bold;
    }

    .product-grid{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:25px;
    }

    .product-card{
        background:#fff;
        border-radius:15px;
        overflow:hidden;
        box-shadow:0 8px 20px rgba(0,0,0,0.08);
        transition:0.3s;
        text-align:center;
        text-decoration:none;
        color:#000;
    }

    .product-card:hover{
        transform:translateY(-8px);
        box-shadow:0 15px 30px rgba(0,0,0,0.15);
    }

    .product-card img{
        width:100%;
        height:280px;
        object-fit:contain;
        background:#fff;
    }

    .product-card h3{
        margin:15px 10px 8px;
        font-size:18px;
    }

    .product-card p{
        color:#e10600;
        font-size:22px;
        font-weight:bold;
        margin-bottom:20px;
    }

    /* Mobile */

    @media(max-width:768px){
        .product-grid{
            grid-template-columns:repeat(2,1fr);
            gap:15px;
        }

        .product-card img{
            height:180px;
        }

        .page-title{
            font-size:24px;
        }
    }
    /* ===== BOTTOM BAR ===== */

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

    /* Mobile */

    @media(max-width:576px){

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

    }
    /* ===== FILTER POPUP ===== */

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

    /* LEFT MENU */

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

    /* RIGHT DATA */

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

    /* CHECKBOX */

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

    /* FOOTER */

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
    /* ===== SORT POPUP ===== */

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
    .color-box{
        display:flex;
        align-items:center;
        gap:15px;
    }

    .color-circle{
        width:28px;
        height:28px;
        border-radius:50%;
        border:2px solid #ddd;
        display:inline-block;
    }
    /* PRICE RANGE */

    .price-inputs{
        display:flex;
        gap:15px;
        margin:20px 0;
    }

    .price-inputs input{
        width:100%;
        height:48px;
        border:1px solid #ddd;
        border-radius:10px;
        padding:10px;
        font-size:20px;
        outline:none;
    }

    .slider-box{
        margin-top:20px;
    }

    .slider-box input{
        width:100%;
        margin:10px 0;
        cursor:pointer;
    }
    </style>
    </head>
    <body>

    <?php include "header.php"; ?>

    <div class="container">

        <h1 class="page-title">Popular Products</h1>

        <a href="index.php" class="back-btn">⬅ Back to Home</a>

        <div class="product-grid">

            <?php
            if(mysqli_num_rows($query) > 0){
                while($row = mysqli_fetch_assoc($query)){
            ?>
            
            <a href="product-detail.php?id=<?php echo $row['id']; ?>" class="product-card">
                <img src="uploads/<?php echo $row['image']; ?>" alt="">
                <h3><?php echo $row['name']; ?></h3>
                <p>₹<?php echo $row['price']; ?></p>
            </a>

            <?php
                }
            }else{
                echo "<p>No products found.</p>";
            }
            ?>

        </div>

    </div>
    <!-- BOTTOM BAR -->

    <div class="bottom-bar">

        <div class="bottom-btn" onclick="openFilter()">
            ☰ Filters
        </div>

        <div class="bottom-btn" onclick="openSort()">
            ⇅ Sort By
        </div>

    </div>
    <!-- FILTER POPUP -->

    <div class="popup" id="filterPopup">

        <div class="popup-header">
            <span onclick="closeFilter()">←</span>
            Filters
        </div>

        <div class="popup-body">

            <!-- LEFT MENU -->
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
            <!-- RIGHT DATA -->

            <div class="right-data">
    
            <!-- GENDER -->
<div class="filter-box active">

<?php

$menResult = mysqli_query($conn,"
    SELECT COUNT(*) as total
    FROM products
    WHERE gender='men'
    AND popular='1'
");

$womenResult = mysqli_query($conn,"
    SELECT COUNT(*) as total
    FROM products
    WHERE gender='women'
    AND popular='1'
");

$menCount = mysqli_fetch_assoc($menResult);
$womenCount = mysqli_fetch_assoc($womenResult);

?>

<label class="check-box">

    <input type="radio"
    name="gender"
    value="men"
    <?= ($gender == 'men') ? 'checked' : ''; ?>>

    Men (<?= $menCount['total']; ?>)

</label>

<label class="check-box">

    <input type="radio"
    name="gender"
    value="women"
    <?= ($gender == 'women') ? 'checked' : ''; ?>>

    Women (<?= $womenCount['total']; ?>)

</label>

</div>

    <!-- CATEGORY -->
    <div class="filter-box">
    <?php
    $cats = mysqli_query($conn,"
    SELECT category, COUNT(*) total
    FROM products
    WHERE gender='$gender'
    GROUP BY category
    ");
    while($row=mysqli_fetch_assoc($cats)){
    ?>
    <label class="check-box">
    <input type="checkbox" name="category[]" value="<?= $row['category']; ?>">
    <?= ucfirst($row['category']); ?> (<?= $row['total']; ?>)
    </label>
    <?php } ?>
    </div>

    <!-- PRICE -->
    <div class="filter-box">

        <h3 id="priceRange">₹0 - ₹5000</h3>

        <div class="price-inputs">

            <input type="number"
            id="minPrice"
            value="0"
            min="0"
            max="5000">

            <input type="number"
            id="maxPrice"
            value="5000"
            min="0"
            max="5000">

        </div>

        <div class="slider-box">

            <input type="range"
            id="rangeMin"
            min="0"
            max="5000"
            value="0">

            <input type="range"
            id="rangeMax"
            min="0"
            max="5000"
            value="5000">

        </div>

    </div>

        <!-- COLORS -->
    <div class="filter-box">
    <?php
    $colors = mysqli_query($conn,"
    SELECT pc.color_name, COUNT(*) total
    FROM product_colors pc
    JOIN products p ON pc.product_id=p.id
    WHERE p.gender='$gender'
    GROUP BY pc.color_name
    ");

    while($row=mysqli_fetch_assoc($colors)){
    ?>
    <label class="check-box color-box">

    <input type="checkbox"
    name="color[]"
    value="<?= $row['color_name']; ?>">

    <span class="color-circle"
    style="background:<?= strtolower($row['color_name']); ?>;">
    </span>

    <?= ucfirst($row['color_name']); ?>

    (<?= $row['total']; ?>)

    </label>
    <?php } ?>
    </div>

    <!-- SIZE -->
    <div class="filter-box">
    <?php
    $sizes = mysqli_query($conn,"
    SELECT ps.size, COUNT(*) total
    FROM product_sizes ps
    JOIN products p ON ps.product_id=p.id
    WHERE p.gender='$gender'
    GROUP BY ps.size
    ");
    while($row=mysqli_fetch_assoc($sizes)){
    ?>
    <label class="check-box">
    <input type="checkbox" name="size[]" value="<?= $row['size']; ?>">
    <?= strtoupper($row['size']); ?> (<?= $row['total']; ?>)
    </label>
    <?php } ?>
    </div>

    <!-- DISCOUNT -->
    <div class="filter-box">
    <?php
    $discounts = mysqli_query($conn,"
    SELECT discount, COUNT(*) total
    FROM products
    WHERE gender='$gender'
    GROUP BY discount
    ");
    while($row=mysqli_fetch_assoc($discounts)){
    ?>
    <label class="check-box">
    <input type="checkbox" name="discount[]" value="<?= $row['discount']; ?>">
    <?= $row['discount']; ?>% OFF (<?= $row['total']; ?>)
    </label>
    <?php } ?>
    </div>

    <!-- BRAND -->
    <div class="filter-box">
    <?php
    $brands = mysqli_query($conn,"
    SELECT brand, COUNT(*) total
    FROM products
    WHERE gender='$gender'
    GROUP BY brand
    ");
    while($row=mysqli_fetch_assoc($brands)){
    ?>
    <label class="check-box">
    <input type="checkbox" name="brand[]" value="<?= $row['brand']; ?>">
    <?= ucfirst($row['brand']); ?> (<?= $row['total']; ?>)
    </label>
    <?php } ?>
    </div>

    <!-- OCCASION -->
    <div class="filter-box">
    <?php
    $occasions = mysqli_query($conn,"
    SELECT occasion, COUNT(*) total
    FROM products
    WHERE gender='$gender'
    GROUP BY occasion
    ");
    while($row=mysqli_fetch_assoc($occasions)){
    ?>
    <label class="check-box">
    <input type="checkbox" name="occasion[]" value="<?= $row['occasion']; ?>">
    <?= ucfirst($row['occasion']); ?> (<?= $row['total']; ?>)
    </label>
    <?php } ?>
    </div>
    </div>
    </div>

                
        <div class="popup-footer">

            <button class="reset-btn"
            onclick="window.location='popular-products.php?type=<?= $gender ?>'">
                Reset
            </button>

            <button class="apply-btn" onclick="applyFilters()">
                Apply Filter
            </button>

        </div>

    </div>
    <!-- SORT POPUP -->

    <div class="sort-popup" id="sortPopup">

        <div class="sort-header">
            Sort By
            <span onclick="closeSort()">✕</span>
        </div>

        <label class="sort-item">
            <input type="radio" name="sort" value="latest">
            Whats New
        </label>

        <label class="sort-item">
            <input type="radio" name="sort" value="high">
            Price High To Low
        </label>

        <label class="sort-item">
            <input type="radio" name="sort" value="low">
            Price Low To High
        </label>

        <label class="sort-item">
            <input type="radio" name="sort" value="discount">
            Best Discount
        </label>

        <button class="sort-apply" onclick="applySort()">
            Apply
        </button>

    </div>
    <script>

    /* FILTER OPEN/CLOSE */

    function openFilter(){
        document.getElementById("filterPopup").style.left = "0";
    }

    function closeFilter(){
        document.getElementById("filterPopup").style.left = "-100%";
    }

    /* SORT OPEN/CLOSE */

    function openSort(){
        document.getElementById("sortPopup").style.bottom = "0";
    }

    function closeSort(){
        document.getElementById("sortPopup").style.bottom = "-100%";
    }

    /* LEFT MENU SWITCH */

    const menu = document.querySelectorAll(".left-menu div");
    const boxes = document.querySelectorAll(".filter-box");

    menu.forEach((item,index)=>{

        item.onclick = ()=>{

            document.querySelector(".left-menu .active")
            .classList.remove("active");

            item.classList.add("active");

            boxes.forEach(box=>{
                box.classList.remove("active");
            });

            boxes[index].classList.add("active");
        };

    });

    /* APPLY FILTER */

    function applyFilters(){

        let gender = document.querySelector(
            'input[name="gender"]:checked'
        );

        let categories = document.querySelectorAll(
            'input[name="category[]"]:checked'
        );

        let minPrice = document.getElementById("minPrice").value;
    let maxPrice = document.getElementById("maxPrice").value;

        let colors = document.querySelectorAll(
            'input[name="color[]"]:checked'
        );

        let sizes = document.querySelectorAll(
            'input[name="size[]"]:checked'
        );

        let discounts = document.querySelectorAll(
            'input[name="discount[]"]:checked'
        );

        let brands = document.querySelectorAll(
            'input[name="brand[]"]:checked'
        );

        let occasions = document.querySelectorAll(
            'input[name="occasion[]"]:checked'
        );

        let url = "popular-products.php?type=" + gender.value;

        /* CATEGORY */
        let catArray = [];
        categories.forEach((cat)=>{
            catArray.push(cat.value);
        });
        if(catArray.length > 0){
            url += "&cat=" + catArray.join(',');
        }

        /* PRICE */
    url += "&price=" + minPrice + "-" + maxPrice;

        /* COLOR */
        let colorArray = [];
        colors.forEach((c)=>{
            colorArray.push(c.value);
        });
        if(colorArray.length > 0){
            url += "&color=" + colorArray.join(',');
        }

        /* SIZE */
        let sizeArray = [];
        sizes.forEach((s)=>{
            sizeArray.push(s.value);
        });
        if(sizeArray.length > 0){
            url += "&size=" + sizeArray.join(',');
        }

        /* DISCOUNT */
        let discountArray = [];
        discounts.forEach((d)=>{
            discountArray.push(d.value);
        });
        if(discountArray.length > 0){
            url += "&discount=" + discountArray.join(',');
        }

        /* BRAND */
        let brandArray = [];
        brands.forEach((b)=>{
            brandArray.push(b.value);
        });
        if(brandArray.length > 0){
            url += "&brand=" + brandArray.join(',');
        }

        /* OCCASION */
        let occasionArray = [];
        occasions.forEach((o)=>{
            occasionArray.push(o.value);
        });
        if(occasionArray.length > 0){
            url += "&occasion=" + occasionArray.join(',');
        }

        window.location = url;
    }

    /* APPLY SORT */

    function applySort(){

        let sort = document.querySelector(
            'input[name="sort"]:checked'
        );

        let url = window.location.pathname + window.location.search;

        if(sort){

            if(url.includes("&sort=")){

                url = url.replace(
                    /&sort=[^&]*/,
                    "&sort=" + sort.value
                );

            }else{

                url += "&sort=" + sort.value;

            }
        }

        window.location = url;
    }

    </script>
    <?php include "footer.php"; ?>

    </body>
    </html>