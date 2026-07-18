<?php
session_start();
include "db.php";

/* ================= LOGIN CHECK ================= */
if(!isset($_SESSION['user_id'])){
    echo "Please login first";
    exit;
}

$user_id = $_SESSION['user_id'];

/* ================= FETCH ORDERS ================= */
$orders = $conn->query("
SELECT 
    o.id,
    o.order_code,
    o.tracking_id,
    o.order_status,
    o.return_status,
    o.refund_status,
    o.created_at,
    (
        SELECT oi.id 
        FROM order_items oi 
        WHERE oi.order_id = o.id 
        LIMIT 1
    ) as item_id,
    (
    SELECT pc.color_image
    FROM order_items oi
    JOIN product_colors pc 
    ON pc.product_id = oi.product_id
    WHERE oi.order_id = o.id
    LIMIT 1
) as color_image
FROM orders o
WHERE o.user_id='$user_id'
ORDER BY o.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>My Orders</title>



<style>

body{
    font-family:Arial;
    background:#f2f2f2;
    margin:0;
    padding-top: 95px;
}

h2{
    padding:14px;
    margin:0;
    font-size:28px;
}

.card{
    background:white;
    margin:12px;
    padding:14px;
    border-radius:16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.06);
    transition:0.2s;
}

.card:hover{
    transform:scale(1.01);
}

.card img{
    width:95px;
    height:95px;
    object-fit:contain;
    border-radius:12px;
    background:#f8f8f8;
}

a{
    text-decoration:none;
    color:black;
}

.status{
    font-weight:bold;
    color:#555;
    font-size:15px;
}

.empty{
    text-align:center;
    margin-top:60px;
    color:#777;
    font-size:18px;
}

.arrow{
    font-size:26px;
    color:#999;
    font-weight:bold;
}

.date{
    font-size:13px;
    color:#777;
    margin-top:6px;
}

.badge{
    color:#fff;
    display:inline-block;
    padding:6px 14px;
    border-radius:30px;
    font-size:13px;
    font-weight:bold;
    margin-bottom:8px;
}

@media(max-width:600px){

.card{
    padding:12px;
}

.card img{
    width:88px;
    height:88px;
}

h2{
    font-size:24px;
}

}
.order-filters{
    margin:20px 0;
}

.filter-select{
    width:220px;
    padding:12px;
    border:1px solid #ddd;
    font-size:15px;
    border-radius:4px;
    margin-bottom:20px;
}

.year-list{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.year-list a{
    text-decoration:none;
    padding:10px 20px;
    border:1px solid #ddd;
    color:#111;
    border-radius:4px;
    font-weight:600;
    transition:.3s;
}

.year-list a:hover{
    background:#111;
    color:#fff;
}

</style>

</head>

<body>
      <?php include 'header.php'; ?>

<h2>My Orders</h2>

<div class="order-filters">

    <!-- Last 6 Months -->
    <select name="month_filter" class="filter-select">
        <option>Last 6 Months</option>
        <option>2026</option>
        <option>2025</option>
         <option>2024</option>
          <option>2023</option>
    </select>


<?php if($orders->num_rows > 0){ ?>

<?php 


while($row = $orders->fetch_assoc()){ 
 

$img = !empty($row['color_image'])
    ? "./uploads/" . $row['color_image']
    : "./uploads/default.png";

$status = strtolower(trim($row['order_status'] ?? ''));

$return_status = strtolower(trim($row['return_status'] ?? ''));
$refund_status = strtolower(trim($row['refund_status'] ?? ''));

$color = "#0d6efd";
$text  = "Order Confirmed";

/* ================= STATUS ================= */

if($status == "processing"){
    $color = "#fd7e14";
    $text  = "Processing";
}

if($status == "packed"){
    $color = "#6f42c1";
    $text  = "Packed";
}

if($status == "shipped"){
    $color = "#6f42c1";
    $text  = "Shipped";
}

if($status == "out for delivery"){
    $color = "#198754";
    $text  = "Out For Delivery";
}

if($status == "delivered"){
    $color = "#198754";
    $text  = "Delivered";
}

if($status == "cancelled"){
    $color = "#dc3545";
    $text  = "Cancelled";
}

/* ================= RETURN REFUND ================= */

if(
    $return_status != "" ||
    $refund_status != "" ||
    $status == "returned" ||
    $status == "refund initiated" ||
    $status == "refunded"
){
    $color = "#b8860b";
    $text  = "Return & Refund";
}

$date = !empty($row['created_at']) 
    ? date("d M Y", strtotime($row['created_at']))
    : date("d M Y");

?>
<a href="order-details.php?code=<?=$row['order_code']?>">

<div class="card">

<div style="display:flex;align-items:center;">

<img 
src="<?= htmlspecialchars($img) ?>" 
onerror="this.src='./uploads/default.png'"
>
<div style="margin-left:14px;">

<div 
class="badge"
style="background:<?=$color?>;"
>
<?=$text?>
</div>

<div class="status">
Order ID: <?=$row['order_code']?>
</div>

<div style="font-size:13px;color:#666;margin-top:4px;">
Tracking ID: <?=$row['tracking_id']?>
</div>

<div class="date">
<?=$text?> on <?=$date?>
</div>

</div>

</div>

<div class="arrow">›</div>

</div>

</a>



<?php } ?>

<?php } else { ?>

<div class="empty">
No orders found
</div>

<?php } ?>
  <?php include 'footer.php'; ?>
</body>
</html>