<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cloths";

$conn = new mysqli($host, $user, $pass, $db);

/* Connection check */
if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

/* Charset fix (important) */
$conn->set_charset("utf8mb4");
