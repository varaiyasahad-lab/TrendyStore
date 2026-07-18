<?php
session_start();
require_once "../db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id='$user_id'")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Account</title>

<style>
body{
  margin:0;
  font-family:Arial;
  background:#f5f5f5;
}

.topbar{
  background:#fff;
  padding:16px;
  font-size:18px;
  font-weight:bold;
  border-bottom:1px solid #eee;
}

.section{
  background:#fff;
  margin-top:10px;
  padding:16px;
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

.back{
  text-decoration:none;
  color:#000;
  font-size:14px;
}
</style>
</head>
<body>