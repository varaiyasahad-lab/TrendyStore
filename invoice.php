<?php
include "db.php";

if (!isset($_GET['order_code'])) {
  die("❌ Order Code missing");
}

$order_code = $_GET['order_code'];

$q = $conn->query("SELECT * FROM orders WHERE order_code='$order_code'");
if ($q->num_rows == 0) {
  die("❌ Order not found");
}

$o = $q->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Invoice | Trendy Store</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="card p-4">

    <h2>🧾 Invoice</h2>
    <hr>

    <p><b>Order Code:</b> <?php echo $o['order_code']; ?></p>
    <p><b>Name:</b> <?php echo $o['name']; ?></p>
    <p><b>Mobile:</b> <?php echo $o['mobile']; ?></p>
    <p><b>Address:</b> <?php echo $o['address']; ?></p>

    <hr>

    <p><b>Payment:</b> <?php echo $o['payment_method']; ?></p>
    <p><b>Status:</b> <?php echo $o['payment_status']; ?></p>
    <p><b>Total:</b> ₹<?php echo $o['total']; ?></p>

    <button onclick="window.print()" class="btn btn-success mt-3">
      🖨 Print Invoice
    </button>

  </div>
</div>

</body>
</html>
