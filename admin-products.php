<?php
session_start();

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin-login.php");
    exit;
}

include "db.php";
/* SEARCH */

$search = $_GET['search'] ?? '';

$where = '';

if(!empty($search)){

    $search = mysqli_real_escape_string($conn,$search);

    $where = "
    WHERE name LIKE '%$search%'
    OR category LIKE '%$search%'
    OR brand LIKE '%$search%'
    ";
}

/* PAGINATION */

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1){
    $page = 1;
}

$start = ($page - 1) * $limit;

/* TOTAL PRODUCTS */

$totalResult = mysqli_query($conn,"
SELECT COUNT(*) total
FROM products
$where
");

$totalRow = mysqli_fetch_assoc($totalResult);

$totalProducts = $totalRow['total'];

$totalPages = ceil($totalProducts / $limit);

/* PRODUCTS */

$result = mysqli_query($conn,"
SELECT *
FROM products
$where
ORDER BY id DESC
LIMIT $start,$limit
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Products Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
    padding:30px;
    font-family:Arial;
}

.box{
    background:#fff;
    padding:20px;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

h2{
    margin-bottom:20px;
}

.product-img{
    width:70px;
    height:70px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid #ddd;
}

.table td{
    vertical-align:middle;
}

.badge-stock{
    font-size:13px;
    padding:8px 12px;
}

.pagination .page-link{
    color:#000;
    border-radius:8px;
    margin:0 3px;
}

.pagination .active .page-link{
    background:#111827;
    border-color:#111827;
}

</style>

</head>
<body>

<div class="box">

<h2>📦 Products Management</h2>
<div class="d-flex justify-content-between align-items-center mb-3">


<a href="add-product.php" class="btn btn-success mb-3">
+ Add Product
</a>

<a href="admin-dashboard.php" class="btn btn-secondary">
← Back
</a>

<form method="GET" class="d-flex" style="width:400px;">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Product / Category / Brand"
value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
>

<button class="btn btn-dark ms-2">
Search
</button>

</form>

</div>

<hr>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Category</th>
<th>Gender</th>
<th>Price</th>
<th>Old Price</th>
<th>Stock</th>
<th>Brand</th>
<th>Popular Product</th>
<th>Best Seller</th>
<th>Created</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $row['id'] ?></td>

<td>

<?php
$imagePath = "uploads/".$row['image'];

if(!empty($row['image']) && file_exists($imagePath)){
?>
<img src="<?= $imagePath ?>" class="product-img">
<?php
}else{
?>
<img src="images/no-image.png" class="product-img">
<?php } ?>

</td>

<td><?= htmlspecialchars($row['name']) ?></td>

<td><?= $row['category'] ?></td>

<td><?= $row['gender'] ?></td>

<td>₹<?= $row['price'] ?></td>

<td>₹<?= $row['old_price'] ?></td>

<td>

<?php if($row['stock'] > 0){ ?>

<span class="badge bg-success badge-stock">
<?= $row['stock'] ?>
</span>

<?php } else { ?>

<span class="badge bg-danger badge-stock">
Out
</span>

<?php } ?>

</td>

<td><?= $row['brand'] ?></td>

<td>

<?php if($row['popular']==1){ ?>

<span class="badge bg-info text-dark">
Yes
</span>

<?php } else { ?>

<span class="badge bg-secondary">
No
</span>

<?php } ?>

</td>

<td>

<?php if($row['best_seller']==1){ ?>

<span class="badge bg-warning text-dark">
Yes
</span>

<?php } else { ?>

<span class="badge bg-secondary">
No
</span>

<?php } ?>

</td>

<td><?= $row['created_at'] ?></td>

<td>
<a href="edit-product.php?id=<?= $row['id'] ?>"
class="btn btn-primary btn-sm">
Edit
</a>

<a href="delete-product.php?id=<?= $row['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this product?')">
Delete
</a>
</td>

</tr>

<?php } ?>

</tbody>

</table>

<!-- PAGINATION -->



<nav>

<ul class="pagination justify-content-center">

<?php if($page > 1){ ?>

<li class="page-item">
<a class="page-link"
href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">
Previous
</a>
</li>

<?php } ?>

<?php

$startPage = max(1,$page-2);
$endPage   = min($totalPages,$page+2);

for($i=$startPage;$i<=$endPage;$i++){

?>

<li class="page-item <?= ($page==$i)?'active':'' ?>">

<a class="page-link"
href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
<?= $i ?>
</a>

</li>

<?php } ?>

<?php if($page < $totalPages){ ?>

<li class="page-item">
<a class="page-link"
href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">
Next
</a>
</li>

<?php } ?>

</ul>

</nav>

</body>
</html>