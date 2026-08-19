<?php
session_start();
include "db.php";

/* ====== SESSION MESSAGES ====== */
$login_msg = $_SESSION['login_error'] ?? "";
$register_msg = $_SESSION['register_error'] ?? "";
unset($_SESSION['login_error'], $_SESSION['register_error']);

/* ====== REGISTER ====== */
if (isset($_POST['register'])) {

    $fname  = trim($_POST['fname']);
    $lname  = trim($_POST['lname']);
    $email  = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $pass   = $_POST['password'];
    $cpass  = $_POST['cpassword'];

    if($pass != $cpass){
        $_SESSION['register_error']="Passwords do not match";
        header("Location: auth.php");
        exit;
    }

    $stmt=$conn->prepare("SELECT id FROM users WHERE mobile=?");
    $stmt->bind_param("s",$mobile);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows>0){
        $_SESSION['register_error']="Mobile already exists";
        header("Location: auth.php");
        exit;
    }

    $hash=password_hash($pass,PASSWORD_DEFAULT);

    $stmt=$conn->prepare("INSERT INTO users(first_name,last_name,email,mobile,password)
    VALUES(?,?,?,?,?)");

    $stmt->bind_param("sssss",$fname,$lname,$email,$mobile,$hash);

    if($stmt->execute()){

        $_SESSION['user_id']=$stmt->insert_id;
        $_SESSION['user_name']=$fname." ".$lname;

        header("Location:index.php");
        exit;

    }else{

        die($stmt->error);

    }

}


/* ===== LOGIN ===== */

if(isset($_POST['login'])){

    $mobile=trim($_POST['mobile']);
    $password=$_POST['password'];

    $stmt=$conn->prepare("SELECT id,first_name,last_name,password
    FROM users
    WHERE mobile=?");

    $stmt->bind_param("s",$mobile);
    $stmt->execute();

    $result=$stmt->get_result();

    $user=$result->fetch_assoc();

    if($user){

        if(password_verify($password,$user['password'])){

            $_SESSION['user_id']=$user['id'];

            $_SESSION['user_name']=$user['first_name']." ".$user['last_name'];

            header("Location:index.php");
            exit;

        }else{

            $_SESSION['login_error']="Wrong Password";

        }

    }else{

        $_SESSION['login_error']="Mobile Not Found";

    }

    header("Location:auth.php");
    exit;

}

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Login | Trendy Store</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;
    background:linear-gradient(135deg,#eef2ff,#ffffff,#f5f5f5);
    display:flex;
    justify-content:center;
    align-items:center;
    padding:30px;
}

.container{
    width:1100px;
    max-width:100%;
    min-height:680px;
    display:flex;
    background:#fff;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 25px 60px rgba(0,0,0,.18);
}

/* LEFT */

.left{
    width:45%;
    position:relative;
    background:url("uploads/login-bg.png") center center no-repeat;
      background-size: cover;
}

.left::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(rgba(0,0,0,.35),rgba(0,0,0,.55));
}

.overlay{
    position:relative;
    z-index:2;
    height:100%;
    color:#fff;
    display:flex;
    flex-direction:column;
    justify-content:center;
    padding:60px;
}

.overlay h4{
    letter-spacing:4px;
    font-size:15px;
    margin-bottom:15px;
}

.overlay h1{
    font-size:48px;
        line-height:1.2;
        margin-bottom:20px;
    font-style: italic;
}

.overlay p{
    line-height:1.8;
    opacity:.9;
    margin-bottom:40px;
}

.feature{
    display:flex;
    gap:20px;
}

.feature div{
    width:110px;
    height:110px;
    border-radius:18px;
    background:rgba(255,255,255,.12);
    backdrop-filter:blur(8px);
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}

.feature i{
    font-size:30px;
    margin-bottom:12px;
}

.feature p{
    margin:0;
    text-align:center;
    font-size:13px;
}

/* RIGHT */

.right{
    width:55%;
    background:#fff;
    padding:45px;
    position:relative;
}

.close{
    position:absolute;
    right:20px;
    top:18px;
    width:40px;
    height:40px;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    color:#333;
    text-decoration:none;
    transition:.3s;
}

.close:hover{
    background:#eee;
}

/* Tabs */

.tabs{
    display:flex;
    background:#f3f3f3;
    border-radius:12px;
    overflow:hidden;
    margin-bottom:30px;
}

.tabs button{
    flex:1;
    border:none;
    padding:15px;
    background:none;
    cursor:pointer;
    font-size:16px;
    font-weight:600;
    transition:.3s;
}

.tabs .active{
    background:#111;
    color:#fff;
}

/* Inputs */

input{
    width:100%;
    height:55px;
    border:1px solid #ddd;
    border-radius:12px;
    padding:0 18px;
    margin-bottom:18px;
    font-size:15px;
    transition:.3s;
}

input:focus{
    border-color:#111;
    outline:none;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}

.input{
    position:relative;
}

.input i:first-child{
    position:absolute;
    left:18px;
    top:50%;
    transform:translateY(-50%);
    color:#888;
}

.input input{
    padding:0 45px 0 50px;
    margin-bottom:15px;
}

.input{
    position:relative;
}

.input input{
    width:100%;
    height:55px;
    padding-left:50px;
    padding-right:55px;
    margin-bottom:0;
}

.eye{
    position:absolute;
    right:18px;
    top:50%;
    transform:translateY(-50%);
    width:20px;
    height:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    color:#777;
}

.double{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}

/* Row */

.row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin:15px 0 25px;
    font-size:14px;
}

.row a{
    color:#111;
    text-decoration:none;
}

.row label{
    display:flex;
    align-items:center;
    gap:8px;
}

.row input{
    width:auto;
    height:auto;
    margin:0;
}

/* Button */

.btn{
    width:100%;
    height:55px;
    border:none;
    border-radius:12px;
    background:#111;
    color:#fff;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

.btn:hover{
    background:#333;
    transform:translateY(-2px);
}

.or{
    text-align:center;
    margin:25px 0;
    position:relative;
}

.or span{
    background:#fff;
    padding:0 15px;
    position:relative;
    z-index:2;
}

.or::before{
    content:"";
    position:absolute;
    left:0;
    top:50%;
    width:100%;
    height:1px;
    background:#ddd;
}

/* Social */



.footer{
    margin-top:25px;
    text-align:center;
    color:#777;
    font-size:14px;
}

/* Responsive */

@media(max-width:900px){

.container{
    flex-direction:column;
}

.left{
    width:100%;
    height:280px;
}

.right{
    width:100%;
}

.double{
    grid-template-columns:1fr;
}

.overlay{
    padding:30px;
}

.overlay h1{
    font-size:34px;
}

.feature{
    flex-wrap:wrap;
}

}

#loginForm .input{
    margin-bottom:20px;
}
#registerForm .input{
    margin-bottom:20px;
}

#registerForm > input{
    margin-bottom:20px;
}
.msg{
    color: #dc3545;
    font-size: 15px;
    font-weight: 600;
    margin: 10px 0;
}
</style>

</head>

<body>

<div class="container">

<!-- LEFT -->

<div class="left">

<div class="overlay">

<h4>TRENDY STORE</h4>

<h1>Welcome To <br>Trendy Store</h1>

<p>

Discover latest fashion collections,

premium quality clothing,

and amazing offers.

</p>

<div class="feature">

<div>

<i class="fa-solid fa-shield"></i>

<p>Secure Shopping</p>

</div>

<div>

<i class="fa-solid fa-truck-fast"></i>

<p>Fast Delivery</p>

</div>

<div>

<i class="fa-solid fa-credit-card"></i>

<p>Safe Payment</p>

</div>

</div>

</div>

</div>

<!-- RIGHT -->

<div class="right">

<a href="index.php" class="close">

<i class="fa-solid fa-xmark"></i>

</a>

<div class="tabs">

<button id="loginBtn"

class="active"

onclick="showLogin()">

Login

</button>

<button

id="registerBtn"

onclick="showRegister()">

Register

</button>

</div>

<!-- LOGIN -->

<form

method="POST"

id="loginForm">

<?php

if($login_msg)

echo "<div class='msg'>$login_msg</div>";

?>

<div class="input">

<i class="fa-solid fa-phone"></i>

<input

type="text"

name="mobile"

placeholder="Mobile Number"

required>

</div>

<div class="input">

<i class="fa-solid fa-lock"></i>

<input

type="password"

id="loginPass"

name="password"

placeholder="Password"

required>

<span

onclick="togglePass('loginPass',this)"

class="eye">

<i class="fa-solid fa-eye"></i>

</span>

</div>



<button

class="btn"

name="login">

Login

</button>



</form>

<!-- REGISTER -->

<form

method="POST"

id="registerForm"

style="display:none;">

<?php

if($register_msg)

echo "<div class='msg'>$register_msg</div>";

?>

<div class="double">

<input

type="text"

name="fname"

placeholder="First Name"

required>

<input

type="text"

name="lname"

placeholder="Last Name"

required>

</div>

<input

type="email"

name="email"

placeholder="Email Address"

required>

<input

type="text"

name="mobile"

placeholder="Mobile Number"

required>

<div class="input">

<i class="fa-solid fa-lock"></i>

<input

type="password"

id="regPass"

name="password"

placeholder="Password"

required>

<span

class="eye"

onclick="togglePass('regPass',this)">

<i class="fa-solid fa-eye"></i>

</span>

</div>

<div class="input">

<i class="fa-solid fa-lock"></i>

<input

type="password"

id="confirmPass"

name="cpassword"

placeholder="Confirm Password"

required>

<span

class="eye"

onclick="togglePass('confirmPass',this)">

<i class="fa-solid fa-eye"></i>

</span>

</div>

<button

class="btn"

name="register">

Create Account

</button>

</form>

<p class="footer">

Secure Login • Trendy Store

</p>

</div>

</div>

<script>

const loginForm=document.getElementById("loginForm");
const registerForm=document.getElementById("registerForm");

const loginBtn=document.getElementById("loginBtn");
const registerBtn=document.getElementById("registerBtn");

function showLogin(){

loginForm.style.display="block";
registerForm.style.display="none";

loginBtn.classList.add("active");
registerBtn.classList.remove("active");

}

function showRegister(){

loginForm.style.display="none";
registerForm.style.display="block";

registerBtn.classList.add("active");
loginBtn.classList.remove("active");

}

function togglePass(id,el){

let input=document.getElementById(id);

let icon=el.querySelector("i");

if(input.type=="password"){

input.type="text";

icon.classList.remove("fa-eye");
icon.classList.add("fa-eye-slash");

}else{

input.type="password";

icon.classList.remove("fa-eye-slash");
icon.classList.add("fa-eye");

}

}

<?php if($register_msg){ ?>

showRegister();

<?php } ?>

<?php if($login_msg){ ?>

showLogin();

<?php } ?>

</script>

</body>

</html>