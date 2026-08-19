<?php
session_start();
include "db.php";

/* ================= LOGIN CHECK ================= */
if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ================= FETCH NOTIFICATIONS ================= */
$get = mysqli_query($conn, "
    SELECT * FROM notifications
    WHERE user_id='$user_id'
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Notifications - Trendy Store</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f6f6f6;
    color:#111;
    margin-top: 95px;
}

/* ================= HEADER ================= */

.page-header{
    background:#fff;
    border-bottom:1px solid #e5e5e5;
    padding:18px 5%;
    position:sticky;
    top:0;
    z-index:100;
}

.header-inner{
    max-width:1100px;
    margin:auto;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.header-title{
    display:flex;
    align-items:center;
    gap:12px;
}

.header-icon{
    width:45px;
    height:45px;
    border-radius:50%;
    background:#111;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:21px;
}

.header-title h1{
    font-size:23px;
}

.header-title p{
    font-size:13px;
    color:#888;
    margin-top:3px;
}

/* ================= MAIN ================= */

.notification-container{
    width:94%;
    max-width:850px;
    margin:30px auto 50px;
}

/* ================= NOTIFICATION CARD ================= */

.notification-link{
    text-decoration:none;
    color:#111;
    display:block;
}

.notification-card{
    background:#fff;
    border-radius:18px;
    padding:18px;
    margin-bottom:16px;

    display:flex;
    align-items:flex-start;
    gap:15px;

    border:1px solid #eee;

    box-shadow:0 4px 15px rgba(0,0,0,0.05);

    transition:0.3s;
}

.notification-card:hover{
    transform:translateY(-3px);
    box-shadow:0 8px 22px rgba(0,0,0,0.10);
}

/* ================= ICON ================= */

.notification-icon{
    width:52px;
    height:52px;
    min-width:52px;

    border-radius:50%;

    background:#111;
    color:#fff;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:22px;
}

/* ================= PRODUCT IMAGE ================= */

.product-img{
    width:72px;
    height:72px;
    min-width:72px;

    object-fit:cover;

    border-radius:12px;

    border:1px solid #eee;
}

/* ================= CONTENT ================= */

.notification-content{
    flex:1;
    min-width:0;
}

.notification-content h3{
    font-size:17px;
    margin-bottom:6px;
    font-weight:700;
}

.product-name{
    display:block;
    font-size:14px;
    margin-bottom:7px;
    color:#333;
}

.notification-message{
    color:#666;
    font-size:14px;
    line-height:1.6;
}

.notification-time{
    margin-top:10px;
    font-size:12px;
    color:#999;
}

/* ================= ARROW ================= */

.notification-arrow{
    width:30px;
    height:30px;

    display:flex;
    align-items:center;
    justify-content:center;

    color:#999;
    font-size:18px;
}

/* ================= EMPTY ================= */

.empty-box{
    background:#fff;

    border:1px solid #eee;
    border-radius:18px;

    padding:60px 20px;

    text-align:center;

    box-shadow:0 4px 15px rgba(0,0,0,0.05);
}

.empty-icon{
    width:75px;
    height:75px;

    border-radius:50%;

    background:#f1f1f1;

    margin:0 auto 18px;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:32px;
}

.empty-box h2{
    font-size:20px;
    margin-bottom:8px;
}

.empty-box p{
    color:#888;
    font-size:14px;
}

/* ================= MOBILE ================= */

@media(max-width:600px){

    .page-header{
        padding:15px;
    }

    .header-title h1{
        font-size:20px;
    }

    .header-title p{
        font-size:12px;
    }

    .notification-container{
        width:94%;
        margin-top:20px;
    }

    .notification-card{
        padding:14px;
        gap:10px;
        border-radius:15px;
    }

    .notification-icon{
        width:43px;
        height:43px;
        min-width:43px;
        font-size:18px;
    }

    .product-img{
        width:60px;
        height:60px;
        min-width:60px;
    }

    .notification-content h3{
        font-size:15px;
    }

    .product-name{
        font-size:13px;
    }

    .notification-message{
        font-size:13px;
    }

    .notification-arrow{
        display:none;
    }

}
.back-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    margin:15px 14px 5px;
    padding:10px 16px;
    background:#111;
    color:#fff;
    border-radius:8px;
    font-size:14px;
    font-weight:bold;
    text-decoration:none;
    transition:0.3s;
}

.back-btn:hover{
    background:#333;
}

.back-arrow{
    font-size:20px;
    line-height:1;
}

</style>

</head>

<body>
    <?php include "header.php"; ?>
<a href="account.php" class="back-btn">
    <span class="back-arrow">←</span>
    Back to Account
</a>
<!-- ================= HEADER ================= -->

<div class="page-header">

    <div class="header-inner">

        <div class="header-title">

            <div class="header-icon">
                🔔
            </div>

            <div>
                <h1>Notifications</h1>
                <p>Your latest updates</p>
            </div>

        </div>

    </div>

</div>


<!-- ================= NOTIFICATIONS ================= -->

<div class="notification-container">

<?php

if(mysqli_num_rows($get) > 0){

    while($row = mysqli_fetch_assoc($get)){

?>

<a
class="notification-link"
href="product-detail.php?id=<?php echo (int)$row['product_id']; ?>"
>

<div class="notification-card">

    <!-- Notification Icon -->
    <div class="notification-icon">
        🔔
    </div>


    <!-- Product Image -->
    <?php if(!empty($row['product_image'])){ ?>

        <img
            src="uploads/<?php echo htmlspecialchars($row['product_image']); ?>"
            class="product-img"
            alt="Product"
        >

    <?php } ?>


    <!-- Notification Content -->
    <div class="notification-content">

        <h3>
            <?php echo htmlspecialchars($row['title']); ?>
        </h3>

        <?php if(!empty($row['product_name'])){ ?>

            <span class="product-name">
                <?php echo htmlspecialchars($row['product_name']); ?>
            </span>

        <?php } ?>

        <p class="notification-message">
            <?php echo htmlspecialchars($row['message']); ?>
        </p>

        <div class="notification-time">
            <?php echo htmlspecialchars($row['created_at']); ?>
        </div>

    </div>


    <!-- Arrow -->
    <div class="notification-arrow">
        ›
    </div>

</div>

</a>

<?php

    }

}else{

?>

<!-- ================= EMPTY ================= -->

<div class="empty-box">

    <div class="empty-icon">
        🔔
    </div>

    <h2>No Notifications Yet</h2>

    <p>
        You don't have any notifications right now.
    </p>

</div>

<?php

}

?>

</div>
<?php include "footer.php"; ?>
</body>
</html>