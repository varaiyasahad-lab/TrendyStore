<?php
session_start();

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin-login.php");
    exit;
}

include "db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* =========================
   ORDER STATUS UPDATE
========================= */
if(isset($_POST['update_status'])){

    $order_code = $conn->real_escape_string($_POST['order_code']);
    $status     = $conn->real_escape_string($_POST['status']);

    $dateField = "";

    if($status=="Confirmed") $dateField="confirmed_at";
    elseif($status=="Packed") $dateField="packed_at";
    elseif($status=="Shipped") $dateField="shipped_at";
    elseif($status=="Out for Delivery") $dateField="out_for_delivery_at";
    elseif($status=="Delivered") $dateField="delivered_at";

    $query = "UPDATE orders SET order_status='$status'";

    if($dateField != ""){
        $query .= ", $dateField = NOW()";
    }

    $query .= " WHERE order_code='$order_code'";

    if(!$conn->query($query)){
        die("Order Update Error: " . $conn->error);
    }
    header("Location: admin-orders.php");
exit;
}

/* =========================
   RETURN UPDATE (FIXED)
========================= */
if(isset($_POST['update_return'])){

    $order_code   = $conn->real_escape_string($_POST['order_code']);
    $return_status = $conn->real_escape_string($_POST['return_status']);

    $return_status = trim($return_status);

    $query = "UPDATE orders SET return_status='$return_status'";

    if($return_status == "Return Requested"){
        $query .= ", returned_at = NOW()";
    }

    if(strtolower($return_status) == "picked up"){
        $query .= ", pickup_at = NOW()";
    }

    if(strtolower($return_status) == "quality check"){
        $query .= ", quality_check_at = NOW()";
    }

    $query .= " WHERE order_code='$order_code'";

    if(!$conn->query($query)){
        die("Return Update Error: " . $conn->error);
    }
    header("Location: admin-orders.php");
exit;
}
/* =========================
   REFUND UPDATE (FIXED)
========================= */
if(isset($_POST['update_refund'])){

    $order_code = $_POST['order_code'];
    $refund_status = $_POST['refund_status'];

    $query = "UPDATE orders 
              SET refund_status='$refund_status'";

    if(strtolower($refund_status) == "processing"){
        $query .= ", refund_initiated_at = NOW()";
    }

    if(strtolower($refund_status) == "completed"){
        $query .= ", refund_completed_at = NOW()";
    }

    $query .= " WHERE order_code='$order_code'";

    if($conn->query($query)){
        echo "<script>alert('Refund Updated');window.location.href='admin-orders.php';</script>";
    } else {
        echo $conn->error;
    }
}
/* SEARCH */

$search = $_GET['search'] ?? '';

$where = '';

if(!empty($search)){
    $search = $conn->real_escape_string($search);

    $where = "
    WHERE order_code LIKE '%$search%'
    OR name LIKE '%$search%'
    ";
}
/* =========================
   FETCH ORDERS
========================= */
/* PAGINATION */

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1){
    $page = 1;
}

$start = ($page - 1) * $limit;

/* TOTAL ORDERS */
$totalRes = $conn->query("
SELECT COUNT(*) total
FROM orders
$where
");

$totalRow = $totalRes->fetch_assoc();

$totalOrders = $totalRow['total'];

$totalPages = ceil($totalOrders / $limit);

/* FETCH ORDERS */

$orders = $conn->query("
SELECT *
FROM orders
$where
ORDER BY id DESC
LIMIT $start,$limit
");

if(!$orders){
    die('Fetch Error: '.$conn->error);
}

if(!$orders){
    die("Fetch Error: " . $conn->error);
}
?>

<!DOCTYPE html>

<html>
<head>
<title>Admin Orders</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f1f5f9;
    font-family:'Segoe UI',sans-serif;
}

.card-box{
    background:#fff;
    padding:25px;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.table{
    background:#fff;
}

.table thead{
    background:#111827;
    color:#fff;
}

.table th,
.table td{
    vertical-align:middle;
    padding:15px;
}

.table tbody tr:hover{
    background:#f8fafc;
}

.form-select{
    border-radius:10px;
}

.btn{
    border-radius:10px;
}

.pagination .page-link{
    border-radius:10px;
    margin:0 4px;
}

.pagination .active .page-link{
    background:#111827;
    border-color:#111827;
}
</style>

</head>

<body>

<div class="container mt-5">
    <div class="container mt-5">

<a href="javascript:history.back()" class="btn btn-secondary mb-3">
    ← Back
</a>

<h3 class="mb-4">📦 Admin Order Management</h3>
<form method="GET" class="mb-4">
    <div class="input-group">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search Order Code or Customer Name"
            value="<?= $_GET['search'] ?? '' ?>"
        >

        <button class="btn btn-dark" type="submit">
            Search
        </button>
    </div>
</form>
<div class="card-box">

<table class="table table-bordered align-middle">

<thead class="table-dark">
<tr>
<th>Order Code</th>
<th>Name</th>
<th>Total</th>
<th>Details</th>
<th>Order</th>
<th>Return</th>
<th>Refund</th>

</tr>
</thead>

<tbody>


<?php while($row = $orders->fetch_assoc()){ ?>

<tr>

<td><?= $row['order_code'] ?></td>
<td><?= $row['name'] ?? '-' ?></td>
<?php
if(!empty($row['total_amount']) && $row['total_amount'] > 0){
    $total = $row['total_amount'];
} else {
    $total = 0;

    $items = $conn->query("
        SELECT qty, price
        FROM order_items
        WHERE order_id = '".$row['id']."'
    ");

    while($item = $items->fetch_assoc()){
        $total += ($item['price'] * $item['qty']);
    }
}
?>
<td>₹<?= $total ?></td>

<td>
<a href="admin-order-details.php?id=<?= $row['id'] ?>"
class="btn btn-info btn-sm">
View
</a>
</td>


<!-- ORDER -->

<td>
<form method="POST">
<input type="hidden" name="order_code" value="<?= $row['order_code'] ?>">

<select name="status" class="form-select mb-2">
<option value="Confirmed" <?= ($row['order_status']=="Confirmed")?'selected':'' ?>>Confirmed</option>
<option value="Packed" <?= ($row['order_status']=="Packed")?'selected':'' ?>>Packed</option>
<option value="Shipped" <?= ($row['order_status']=="Shipped")?'selected':'' ?>>Shipped</option>
<option value="Out for Delivery" <?= ($row['order_status']=="Out for Delivery")?'selected':'' ?>>Out for Delivery</option>
<option value="Delivered" <?= ($row['order_status']=="Delivered")?'selected':'' ?>>Delivered</option>
</select>

<button type="submit" name="update_status" class="btn btn-success btn-sm w-100">
Update Order
</button>
</form>
</td>

<!-- RETURN -->

<td>
<form method="POST">
<input type="hidden" name="order_code" value="<?= $row['order_code'] ?>">

<select name="return_status" class="form-select mb-2">
<option value="">None</option>
<option value="Return Requested" <?= ($row['return_status']=="Return Requested")?'selected':'' ?>>Return Requested</option>
<option value="Picked Up" <?= ($row['return_status']=="Picked Up")?'selected':'' ?>>Picked Up</option>
<option value="Quality Check" <?= ($row['return_status']=="Quality Check")?'selected':'' ?>>Quality Check</option>
</select>
<button type="submit" name="update_return" class="btn btn-warning btn-sm w-100">
Update Return
</button>
</form>
</td>

<!-- REFUND -->

<td>
<form method="POST">
  <input type="hidden" name="order_code" value="<?= $row['order_code'] ?>">

  <select name="refund_status" class="form-select mb-2">
    <option value="">None</option>
    <option value="Processing" <?= ($row['refund_status']=="Processing")?'selected':'' ?>>Processing</option>
    <option value="Completed" <?= ($row['refund_status']=="Completed")?'selected':'' ?>>Completed</option>
  </select>

  <button type="submit" name="update_refund" class="btn btn-primary btn-sm w-100">
    Update Refund
  </button>
</form>
</td>

</tr>

<?php } ?>

</tbody>
</table>
<nav class="mt-4">
    <ul class="pagination justify-content-center">

<?php if($page > 1){ ?>
<li class="page-item">
<a class="page-link" href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">Previous</a>
</li>
<?php } ?>

<?php

$start = max(1, $page - 2);
$end   = min($totalPages, $page + 2);

if($start > 1){
    echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';

    if($start > 2){
        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }
}

for($i=$start; $i<=$end; $i++){
?>

<li class="page-item <?= ($i==$page)?'active':'' ?>">
<a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
<?= $i ?>
</a>
</li>

<?php } ?>

<?php

if($end < $totalPages){

    if($end < $totalPages-1){
        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }

    echo '<li class="page-item"><a class="page-link" href="?page='.$totalPages.'">'.$totalPages.'</a></li>';
}
?>

<?php if($page < $totalPages){ ?>
<li class="page-item">
<a class="page-link" href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">Next</a>
</li>
<?php } ?>

</ul>

</nav>
</div>

</div>

</body>
</html>
