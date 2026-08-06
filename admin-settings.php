<?php
session_start();

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin-login.php");
    exit;
}

include "db.php";


$message = "";

/* FETCH ADMIN */
$admin_id = $_SESSION['admin_id'];

$query = mysqli_query($conn, "SELECT * FROM admin WHERE id='$admin_id'");

if(!$query){
    die("Query Error: " . mysqli_error($conn));
}

$admin = mysqli_fetch_assoc($query);

/* PASSWORD CHANGE */
if(isset($_POST['change_password'])){

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    

if(
    !password_verify($old_password, $admin['password']) &&
    $old_password !== $admin['password']
){

    $message = "<div class='alert alert-danger'>
    Old Password Incorrect
    </div>";

}

    elseif($new_password != $confirm_password){

        $message = "<div class='alert alert-warning'>
        New Password & Confirm Password Not Match
        </div>";

    }else{

     $hash = password_hash($new_password, PASSWORD_DEFAULT);

$update = mysqli_query($conn,"
UPDATE admin
SET password='$hash'
WHERE id='$admin_id'
");

if($update){

    $admin['password'] = $hash;

    $message = "<div class='alert alert-success'>
    Password Updated Successfully
    </div>";

}else{

    $message = "<div class='alert alert-danger'>
    ".mysqli_error($conn)."
    </div>";
}
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Settings</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
    font-family:'Segoe UI',sans-serif;
}

.box{
    max-width:850px;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.profile-top{
    display:flex;
    align-items:center;
    gap:20px;
    margin-bottom:30px;
}

.profile-img{
    width:100px;
    height:100px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #ddd;
}

.info-box{
    background:#f8fafc;
    padding:20px;
    border-radius:15px;
    margin-bottom:25px;
}

.info-box p{
    margin:8px 0;
    font-size:16px;
}

h4{
    margin-bottom:20px;
}

</style>
</head>
<body>

<div class="box">

<a href="admin-dashboard.php" class="btn btn-secondary mb-4">
← Back Dashboard
</a>

<h2 class="mb-4">⚙ Admin Profile Settings</h2>

<?= $message ?>

<div class="profile-top">
    <img src="uploads/admin1.jpg" class="profile-img">

    <div>
     <h4>👨‍💼 <?= htmlspecialchars($admin['username']) ?></h4>
        <p>Manage your account settings</p>
    </div>
</div>

<div class="info-box">
    
    <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
    <p><strong>Role:</strong> Admin</p>
</div>

<hr>

<h4>🔒 Change Password</h4>

<form method="POST">

<div class="mb-3">
<label>Old Password</label>
<input type="password"
name="old_password"
class="form-control"
required>
</div>

<div class="mb-3">
<label>New Password</label>
<input type="password"
name="new_password"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Confirm Password</label>
<input type="password"
name="confirm_password"
class="form-control"
required>
</div>

<button
type="submit"
name="change_password"
class="btn btn-success">
Update Password
</button>

</form>

</div>

</body>
</html>