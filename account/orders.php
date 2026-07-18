<?php
session_start();
include "../db.php";

if(!isset($_SESSION['admin'])){
  header("location:login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Orders</title>
<style>
table{border-collapse:collapse;width:100%;}
th,td{border:1px solid #000;padding:8px;text-align:center;}
th{background:#eee;}
</style>
</head>
<body>

<h2>All Orders</h2>

<table>
<tr>
    <th>Payment</th>
<th>Status</th>
  <th>ID</th>
  <th>Name</th>
  <th>Mobile</th>
  <th>Address</th>
  <th>Total</th>
  <th>Date</th>
  <th>Order Status</th>
<th>Update</th>

</tr>

<?php
$q = $conn->query("SELECT * FROM orders ORDER BY id DESC");
while($row = $q->fetch_assoc()){
?>
<tr>
  <td><?php echo $row['id']; ?></td>
  <td><?php echo $row['name']; ?></td>
  <td><?php echo $row['mobile']; ?></td>
  <td><?php echo $row['address']; ?></td>
  <td>₹<?php echo $row['total']; ?></td>
  <td><?php echo $row['order_date']; ?></td>
  <td><?php echo $row['payment_method']; ?></td>
<td><?php echo $row['payment_status']; ?></td>
<td><?php echo $row['order_status']; ?></td>

<td>
  <form method="post" action="update-status.php">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <select name="status">
      <option <?php if($row['order_status']=="Pending") echo "selected"; ?>>Pending</option>
      <option <?php if($row['order_status']=="Shipped") echo "selected"; ?>>Shipped</option>
      <option <?php if($row['order_status']=="Delivered") echo "selected"; ?>>Delivered</option>
    </select>

    <button type="submit">Update</button>
  </form>
</td>


</tr>
<?php } ?>

</table>

<br>
<a href="dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>
