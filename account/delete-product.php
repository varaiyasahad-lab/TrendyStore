<?php
session_start();
include "../db.php";

if(!isset($_SESSION['admin'])){
  header("location:login.php");
}

$id = $_GET['id'];

$conn->query("DELETE FROM products WHERE id=$id");

header("location:products.php");
?>
