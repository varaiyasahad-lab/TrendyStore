<?php
session_start();

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin-login.php");
    exit;
}

include "db.php";

$perPage = 10;

$topPage = isset($_GET['top_page']) ? max(1, (int)$_GET['top_page']) : 1;
$lowPage = isset($_GET['low_page']) ? max(1, (int)$_GET['low_page']) : 1;
$outPage = isset($_GET['out_page']) ? max(1, (int)$_GET['out_page']) : 1;

$topOffset = ($topPage - 1) * $perPage;
$lowOffset = ($lowPage - 1) * $perPage;
$outOffset = ($outPage - 1) * $perPage;

$topCountResult = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM (
    SELECT oi.product_id
    FROM order_items oi
    INNER JOIN products p ON p.id = oi.product_id
    GROUP BY oi.product_id
) AS t
");

$topTotal = (int)mysqli_fetch_assoc($topCountResult)['total'];
$topPages = max(1, ceil($topTotal / $perPage));

$topProducts = mysqli_query($conn,"
SELECT
    p.name,
    SUM(oi.qty) AS total_sales
FROM order_items oi
INNER JOIN products p ON p.id = oi.product_id
GROUP BY oi.product_id
ORDER BY total_sales DESC
LIMIT $perPage OFFSET $topOffset
");

$lowCountResult = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM (
    SELECT p.id
    FROM products p
    INNER JOIN product_sizes ps ON ps.product_id = p.id
    WHERE ps.stock > 0
    AND ps.stock <= 5
    GROUP BY p.id
) AS t
");

$lowTotal = (int)mysqli_fetch_assoc($lowCountResult)['total'];
$lowPages = max(1, ceil($lowTotal / $perPage));

$lowStock = mysqli_query($conn,"
SELECT
    p.id,
    p.name,
    MIN(ps.stock) AS stock,
    GROUP_CONCAT(
        CONCAT(ps.size, ' (', ps.stock, ')')
        ORDER BY ps.stock ASC
        SEPARATOR ', '
    ) AS size_stock
FROM products p
INNER JOIN product_sizes ps ON ps.product_id = p.id
WHERE ps.stock > 0
AND ps.stock <= 5
GROUP BY p.id, p.name
ORDER BY stock ASC
LIMIT $perPage OFFSET $lowOffset
");

$outCountResult = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM products p
WHERE NOT EXISTS (
    SELECT 1
    FROM product_sizes ps
    WHERE ps.product_id = p.id
    AND ps.stock > 0
)
");

$outTotal = (int)mysqli_fetch_assoc($outCountResult)['total'];
$outPages = max(1, ceil($outTotal / $perPage));

$outStock = mysqli_query($conn,"
SELECT
    p.id,
    p.name
FROM products p
WHERE NOT EXISTS (
    SELECT 1
    FROM product_sizes ps
    WHERE ps.product_id = p.id
    AND ps.stock > 0
)
ORDER BY p.id DESC
LIMIT $perPage OFFSET $outOffset
");

function pagination($current, $total, $param)
{
    if($total <= 1){
        return;
    }

    echo '<nav class="mt-3">';
    echo '<ul class="pagination justify-content-center">';

    if($current > 1){
        echo '<li class="page-item">';
        echo '<a class="page-link" href="?'.$param.'='.($current-1).'">Previous</a>';
        echo '</li>';
    }else{
        echo '<li class="page-item disabled">';
        echo '<span class="page-link">Previous</span>';
        echo '</li>';
    }

    $start = max(1, $current - 2);
    $end = min($total, $current + 2);

    if($start > 1){
        echo '<li class="page-item">';
        echo '<a class="page-link" href="?'.$param.'=1">1</a>';
        echo '</li>';

        if($start > 2){
            echo '<li class="page-item disabled">';
            echo '<span class="page-link">...</span>';
            echo '</li>';
        }
    }

    for($i = $start; $i <= $end; $i++){
        if($i == $current){
            echo '<li class="page-item active">';
            echo '<span class="page-link">'.$i.'</span>';
            echo '</li>';
        }else{
            echo '<li class="page-item">';
            echo '<a class="page-link" href="?'.$param.'='.$i.'">'.$i.'</a>';
            echo '</li>';
        }
    }

    if($end < $total){
        if($end < $total - 1){
            echo '<li class="page-item disabled">';
            echo '<span class="page-link">...</span>';
            echo '</li>';
        }

        echo '<li class="page-item">';
        echo '<a class="page-link" href="?'.$param.'='.$total.'">'.$total.'</a>';
        echo '</li>';
    }

    if($current < $total){
        echo '<li class="page-item">';
        echo '<a class="page-link" href="?'.$param.'='.($current+1).'">Next</a>';
        echo '</li>';
    }else{
        echo '<li class="page-item disabled">';
        echo '<span class="page-link">Next</span>';
        echo '</li>';
    }

    echo '</ul>';
    echo '</nav>';
}
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

.pagination{
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

<?php if(mysqli_num_rows($topProducts) > 0){ ?>

<?php
$rank = $topOffset + 1;

while($row = mysqli_fetch_assoc($topProducts)){
?>

<tr>

<td>#<?= $rank++ ?></td>

<td><?= htmlspecialchars($row['name']) ?></td>

<td><?= $row['total_sales'] ?></td>

</tr>

<?php } ?>

<?php }else{ ?>

<tr>

<td colspan="3" class="text-center">
No Sales Data
</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php pagination($topPage, $topPages, 'top_page'); ?>

</div>


<div class="box">

<h4>⚠️ Low Stock Products</h4>

<table class="table table-bordered">

<thead class="table-warning">

<tr>
<th>ID</th>
<th>Product</th>
<th>Lowest Stock</th>
<th>Size Stock</th>
</tr>

</thead>

<tbody>

<?php if(mysqli_num_rows($lowStock) > 0){ ?>

<?php while($row = mysqli_fetch_assoc($lowStock)){ ?>

<tr>

<td><?= $row['id'] ?></td>

<td><?= htmlspecialchars($row['name']) ?></td>

<td><?= $row['stock'] ?></td>

<td><?= htmlspecialchars($row['size_stock']) ?></td>

</tr>

<?php } ?>

<?php }else{ ?>

<tr>

<td colspan="4" class="text-center">
No Low Stock Products
</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php pagination($lowPage, $lowPages, 'low_page'); ?>

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

<?php if(mysqli_num_rows($outStock) > 0){ ?>

<?php while($row = mysqli_fetch_assoc($outStock)){ ?>

<tr>

<td><?= $row['id'] ?></td>

<td><?= htmlspecialchars($row['name']) ?></td>

</tr>

<?php } ?>

<?php }else{ ?>

<tr>

<td colspan="2" class="text-center">
No Out Of Stock Products
</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php pagination($outPage, $outPages, 'out_page'); ?>

</div>

</body>

</html>