    <?php
    session_start();
    include "db.php";

    if(!isset($_SESSION['user_id'])){
        header("Location: auth.php");
        exit;
    }

    $user_id = (int)$_SESSION['user_id'];
    $product_id = (int)($_GET['id'] ?? 0);

    if($product_id > 0){

        // check already exists
        $check = $conn->query("SELECT * FROM wishlist WHERE user_id=$user_id AND product_id=$product_id");

        if($check->num_rows > 0){
            // 🔴 REMOVE
            $conn->query("DELETE FROM wishlist WHERE user_id=$user_id AND product_id=$product_id");
        } else {
            // 🟢 ADD
            $conn->query("INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)");
        }
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
    ?>