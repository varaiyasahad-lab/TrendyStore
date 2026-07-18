<?php
session_start();
include "db.php";

/* ====== SESSION MESSAGES ====== */
$login_msg = $_SESSION['login_error'] ?? "";
$register_msg = $_SESSION['register_error'] ?? "";
unset($_SESSION['login_error'], $_SESSION['register_error']);

/* ====== REGISTER ====== */
if (isset($_POST['register'])) {
    $fname   = trim($_POST['fname']);
    $lname   = trim($_POST['lname']);
    $email   = trim($_POST['email']);
    $mobile  = trim($_POST['mobile']);
    $pass    = $_POST['password'];
    $cpass   = $_POST['cpassword'];

    if ($pass !== $cpass) {
        $_SESSION['register_error'] = "Passwords do not match";
        header("Location: auth.php"); exit;
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE mobile=?");
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['register_error'] = "Mobile already exists";
        header("Location: auth.php"); exit;
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (first_name,last_name,email,mobile,password) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $fname, $lname, $email, $mobile, $hash);

   if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['user_name'] = $fname . " " . $lname;
    header("Location: index.php");
    exit;
} else {
    die("Register Error: " . $stmt->error);
}
}
/* ====== LOGIN ====== */
if (isset($_POST['login'])) {
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("
        SELECT id, first_name, last_name, password 
        FROM users 
        WHERE mobile=?
    ");
    $stmt->bind_param("s", $mobile);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name']." ".$user['last_name'];
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Wrong password";
        }
    } else {
        $_SESSION['login_error'] = "Mobile not found";
    }

    header("Location: auth.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login / Register</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

/* ===== BASE ===== */
body{
  margin:0;
  font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto;
  background:#f3f4f6;
}

/* ===== CENTER ===== */
.wrapper{
  display:flex;
  justify-content:center;
  align-items:center;
  min-height:100vh;
}

/* ===== CARD ===== */
.card{
  width:100%;
  max-width:420px;
  background:#fff;
  border-radius:16px;
  padding:25px;
  box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

/* ===== TABS ===== */
.tabs{
  display:flex;
  background:#f1f1f1;
  border-radius:10px;
  overflow:hidden;
  margin-bottom:20px;
}

.tabs button{
  flex:1;
  padding:12px;
  border:none;
  background:none;
  font-weight:600;
  cursor:pointer;
}

.tabs .active{
  background:#000;
  color:#fff;
}

/* ===== INPUT ===== */
.input{
  position:relative;
}

.input input{
  width:100%;
  padding:14px;
  margin:8px 0;
  border-radius:8px;
  border:1px solid #ddd;
  font-size:14px;
}

.input input:focus{
  border-color:#000;
  outline:none;
}

/* ===== BUTTON ===== */
.btn{
  width:100%;
  padding:14px;
  margin-top:10px;
  background:#000;
  color:#fff;
  border:none;
  border-radius:8px;
  font-weight:600;
  cursor:pointer;
  transition:0.3s;
}

.btn:hover{
  background:#333;
}

/* ===== MSG ===== */
.msg{
  text-align:center;
  color:#e63946;
  margin-bottom:10px;
}

/* ===== FOOT ===== */
.footer{
  text-align:center;
  margin-top:15px;
  font-size:13px;
  color:#777;
}

/* ===== MOBILE ===== */
@media(max-width:480px){
  .card{
    margin:15px;
    padding:20px;
  }
}
.input{
  position:relative;
  width:100%;
}

.input input{
  width:100%;
  padding:10px 10px 10px 5px;
  margin:8px 0;
  border-radius:8px;
  border:1px solid #ddd;
  font-size:14px;
}

.eye{
  position:absolute;
  right:15px;
  top:50%;
  transform:translateY(-50%);
  cursor:pointer;
  font-size:18px;
  z-index:2;
}
.card{
  position: relative;
}

.close-btn{
  position:absolute;
  top:1px;
  right:10px;
  width:10px;
  height:10px;
  display:flex;
  align-items:center;
  justify-content:center;
  text-decoration:none;
  font-size:22px;
  color:#555;
  border-radius:50%;
  transition:0.2s;
}
.close-btn:hover{
  background:#f2f2f2;
  color:#000;
}
</style>
</head>

<body>

<div class="wrapper">

<div class="card">

<a href="index.php" class="close-btn">&times;</a>

<div class="tabs">
  <button id="loginBtn" class="active" onclick="showLogin()">Login</button>
  <button id="regBtn" onclick="showRegister()">Register</button>
</div>

<!-- LOGIN -->
  <form method="POST" id="loginForm">
  <?php if($login_msg) echo "<div class='msg'>$login_msg</div>"; ?>

<div class="input">
  <input 
    type="text" 
    name="mobile" 
    placeholder="Mobile Number" 
    autocomplete="off"
    required>
</div>
<div class="input pass-box">
  <input 
    type="password" 
    id="loginPass"
    name="password" 
    placeholder="Password" 
    autocomplete="new-password"
    required>

  <span class="eye" onclick="togglePass('loginPass', this)">👁</span>
</div>

  <button class="btn" name="login">Login</button>
</form>

<!-- REGISTER -->
<form method="POST" id="regForm" style="display:none;">
  <?php if($register_msg) echo "<div class='msg'>$register_msg</div>"; ?>
<div class="input">
  <input type="text" name="fname" placeholder="First Name" autocomplete="off" required>
</div>

<div class="input">
  <input type="text" name="lname" placeholder="Last Name" autocomplete="off" required>
</div>


<div class="input">
  <input type="email" name="email" placeholder="Email Address" required>
</div>


<div class="input">
  <input type="text" name="mobile" placeholder="Mobile Number" autocomplete="off" required>
</div>
<div class="input pass-box">
  <input 
    type="password" 
    id="regPass"
    name="password" 
    placeholder="Password" 
    autocomplete="new-password"
    required>

  <span class="eye" onclick="togglePass('regPass', this)">👁</span>
</div>

<div class="input pass-box">
  <input 
    type="password" 
    id="confirmPass"
    name="cpassword" 
    placeholder="Confirm Password" 
    autocomplete="new-password"
    required>

  <span class="eye" onclick="togglePass('confirmPass', this)">👁</span>
</div>
  <button class="btn" name="register">Create Account</button>
</form>

<div class="footer">
  Secure login • Trendy Store
</div>

</div>
</div>

<script>
function showRegister(){
  loginForm.style.display="none";
  regForm.style.display="block";
  loginBtn.classList.remove("active");
  regBtn.classList.add("active");
}

function showLogin(){
  loginForm.style.display="block";
  regForm.style.display="none";
  loginBtn.classList.add("active");
  regBtn.classList.remove("active");
}

<?php if($register_msg){ ?> showRegister(); <?php } ?>
<?php if($login_msg){ ?> showLogin(); <?php } ?>
function togglePass(id, icon){
  let input = document.getElementById(id);

  if(input.type === "password"){
    input.type = "text";
    icon.innerHTML = "🙈";
  }else{
    input.type = "password";
    icon.innerHTML = "👁";
  }
}
</script>

</body>
</html>