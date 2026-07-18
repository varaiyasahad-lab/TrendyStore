     <?php
    session_start();
    include "db.php";

    /* login check */
    if(!isset($_SESSION['user_id'])){
      header("Location: auth.php");
      exit;
    }

    $user_id = (int)$_SESSION['user_id'];
    $product_id = (int)($_GET['id'] ?? 0);

    /* delete */
    if($product_id > 0){
      $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id=? AND product_id=?");
      $stmt->bind_param("ii", $user_id, $product_id);
      $stmt->execute();
    }

    /* back to previous page */
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "wishlist.php"));
    exit;
    ?>