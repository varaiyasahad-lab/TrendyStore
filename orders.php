<?php
session_start();
include "db.php";

$mobile = $_SESSION['mobile'];

$q = $conn->query("SELECT * FROM orders WHERE mobile='$mobile' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Orders</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{font-family:Arial;background:#f5f5f5;margin:0;}
.card{
background:white;
margin:10px;
padding:10px;
border-radius:8px;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
display:flex;
align-items:center;
justify-content:space-between;
}
.card img{width:60px;border-radius:6px;}
a{text-decoration:none;color:black;}
.status{font-weight:bold;}
</style>
</head>
<body>

<h2 style="padding:10px;">My Orders</h2>

<?php while($row=$q->fetch_assoc()){ ?>
<a href="order-details.php?id=<?=$row['id']?>">
<div class="card">
<div style="display:flex;align-items:center;">
<img src="uploads/<?=$row['image']?>">
<div style="margin-left:10px;">
<div><?=$row['product_name']?></div>
<div class="status"><?=$row['status']?></div>
</div>
</div>
<div>></div>
</div>
</a>
<?php } ?>

</body>
</html>