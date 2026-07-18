<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "db.php";

$msg = "";

/* SEND OTP */
if(isset($_POST['send_otp'])){

    $mobile = trim($_POST['mobile']);

    if(strlen($mobile) != 10){
        $msg = "Enter valid 10 digit mobile ❌";
    } else {

        $otp = rand(1000,9999);

        $_SESSION['otp'] = $otp;
        $_SESSION['mobile'] = $mobile;

        $apiKey = "DXwRY6FiCdSNyIrU0VEZGl74xoLmTpk1qKPJ8ezWOcasng39B2nwCxE5mNVthMOdgBzab798iPcqyR0Z"; // 🔑 apni Fast2SMS key

        $fields = array(
            "sender_id" => "FSTSMS",
            "message" => "Your OTP is $otp",
            "language" => "english",
            "route" => "otp",   // 🔥 IMPORTANT
            "numbers" => "$mobile"
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: $apiKey",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        if(curl_errno($curl)){
            $msg = "Server error ❌";
        } else {
            $msg = "OTP sent to $mobile ✅";
        }

        curl_close($curl);
    }
}

/* VERIFY OTP */
if(isset($_POST['verify_otp'])){

    $user_otp = $_POST['otp'];

    if(isset($_SESSION['otp']) && $user_otp == $_SESSION['otp']){

        $mobile = $_SESSION['mobile'];

        $check = $conn->query("SELECT * FROM users WHERE mobile='$mobile'");

        if($check->num_rows == 0){
            $conn->query("INSERT INTO users(name,mobile) VALUES('User','$mobile')");
        }

        $_SESSION['user'] = $mobile;

        header("Location: index.php");
        exit;

    } else {
        $msg = "Wrong OTP ❌";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>OTP Login</title>
<style>
body{font-family:Arial;background:#f2f2f2;text-align:center;padding-top:100px;}
.box{background:#fff;padding:30px;width:320px;margin:auto;border-radius:10px;}
input{width:100%;padding:10px;margin:10px 0;}
button{width:100%;padding:10px;background:black;color:white;border:none;}
.msg{color:green;}
</style>
</head>

<body>

<div class="box">
<h2>Login with OTP</h2>

<p class="msg"><?php echo $msg; ?></p>

<form method="post">
<input type="text" name="mobile" placeholder="Enter Mobile Number" required>
<button name="send_otp">Send OTP</button>
</form>

<form method="post">
<input type="text" name="otp" placeholder="Enter OTP">
<button name="verify_otp">Verify OTP</button>
</form>

</div>

</body>
</html>x