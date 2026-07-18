<?php
session_start();
require_once __DIR__ . '/../db.php';

if(!isset($_SESSION['admin'])){
  header("Location: auth.php");
  exit;
}

$res = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<h2>Product List</h2>
<a href="add-product.php">➕ Add Product</a><br><br>

<table border="1" cellpadding="10">
<tr>
  <th>Image</th>
  <th>Name</th>
  <th>Category</th>
  <th>Price</th>
  <th>Action</th>
</tr>

<?php while($p = $res->fetch_assoc()){ ?>
<tr>
  <td><img src="../uploads/<?= $p['image'] ?>" width="70"></td>
  <td><?= $p['name'] ?></td>
  <td><?= $p['category'] ?></td>
  <td>₹<?= $p['price'] ?></td>
  <td>
    <a href="edit-product.php?id=<?= $p['id'] ?>">Edit</a> |
    <a href="delete-product.php?id=<?= $p['id'] ?>"
       onclick="return confirm('Delete product?')">Delete</a>
  </td>
</tr>
<?php } ?>
</table>
