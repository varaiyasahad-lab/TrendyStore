

    <?php
    session_start();

    if(!isset($_SESSION['admin_logged_in'])){
        header("Location: admin-login.php");
        exit;
    }

    include "db.php";

    $adminName = $_SESSION['admin_name'] ?? 'Admin';
$adminImage = $_SESSION['admin_image'] ?? 'admin.png';

 
    $users = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) total FROM users
    "));


    $products = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) total FROM products
    "));

   
    $orders = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) total FROM orders
    "));

   
    $revenue = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT IFNULL(SUM(total),0) total FROM orders
    "));

  
    $todayOrders = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) total
    FROM orders
    WHERE DATE(created_at)=CURDATE()
    "));

    
    $weekOrders = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) total
    FROM orders
    WHERE YEARWEEK(created_at)=YEARWEEK(NOW())
    "));

 
    $monthOrders = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) total
    FROM orders
    WHERE MONTH(created_at)=MONTH(NOW())
    AND YEAR(created_at)=YEAR(NOW())
    "));

   
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

    
$pendingOrders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM orders
WHERE order_status='Pending'
"));



$deliveredOrders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM orders
WHERE order_status='Delivered'
"));


$returnOrders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM orders
WHERE return_status IS NOT NULL
AND return_status!=''
"));

    ?>

    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#f1f5f9;
    font-family:'Segoe UI',sans-serif;
}




.topbar{
    position:fixed;
    top:0;
    left:250px;
    right:0;
    height:75px;
    background:#fff;
    padding:0 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 3px 15px rgba(0,0,0,.08);
    z-index:999;
}

.topbar h2{
    font-size:28px;
    font-weight:700;
    color:#111827;
}




.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:250px;
    height:100vh;
    background:linear-gradient(180deg,#0f172a,#111827);
    padding-top:20px;
}

.logo{
    text-align:center;
    color:#fff;
    margin-bottom:30px;
}

.logo h3{
    font-size:24px;
    margin-bottom:5px;
}

.logo p{
    font-size:13px;
    color:#cbd5e1;
}

.sidebar a{
    display:block;
    color:#fff;
    text-decoration:none;
    padding:15px 25px;
    transition:.3s;
    font-size:16px;
}

.sidebar a:hover{
    background:#1e293b;
    padding-left:35px;
}




.content{
    margin-left:250px;
    margin-top:90px;
    padding:30px;
}




.card-box{
    border:none;
    border-radius:20px;
    padding:25px;
    color:#fff;
    height:150px;
    overflow:hidden;
    position:relative;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
    transition:.3s;
}

.card-box:hover{
    transform:translateY(-8px);
}

.card-box h2{
    font-size:42px;
    font-weight:700;
    margin-bottom:10px;
}

.card-box p{
    font-size:17px;
    opacity:.95;
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




.purple{
    background:linear-gradient(135deg,#7c3aed,#9333ea);
}

.blue{
    background:linear-gradient(135deg,#2563eb,#3b82f6);
}

.green{
    background:linear-gradient(135deg,#059669,#10b981);
}

.orange{
    background:linear-gradient(135deg,#ea580c,#fb923c);
}

.red{
    background:linear-gradient(135deg,#dc2626,#ef4444);
}

.dark{
    background:linear-gradient(135deg,#111827,#1f2937);
}

.gray{
    background:linear-gradient(135deg,#6b7280,#9ca3af);
}

.cyan{
    background:linear-gradient(135deg,#0891b2,#22d3ee);
}



.section-box{
    background:#fff;
    padding:25px;
    border-radius:20px;
    box-shadow:0 4px 20px rgba(0,0,0,.08);
}

.table{
    margin-bottom:0;
}

.badge{
    padding:8px 12px;
    font-size:13px;
}




@media(max-width:768px){

.sidebar{
    width:100%;
    height:auto;
    position:relative;
}

.topbar{
    left:0;
}

.content{
    margin-left:0;
}

}
.top-right{
    display:flex;
    align-items:center;
    gap:20px;
}

.search-box{
    display:flex;
    background:#f8fafc;
    border:1px solid #ddd;
    border-radius:12px;
    overflow:hidden;
}

.search-box input{
    border:none;
    outline:none;
    padding:10px 15px;
    width:220px;
}

.search-box button{
    border:none;
    background:#111827;
    color:#fff;
    padding:10px 15px;
}

.notification{
    position:relative;
    font-size:24px;
    cursor:pointer;
}

.notification span{
    position:absolute;
    top:-8px;
    right:-10px;
    background:red;
    color:#fff;
    font-size:12px;
    width:18px;
    height:18px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
}

.admin-profile{
    display:flex;
    align-items:center;
    gap:10px;
}

.admin-profile img{
    width:45px;
    height:45px;
    border-radius:50%;
    object-fit:cover;
}

.admin-profile h6{
    margin:0;
    font-size:15px;
    font-weight:600;
}

.admin-profile small{
    color:#64748b;
}
.topbar{
    position:fixed;
    top:0;
    left:250px;
    right:0;
    height:80px;
    background:#fff;
    padding:0 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 3px 15px rgba(0,0,0,.08);
    z-index:999;
}

.topbar-right{
    display:flex;
    align-items:center;
    gap:20px;
}



.search-box{
    display:flex;
    align-items:center;
    border:1px solid #ddd;
    border-radius:30px;
    overflow:hidden;
}

.search-box input{
    border:none;
    padding:12px 15px;
    width:220px;
    outline:none;
}

.search-box button{
    border:none;
    background:#111827;
    color:#fff;
    padding:12px 15px;
    cursor:pointer;
}



.notification{
    position:relative;
    font-size:24px;
    text-decoration:none;
}

.notification .badge{
    position:absolute;
    top:-8px;
    right:-10px;
    background:red;
    color:#fff;
    font-size:11px;
    padding:4px 7px;
    border-radius:50%;
}



.admin-profile{
    position:relative;
    display:flex;
    align-items:center;
    gap:10px;
    cursor:pointer;
}

.admin-img{
    width:40px;
    height:40px;
    border-radius:50%;
    object-fit:cover;
}

.admin-info span{
    font-size:16px;
    font-weight:600;
}

.admin-profile::after{
    content:"▼";
    font-size:10px;
    margin-left:5px;
}



.dropdown-menu-box{
    position:absolute;
    top:55px;
    right:0;
    width:180px;
    background:#fff;
    border-radius:10px;
    box-shadow:0 5px 20px rgba(0,0,0,.15);
    display:none;
    z-index:9999;
    overflow:hidden;
}

.dropdown-menu-box a{
    display:block;
    padding:14px 15px;
    text-decoration:none;
    color:#111;
    cursor:pointer;
}

.dropdown-menu-box a:hover{
    background:#f3f4f6;
}

.admin-profile.active .dropdown-menu-box{
    display:block;
}
.admin-info{
    display:flex;
    flex-direction:column;
    line-height:1.2;
}

.admin-info small{
    color:green;
    font-size:12px;
}

.dropdown-icon{
    font-size:12px;
    margin-left:5px;
}
</style>
    </head>

    <body>

   <div class="topbar">

   
    <div>
        <h2>📊 Admin Dashboard</h2>
        <small>Welcome Back Admin 👋</small>
    </div>

    
    
    <div class="topbar-right">

      
    
        <form action="admin-search.php" method="GET" class="search-box">
            <input type="text" name="search" placeholder="Search...">
            <button type="submit">🔍</button>
        </form>

    
        
        <a href="admin-orders.php" class="notification">
            🔔
            <span class="badge">67</span>
        </a>

      
        
        <div class="admin-profile">

<img src="uploads/admin1.jpg" class="admin-img">

<div class="admin-info">
    <span>👨‍💼 <b>Admin</b></span>
    <small>🟢 Online</small>
</div>



<div class="dropdown-menu-box">
    <a href="admin-settings.php">👤 Profile</a>
    <a href="admin-orders.php">📦 Orders</a>
    <a href="admin-logout.php">🚪 Logout</a>
</div>

</div>
</div>

</div>

    <div class="sidebar">

    <div class="logo">
    <h3>🛍 Trendy Store</h3>
    <p>Admin Panel</p>
</div>

    <a href="admin-dashboard.php">📊 Dashboard</a>

    <a href="admin-orders.php">🛒 Orders</a>

    <a href="admin-products.php">📦 Products</a>

    <a href="admin-users.php">👥 Users</a>

    <a href="admin-reports.php">📑 Reports</a>

<a href="admin-analytics.php">📈 Analytics</a>

<a href="admin-settings.php">⚙ Settings</a>

    <a href="admin-logout.php">🚪 Logout</a>

    </div>

    <div class="content">

    <div class="row g-4">

    <div class="col-md-3">
    <div class="card-box purple">
    <h2><?= $users['total'] ?></h2>
    <p>Total Users</p>
    </div>
    </div>

    <div class="col-md-3">
    <div class="card-box blue">
    <h2><?= $products['total'] ?></h2>
    <p>Total Products</p>
    </div>
    </div>

    <div class="col-md-3">
    <div class="card-box green">
    <h2><?= $orders['total'] ?></h2>
    <p>Total Orders</p>
    </div>
    </div>

    <div class="col-md-3">
    <div class="card-box orange">
    <h2>₹<?= $revenue['total'] ?></h2>
    <p>Total Revenue</p>
    </div>
    </div>

    </div>

    <div class="row g-4 mt-1">

    <div class="col-md-4">
    <div class="card-box blue">
    <h2><?= $todayOrders['total'] ?></h2>
    <p>Today's Orders</p>
    </div>
    </div>

    <div class="col-md-4">
    <div class="card-box green">
    <h2><?= $weekOrders['total'] ?></h2>
    <p>Weekly Orders</p>
    </div>
    </div>

    <div class="col-md-4">
    <div class="card-box red">
    <h2><?= $monthOrders['total'] ?></h2>
    <p>Monthly Orders</p>
    </div>
    </div>

    </div>

    <div class="row g-4 mt-1">

    <div class="col-md-4">
    <div class="card-box dark">
    <h2>₹<?= $todaySales['total'] ?></h2>
    <p>Today's Sales</p>
    </div>
    </div>

    <div class="col-md-4">
    <div class="card-box gray">
    <h2>₹<?= $weekSales['total'] ?></h2>
    <p>Weekly Sales</p>
    </div>
    </div>

    <div class="col-md-4">
    <div class="card-box cyan">
    <h2>₹<?= $monthSales['total'] ?></h2>
    <p>Monthly Sales</p>
    </div>
    </div>
    </div>

   
    
    <div class="row g-4 mt-4">

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
    <div class="card-box red">
    <h2><?= $monthVisitors['total'] ?></h2>
    <p>Monthly Visitors</p>
    </div>
    </div>
    </div>
    <div class="row g-4 mt-4">

<div class="col-md-4">
<div class="card-box bg-warning text-dark">
<h2><?= $pendingOrders['total'] ?></h2>
<p>Pending Orders</p>
</div>
</div>

<div class="col-md-4">
<div class="card-box bg-success">
<h2><?= $deliveredOrders['total'] ?></h2>
<p>Delivered Orders</p>
</div>
</div>

<div class="col-md-4">
<div class="card-box bg-danger">
<h2><?= $returnOrders['total'] ?></h2>
<p>Return Orders</p>
</div>
</div>

</div>
</div>
<script>
const adminProfile = document.querySelector('.admin-profile');

adminProfile.addEventListener('click', function(e){
    this.classList.toggle('active');
    e.stopPropagation();
});

document.addEventListener('click', function(){
    adminProfile.classList.remove('active');
});
</script>
    </body>
    </html>