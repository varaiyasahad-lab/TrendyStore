<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_id'])){
    echo "Login Required";
    exit;
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['save'])){
    $address = $_POST['address'];
    $conn->query("UPDATE users SET address='$address' WHERE id='$user_id'");
    echo "<script>alert('Address Saved');</script>";
}

$user = $conn->query("SELECT * FROM users WHERE id='$user_id'")->fetch_assoc();
?>

<h2>My Address</h2>

<form method="post">
<textarea name="address" style="width:100%;height:120px;">
<?php echo $user['address']; ?>
</textarea>

<br><br>

<button name="save">Save Address</button>
</form>