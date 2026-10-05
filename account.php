<?php
session_start();
include "db.php";


if(isset($_GET['logout'])){
    session_destroy();
    header("Location: auth.php");
    exit;
}


if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit;
}


$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user = $result->fetch_assoc();


$name = trim($user['first_name'] . " " . $user['last_name']);
$words = explode(" ", $name);

if(count($words) > 1 && !empty($user['last_name'])){
    $initials = strtoupper(substr($words[0],0,1) . substr($words[1],0,1));
} else {
    $initials = strtoupper(substr($name,0,1));
}


$message = "";

if(isset($_POST['update_profile'])){

    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name  = mysqli_real_escape_string($conn, $_POST['last_name']);

    $update = mysqli_query($conn,"
        UPDATE users
        SET first_name='$first_name',
            last_name='$last_name'
        WHERE id='$user_id'
    ");

    if($update){
        header("Location: account.php");
        exit;
    }else{
        $message = "Profile Update Failed";
    }
}


$colors = ["#ff6b6b","#6bcB77","#4d96ff","#f06595","#ffa94d","#845ef7"];
$bg = $colors[array_rand($colors)];
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Account</title>

<style>
body{
    background:#f5f5f5;
    font-family:Arial,sans-serif;
    padding-top:90px;
}

.header{
  padding:20px;
  background:#fff;
  font-size:20px;
  font-weight:bold;
}

.profile{
  background:#fff;
  padding:20px;
  display:flex;
  align-items:center;
  gap:15px;
}

.profile img{
  width:80px;
  height:80px;
  border-radius:50%;
  object-fit:cover;
}

.avatar{
  width:80px;
  height:80px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#fff;
  font-size:28px;
  font-weight:bold;
}

.profile form input{
  padding:8px;
  margin-top:5px;
}

.menu{
  background:#fff;
  margin-top:10px;
}

.menu a{
  display:flex;
  justify-content:space-between;
  padding:16px;
  text-decoration:none;
  color:#000;
  border-bottom:1px solid #eee;
}

.menu a:hover{
  background:#f2f2f2;
}

.logout{
  margin:20px;
}

.logout a{
  display:block;
  text-align:center;
  padding:15px;
  border:1px solid #000;
  border-radius:10px;
  text-decoration:none;
  color:#000;
}

.version{
  text-align:center;
  color:#777;
  font-size:12px;
  margin-bottom:20px;
}
</style>
</head>

<body>
 <?php include 'header.php'; ?>
<div class="header">My Account</div>

<div class="profile">

<?php if(!empty($user['profile_image'])){ ?>
  <img src="uploads/<?php echo $user['profile_image']; ?>">
<?php } else { ?>
  <div class="avatar" style="background:<?php echo $bg; ?>">
    <?php echo $initials; ?>
  </div>
<?php } ?>

<form method="POST" class="profile-form">

<input
type="text"
name="first_name"
value="<?php echo htmlspecialchars($user['first_name']); ?>"
placeholder="First Name">

<input
type="text"
name="last_name"
value="<?php echo htmlspecialchars($user['last_name']); ?>"
placeholder="Last Name">

<p style="color:#666;margin:10px 0;">
    <?php echo htmlspecialchars($user['mobile']); ?>
</p>

<button type="submit" name="update_profile">
    Update Profile
</button>

</form>

</div>

<div class="menu">
<a href="my-orders.php">📦 Orders <span>›</span></a>
<a href="customer-care.php">📞 Customer Care <span>›</span></a>
<a href="adresses1.php">🏠 Address <span>›</span></a>
<a href="notifications.php">🔔 Notifications<span>›</span></a>
<a href="how-to-return.php">↩ How To Return <span>›</span></a>
<a href="terms.php">📜 Terms & Conditions <span>›</span></a>
<a href="refund-policy.php">💸 Returns & Refund Policy <span>›</span></a>
<a href="fees-payments.php">💳 Fees & Payments <span>›</span></a>
</div>

<div class="logout">
  <a href="account.php?logout=1">Logout</a>
</div>

<div class="version">
  Version 1.0.30 Build 25
</div>
 <?php include 'footer.php'; ?>
</body>
</html>