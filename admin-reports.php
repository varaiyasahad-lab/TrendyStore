<?php
session_start();

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin-login.php");
    exit;
}

include "db.php";

/* TOP SELLING PRODUCTS */
$topProducts = mysqli_query($conn,"
SELECT
p.name,
SUM(oi.qty) AS total_sales
FROM order_items oi
INNER JOIN products p ON p.id = oi.product_id
GROUP BY oi.product_id
ORDER BY total_sales DESC
LIMIT 10
");

/* LOW STOCK PRODUCTS */
$lowStock = mysqli_query($conn,"
SELECT id,name,stock
FROM products
WHERE stock > 0
AND stock <= 5
ORDER BY stock ASC
");

/* OUT OF STOCK PRODUCTS */
$outStock = mysqli_query($conn,"
SELECT id,name
FROM products
WHERE stock = 0
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Reports</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
    font-family:Arial,sans-serif;
    padding:30px;
}

.box{
    background:#fff;
    padding:25px;
    border-radius:20px;
    box-shadow:0 4px 15px rgba(0,0,0,.08);
    margin-bottom:25px;
}

h2{
    margin-bottom:20px;
}

.table{
    margin-bottom:0;
}
</style>
</head>
<body>

<a href="admin-dashboard.php" class="btn btn-secondary mb-4">
← Back to Dashboard
</a>

<h2>📑 Reports</h2>

<div class="box">

<h4>🔥 Top Selling Products</h4>

<table class="table table-bordered">

<thead class="table-dark">
<tr>
<th>Rank</th>
<th>Product</th>
<th>Total Sold</th>
</tr>
</thead>

<tbody>

<?php
$rank = 1;
while($row = mysqli_fetch_assoc($topProducts)){
?>
<tr>
<td>#<?= $rank++ ?></td>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= $row['total_sales'] ?></td>
</tr>
<?php } ?>

</tbody>

</table>

</div>

<div class="box">

<h4>⚠️ Low Stock Products</h4>

<table class="table table-bordered">

<thead class="table-warning">
<tr>
<th>ID</th>
<th>Product</th>
<th>Stock</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($lowStock)){ ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= $row['stock'] ?></td>
</tr>
<?php } ?>

</tbody>

</table>

</div>

<div class="box">

<h4>❌ Out Of Stock Products</h4>

<table class="table table-bordered">

<thead class="table-danger">
<tr>
<th>ID</th>
<th>Product</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($outStock)){ ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['name']) ?></td>
</tr>
<?php } ?>

</tbody>

</table>

</div>

</body>
</html>