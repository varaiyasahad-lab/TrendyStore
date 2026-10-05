<?php
session_start();

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin-login.php");
    exit;
}

include "db.php";


$todaySales = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(total),0) total
FROM orders
WHERE DATE(created_at)=CURDATE()
"));


$weekSales = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(total),0) total
FROM orders
WHERE YEARWEEK(created_at)=YEARWEEK(NOW())
"));

$monthSales = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(total),0) total
FROM orders
WHERE MONTH(created_at)=MONTH(NOW())
AND YEAR(created_at)=YEAR(NOW())
"));


$visitors = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM site_visits
"));

$todayVisitors = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM site_visits
WHERE DATE(visit_date)=CURDATE()
"));

$weekVisitors = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM site_visits
WHERE YEARWEEK(visit_date)=YEARWEEK(NOW())
"));

$monthVisitors = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM site_visits
WHERE MONTH(visit_date)=MONTH(NOW())
AND YEAR(visit_date)=YEAR(NOW())
"));

$chart = mysqli_query($conn,"
SELECT
DATE(created_at) day,
IFNULL(SUM(total),0) sales
FROM orders
WHERE created_at >= DATE_SUB(CURDATE(),INTERVAL 7 DAY)
GROUP BY DATE(created_at)
ORDER BY day ASC
");

$days = [];
$sales = [];

while($row = mysqli_fetch_assoc($chart)){
    $days[] = $row['day'];
    $sales[] = $row['sales'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Analytics</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#f1f5f9;
    font-family:'Segoe UI',sans-serif;
    padding:30px;
}



.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.page-title{
    font-size:32px;
    font-weight:700;
    color:#111827;
}


.card-box{
    border-radius:20px;
    padding:25px;
    color:#fff;
    min-height:140px;
    box-shadow:0 10px 25px rgba(0,0,0,.12);
    transition:.3s;
    position:relative;
    overflow:hidden;
}

.card-box:hover{
    transform:translateY(-6px);
}

.card-box::after{
    content:'';
    position:absolute;
    right:-30px;
    top:-30px;
    width:120px;
    height:120px;
    border-radius:50%;
    background:rgba(255,255,255,.15);
}

.card-box h2{
    font-size:38px;
    font-weight:700;
}

.card-box p{
    margin-top:10px;
    font-size:16px;
}



.blue{
    background:linear-gradient(135deg,#2563eb,#3b82f6);
}

.green{
    background:linear-gradient(135deg,#059669,#10b981);
}

.red{
    background:linear-gradient(135deg,#dc2626,#ef4444);
}

.orange{
    background:linear-gradient(135deg,#ea580c,#fb923c);
}

.dark{
    background:linear-gradient(135deg,#111827,#1f2937);
}

.cyan{
    background:linear-gradient(135deg,#0891b2,#22d3ee);
}


.chart-box{
    background:#fff;
    padding:30px;
    border-radius:20px;
    margin-top:30px;
    box-shadow:0 4px 20px rgba(0,0,0,.08);
}

.chart-box h4{
    font-weight:700;
    color:#111827;
}
</style>

</head>

<body>

<a href="admin-dashboard.php" class="btn btn-secondary mb-4">
← Back To Dashboard
</a>

<h2 class="mb-4">📈 Analytics</h2>

<div class="row g-4">

<div class="col-md-4">
<div class="card-box blue">
<h2>₹<?= $todaySales['total'] ?></h2>
<p>Today's Sales</p>
</div>
</div>

<div class="col-md-4">
<div class="card-box green">
<h2>₹<?= $weekSales['total'] ?></h2>
<p>Weekly Sales</p>
</div>
</div>

<div class="col-md-4">
<div class="card-box red">
<h2>₹<?= $monthSales['total'] ?></h2>
<p>Monthly Sales</p>
</div>
</div>

</div>

<div class="row g-4 mt-1">

<div class="col-md-3">
<div class="card-box dark">
<h2><?= $visitors['total'] ?></h2>
<p>Total Visitors</p>
</div>
</div>

<div class="col-md-3">
<div class="card-box blue">
<h2><?= $todayVisitors['total'] ?></h2>
<p>Today's Visitors</p>
</div>
</div>

<div class="col-md-3">
<div class="card-box green">
<h2><?= $weekVisitors['total'] ?></h2>
<p>Weekly Visitors</p>
</div>
</div>

<div class="col-md-3">
<div class="card-box orange">
<h2><?= $monthVisitors['total'] ?></h2>
<p>Monthly Visitors</p>
</div>
</div>

</div>

<div class="chart-box">

<h4 class="mb-4">📊 Last 7 Days Sales</h4>

<canvas id="salesChart"></canvas>

</div>

<script>

new Chart(document.getElementById('salesChart'),{
    type:'line',
    data:{
        labels:<?= json_encode($days) ?>,
        datasets:[{
            label:'Sales',
            data:<?= json_encode($sales) ?>,
            borderWidth:3,
            fill:false
        }]
    },
    options:{
        responsive:true
    }
});

</script>

</body>
</html>