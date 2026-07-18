    <?php
require_once __DIR__ . '/auth_check.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
</head>
<body>

<h2>Welcome <?php echo $_SESSION['user']; ?></h2>

<a href="add_product.php">➕ Add Product</a><br><br>
<a href="orders.php">📦 View Orders</a><br><br>
<a href="products.php">📦 Manage Products</a><br><br>
<a href="../auth.php?action=logout">🚪 Logout</a>


</body>
</html>
