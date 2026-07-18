<?php
session_start();
include "db.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $product_id = (int)($_POST['product_id'] ?? 0);

    $name = mysqli_real_escape_string(
        $conn,
        $_POST['name'] ?? ''
    );

    $rating = (int)($_POST['rating'] ?? 0);

    $comment = mysqli_real_escape_string(
        $conn,
        $_POST['comment'] ?? ''
    );

    if($product_id <= 0){
        die("Invalid Product");
    }

    /* INSERT REVIEW */

    mysqli_query($conn,"
    INSERT INTO reviews
    (product_id,name,rating,comment)
    VALUES
    (
    '$product_id',
    '$name',
    '$rating',
    '$comment'
    )
    ");

    /* UPDATE PRODUCT AVG RATING */

    $avg_q = mysqli_query($conn,"
    SELECT AVG(rating) as avg_rating
    FROM reviews
    WHERE product_id='$product_id'
    ");

    $avg_data = mysqli_fetch_assoc($avg_q);

    $new_rating = round($avg_data['avg_rating'],1);

    mysqli_query($conn,"
    UPDATE products
    SET rating='$new_rating'
    WHERE id='$product_id'
    ");

    header("Location: product-detail.php?id=$product_id");
    exit;
}
?>