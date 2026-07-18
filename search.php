<?php
session_start();
include "db.php";

/* SEARCH INPUT */
$q_raw = trim($_GET['q'] ?? '');
$q = strtolower($q_raw);

if($q == ''){
  echo "<p style='padding:20px'>Please type something</p>";
  exit;
}

/* NORMALIZE */
$q = rtrim($q, "s");

/* CATEGORY FIX */
if($q == "tshirt" || $q == "t-shirt"){
    $q = "t-shirts";
}

$categories = ["shirt", "jeans", "t-shirts"];

/* QUERY */
if(in_array($q, $categories)){
    $stmt = $conn->prepare("
        SELECT * FROM products 
        WHERE LOWER(category)=?
        ORDER BY id DESC
    ");
    $stmt->bind_param("s", $q);
}else{

    $stmt = $conn->prepare("
        SELECT * FROM products
        WHERE LOWER(name) LIKE ?
        ORDER BY name ASC
    ");

    $search = $q . "%";

    $stmt->bind_param("s", $search);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Search - Trendy Store</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body{
  margin:0;
  font-family:Arial;
  background:#f4f4f4;
  padding-top: 95px;
}

/* HEADER */
.title{
  padding:20px;
  font-size:24px;
  font-weight:700;
}

/* GRID */
.grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:20px;
  padding:20px;
}

/* CARD */
.card{
  background:#fff;
  border-radius:12px;
  padding:15px;
  text-align:center;
  box-shadow:0 6px 15px rgba(0,0,0,0.08);
  transition:.3s;
}

.card:hover{
  transform:translateY(-5px);
}

/* IMAGE */
.card img{
  width:100%;
  height:220px;
  object-fit:contain;
}

/* NAME */
.card h4{
  margin:10px 0 5px;
  font-size:16px;
}

/* PRICE */
.price{
  color:#0a7d5f;
  font-weight:700;
}

/* BUTTON */
.btn{
  display:inline-block;
  margin-top:10px;
  padding:8px 20px;
  background:#000;
  color:#fff;
  border-radius:20px;
  text-decoration:none;
}

/* MOBILE */
@media(max-width:768px){
  .grid{
    grid-template-columns:repeat(2,1fr);
  }
}

</style>
</head>

<body>
   <?php include 'header.php'; ?>

<div class="title">
  Results for: <span style="color:red"><?= htmlspecialchars($q_raw) ?></span>
</div>

<?php
if($result->num_rows == 0){
  echo "<p style='padding:20px'>❌ No products found</p>";
  exit;
}
?>

<div class="grid">

<?php while($row = $result->fetch_assoc()){ ?>

  <div class="card">

    <img src="uploads/<?= htmlspecialchars($row['image']) ?>">

    <h4><?= htmlspecialchars($row['name']) ?></h4>

    <div class="price">₹<?= $row['price'] ?></div>

    <!-- 🔥 FINAL FIXED BUTTON -->
    <a href="product-detail.php?id=<?= $row['id'] ?>" class="btn">
      View Details
    </a>

  </div>

<?php } ?>

</div>
 <?php include 'footer.php'; ?>
</body>
</html>