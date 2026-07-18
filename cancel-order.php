<?php
include "db.php";

if(!isset($_POST['order_code'])){
    die("Invalid Request");
}

$order_code = $conn->real_escape_string($_POST['order_code']);

/* =========================
   IF CONFIRM CANCEL
========================= */
if(isset($_POST['confirm_cancel'])){

    $reason = $conn->real_escape_string($_POST['reason']);

    $conn->query("
        UPDATE orders 
        SET order_status='Cancelled',
            cancel_reason='$reason'
        WHERE order_code='$order_code'
    ");

    header("Location: track-order.php?order_code=$order_code");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Cancel Order</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card p-4 shadow">

<h4>❌ Cancel Order</h4>
<p>Select Reason for Cancellation:</p>

<form method="POST">

<input type="hidden" name="order_code" value="<?= $order_code ?>">

<div class="form-check">
  <input class="form-check-input" type="radio" name="reason" value="Ordered by mistake" required>
  <label class="form-check-label">Ordered by mistake</label>
</div>

<div class="form-check">
  <input class="form-check-input" type="radio" name="reason" value="Found cheaper elsewhere">
  <label class="form-check-label">Found cheaper elsewhere</label>
</div>

<div class="form-check">
  <input class="form-check-input" type="radio" name="reason" value="Shipping time too long">
  <label class="form-check-label">Shipping time too long</label>
</div>

<div class="form-check">
  <input class="form-check-input" type="radio" name="reason" value="Wrong address selected">
  <label class="form-check-label">Wrong address selected</label>
</div>

<div class="form-check">
  <input class="form-check-input" type="radio" name="reason" value="Payment issue">
  <label class="form-check-label">Payment issue</label>
</div>

<div class="form-check mb-3">
  <input class="form-check-input" type="radio" name="reason" value="Other">
  <label class="form-check-label">Other</label>
</div>

<button type="submit" name="confirm_cancel" class="btn btn-danger w-100">
  Confirm Cancel
</button>

</form>

</div>
</div>

</body>
</html>
