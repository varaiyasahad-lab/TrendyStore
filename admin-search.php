<?php
session_start();

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin-login.php");
    exit;
}

include "db.php";

$search = $_GET['search'] ?? '';
$search = mysqli_real_escape_string($conn,$search);

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1){
    $page = 1;
}

$start = ($page - 1) * $limit;


$countQuery = "
SELECT COUNT(*) as total FROM (

    SELECT order_code as item
    FROM orders
    WHERE order_code LIKE '%$search%'
    OR name LIKE '%$search%'

    UNION

    SELECT id as item
    FROM users
    WHERE first_name LIKE '%$search%'
    OR last_name LIKE '%$search%'
    OR email LIKE '%$search%'

    UNION

    SELECT id as item
    FROM products
    WHERE name LIKE '%$search%'
    OR brand LIKE '%$search%'

) as all_results
";

$countResult = mysqli_query($conn,$countQuery);
$countRow = mysqli_fetch_assoc($countResult);

$totalResults = $countRow['total'];
$totalPages = ceil($totalResults / $limit);

$query = "

SELECT 
'Order' as type,
order_code as code,
name as title,
total as extra
FROM orders
WHERE order_code LIKE '%$search%'
OR name LIKE '%$search%'

UNION

SELECT
'User' as type,
id as code,
CONCAT(first_name,' ',last_name) as title,
email as extra
FROM users
WHERE first_name LIKE '%$search%'
OR last_name LIKE '%$search%'
OR email LIKE '%$search%'

UNION

SELECT
'Product' as type,
id as code,
name as title,
brand as extra
FROM products
WHERE name LIKE '%$search%'
OR brand LIKE '%$search%'

LIMIT $start,$limit
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Search</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f1f5f9;
    font-family:Segoe UI;
}
.box{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}
</style>

</head>
<body>

<div class="container mt-5">

<a href="admin-dashboard.php" class="btn btn-dark mb-4">← Back</a>

<h2 class="mb-4">Search Results</h2>

<form method="GET" class="mb-4">
<div class="input-group">
<input type="text" name="search" class="form-control"
placeholder="Search orders, users, products..."
value="<?= htmlspecialchars($search) ?>">

<button class="btn btn-dark">Search</button>
</div>
</form>

<div class="box">

<table class="table table-bordered">

<thead class="table-dark">
<tr>
<th>Type</th>
<th>ID / Code</th>
<th>Name</th>
<th>Extra</th>
</tr>
</thead>

<tbody>

<?php if(mysqli_num_rows($result)>0){ ?>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?= $row['type'] ?></td>
<td><?= $row['code'] ?></td>
<td><?= $row['title'] ?></td>
<td><?= $row['extra'] ?></td>
</tr>

<?php } ?>

<?php } else { ?>

<tr>
<td colspan="4" class="text-center">No Results Found</td>
</tr>

<?php } ?>

</tbody>
</table>



<nav>
<ul class="pagination justify-content-center">

<?php for($i=1;$i<=$totalPages;$i++){ ?>

<li class="page-item <?= ($page==$i)?'active':'' ?>">
<a class="page-link"
href="?search=<?= urlencode($search) ?>&page=<?= $i ?>">
<?= $i ?>
</a>
</li>

<?php } ?>

</ul>
</nav>

</div>
</div>

</body>
</html>