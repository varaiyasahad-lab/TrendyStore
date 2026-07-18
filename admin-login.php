<?php
session_start();

$error = "";

if(isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(

        ($username == "Sahad Varaiya" && $password == "Sahad@2007")

        ||

        ($username == "Sahad Varaiya" && $password == "Sahad@222")

    ){

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $username;

        header("Location: admin-dashboard.php");
        exit;

    }else{

        $error = "Invalid Username or Password";

    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Admin Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    margin:0;
    background:#f4f6f9;
    font-family:Arial,sans-serif;
}

.login-wrap{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.login-box{
    width:100%;
    max-width:420px;
    background:#fff;
    padding:35px;
    border-radius:20px;
    box-shadow:0 5px 25px rgba(0,0,0,.12);
}

.logo{
    text-align:center;
    font-size:55px;
    margin-bottom:10px;
}

.title{
    text-align:center;
    font-size:28px;
    font-weight:700;
    margin-bottom:5px;
}

.sub{
    text-align:center;
    color:#666;
    margin-bottom:25px;
}

.form-control{
    height:50px;
}

.btn-login{
    width:100%;
    height:50px;
    font-size:16px;
    font-weight:bold;
}

.footer-text{
    text-align:center;
    margin-top:15px;
    color:#888;
    font-size:13px;
}

</style>

</head>

<body>

<div class="login-wrap">

<div class="login-box">

<div class="logo">🔐</div>

<div class="title">
Admin Panel
</div>

<div class="sub">
Login to continue
</div>

<?php if(!empty($error)){ ?>

<div class="alert alert-danger">
<?= $error ?>
</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">
Username
</label>

<input
type="text"
name="username"
class="form-control"
placeholder="Enter Username"
required>

</div>

<div class="mb-3">

<label class="form-label">
Password
</label>

<input
type="password"
name="password"
class="form-control"
placeholder="Enter Password"
required>

</div>

<button
type="submit"
name="login"
class="btn btn-dark btn-login">

Login

</button>

</form>

<div class="footer-text">

Trendy Store Admin Panel

</div>

</div>

</div>

</body>
</html>