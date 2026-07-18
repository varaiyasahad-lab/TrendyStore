<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$get = mysqli_query($conn,"
SELECT * FROM notifications
WHERE user_id='$user_id'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Notifications</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f5f5f5;
}

/* HEADER */

.header{
    background:#fff;
    padding:18px;
    font-size:22px;
    font-weight:bold;
    border-bottom:1px solid #eee;
    position:sticky;
    top:0;
    z-index:100;
}

/* CONTAINER */

.container{
    width:95%;
    max-width:700px;
    margin:15px auto;
}

/* CARD */

.card{
    background:#fff;
    border-radius:16px;
    padding:18px;
    margin-bottom:15px;
    display:flex;
    gap:15px;
    align-items:flex-start;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
    transition:0.3s;
    cursor:pointer;
}

.card:hover{
    transform:translateY(-2px);
}

/* ICON */

.icon{
    width:55px;
    height:55px;
    min-width:55px;
    border-radius:50%;
    background:#000;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
}

/* PRODUCT IMAGE */

.product-img{
    width:70px;
    height:70px;
    object-fit:cover;
    border-radius:12px;
}

/* CONTENT */

.content{
    flex:1;
}

.content h3{
    font-size:17px;
    margin-bottom:6px;
}

.product-name{
    display:block;
    font-size:15px;
    margin-bottom:8px;
    color:#111;
}

.content p{
    color:#666;
    font-size:14px;
    line-height:1.6;
}

.time{
    margin-top:10px;
    color:#999;
    font-size:12px;
}

/* EMPTY */

.empty{
    background:#fff;
    padding:40px;
    text-align:center;
    border-radius:16px;
    color:#777;
    font-size:16px;
}

.notification-link{
    text-decoration:none;
    color:black;
}

</style>
</head>

<body>

<div class="header">
    Notifications
</div>

<div class="container">

<?php
if(mysqli_num_rows($get)>0){

while($row=mysqli_fetch_assoc($get)){
?>

<a class="notification-link"
href="product-detail.php?id=<?php echo $row['product_id']; ?>">

<div class="card">

<div class="icon">
🔔
</div>

<?php if(!empty($row['product_image'])){ ?>

<img
src="uploads/<?php echo $row['product_image']; ?>"
class="product-img">

<?php } ?>

<div class="content">

<h3>
<?php echo $row['title']; ?>
</h3>

<b class="product-name">
<?php echo $row['product_name']; ?>
</b>

<p>
<?php echo $row['message']; ?>
</p>

<div class="time">
<?php echo $row['created_at']; ?>
</div>

</div>

</div>

</a>

<?php
}

}else{
?>

<div class="empty">
    No Notifications Yet
</div>

<?php } ?>

</div>

</body>
</html>