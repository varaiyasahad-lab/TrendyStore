<?php
session_start();

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin-login.php");
    exit;
}

include "db.php";

/* DELETE USER */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];

    mysqli_query($conn,"
    DELETE FROM users
    WHERE id='$id'
    ");

    header("Location: admin-users.php");
    exit;
}

/* SEARCH */
$search = $_GET['search'] ?? '';

$where = "";

if(!empty($search)){
    $search = mysqli_real_escape_string($conn,$search);

    $where = "
    WHERE first_name LIKE '%$search%'
    OR last_name LIKE '%$search%'
    OR email LIKE '%$search%'
    ";
}

/* PAGINATION */
$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1){
    $page = 1;
}

$start = ($page - 1) * $limit;

/* TOTAL USERS */
$totalRes = mysqli_query($conn,"
SELECT COUNT(*) total
FROM users
$where
");

$totalRow = mysqli_fetch_assoc($totalRes);

$totalUsers = $totalRow['total'];

$totalPages = ceil($totalUsers / $limit);

/* FETCH USERS */
$users = mysqli_query($conn,"
SELECT *
FROM users
$where
ORDER BY id DESC
LIMIT $start,$limit
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Users</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
    padding:30px;
}

.box{
    background:#fff;
    padding:20px;
    border-radius:15px;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
}
</style>
</head>
<body>

<div class="box">

<a href="javascript:history.back()" class="btn btn-secondary mb-3">
← Back
</a>

<h2>👥 Users Management</h2>

<hr>

<!-- SEARCH -->
<form method="GET" class="mb-4">
<div class="input-group">
<input type="text"
name="search"
class="form-control"
placeholder="Search users..."
value="<?= $_GET['search'] ?? '' ?>">

<button class="btn btn-dark">Search</button>
</div>
</form>

<table class="table table-bordered table-hover">

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Mobile</th>
<th>Address</th>
<th>Created</th>
<th>Action</th>
</tr>

<?php while($u=mysqli_fetch_assoc($users)){ ?>

<tr>

<td><?= $u['id'] ?></td>

<td>
<?= $u['first_name'] ?>
<?= $u['last_name'] ?>
</td>

<td><?= $u['email'] ?></td>

<td><?= $u['mobile'] ?></td>

<td><?= $u['address'] ?></td>

<td><?= $u['created_at'] ?></td>

<td>
<a href="?delete=<?= $u['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this user?')">
Delete
</a>
</td>

</tr>

<?php } ?>

</table>

<!-- PAGINATION -->
<nav>
<ul class="pagination justify-content-center">

<?php for($i=1;$i<=$totalPages;$i++){ ?>

<li class="page-item <?= ($page==$i)?'active':'' ?>">
<a class="page-link"
href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
<?= $i ?>
</a>
</li>

<?php } ?>

</ul>
</nav>

</div>

</body>
</html>