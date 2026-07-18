<?php
include "db.php";

$q = strtolower(trim($_GET['q'] ?? ''));
if($q == '') exit;

/* 🔥 CATEGORY SUGGESTIONS FIRST */
$categories = ["shirt", "jeans", "tshirt"];

foreach($categories as $cat){
    if(strpos($cat, $q) !== false){
        echo "<div onclick=\"pickSearch('$cat')\">🔍 ".ucfirst($cat)."</div>";
    }
}

/* 🔥 PRODUCT SUGGESTIONS */
$stmt = $conn->prepare("
    SELECT name
    FROM products
    WHERE LOWER(name) LIKE ?
    ORDER BY name ASC
    LIMIT 10
");

$likeStart = $q . "%";

$stmt->bind_param("s", $likeStart);
$stmt->execute();
$res = $stmt->get_result();

while($row = $res->fetch_assoc()){
    $name = htmlspecialchars($row['name']);
    echo "<div onclick=\"pickSearch('$name')\">🔍 $name</div>";
}
?>