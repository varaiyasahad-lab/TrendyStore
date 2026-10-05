    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();
    include "db.php";

    if (!isset($_SESSION['user_id'])) {
        die("Login Required");
    }

    if (empty($_SESSION['cart']) && empty($_SESSION['buy_now'])) {
        die("No Product Found");
    }

    $user_id = $_SESSION['user_id'];

    $getAddress = mysqli_query($conn,"
    SELECT *
    FROM addresses
    WHERE user_id='$user_id'
    ORDER BY id DESC
    LIMIT 1
    ");

    $addr = mysqli_fetch_assoc($getAddress);
    

    $name    = $conn->real_escape_string($addr['fullname']);
    $mobile  = $conn->real_escape_string($addr['mobile']);
    $address = $conn->real_escape_string($addr['address']);
    $total = 0;

    if(!empty($_SESSION['cart'])){

        foreach ($_SESSION['cart'] as $item) {

            if(isset($item['price'], $item['qty'])){

                $total += $item['price'] * $item['qty'];
            }
        }
    }

    $product = null;

    if(!empty($_SESSION['buy_now'])){

        $buy = $_SESSION['buy_now'];

        $id  = (int)($buy['id'] ?? 0);
        $qty = (int)($buy['qty'] ?? 1);

        $q = $conn->query("
        SELECT *
        FROM products
        WHERE id='$id'
        ");

        if($q && $q->num_rows > 0){

            $product = $q->fetch_assoc();

            $total += $product['price'] * $qty;

        }else{

            die("Invalid Product");
        }
    }

    if($total <= 0){

        die("Total Calculation Failed");
    }



$voucher = $_POST['coupon_select'] ?? ($_POST['voucher_code'] ?? '');
$discount = 0;

$firstOrderQ = $conn->query("
SELECT id FROM orders
WHERE user_id='$user_id'
LIMIT 1
");

if($voucher == "WELCOME100"){

    if($firstOrderQ->num_rows > 0){
        $_SESSION['voucher_error'] = "Already redeemed";
        header("Location: payment.php");
        exit;
    }

    $discount = 100;
}

elseif($voucher == "SAVE100" && $total >= 899){
    $discount = 100;
}

elseif($voucher == "SAVE200" && $total >= 1099){
    $discount = 200;
}

elseif($voucher == "SAVE300" && $total >= 1599){
    $discount = 300;
}

/* INVALID */
else{
    if(!empty($voucher)){
        $_SESSION['voucher_error'] = "Invalid voucher";
        header("Location: payment.php");
        exit;
    }
}

$final_total = $total - $discount;

if($final_total < 0){
    $final_total = 0;
}
    /* ======================
    PAYMENT
    ====================== */

    $payment_method = $conn->real_escape_string(
    $_POST['payment_method'] ?? 'COD'
    );

    $payment_status =
    ($payment_method == 'COD')
    ? 'Pending'
    : 'Paid';

    /* ======================
    ORDER CODE
    ====================== */

    $order_code =
    "ORD" . strtoupper(substr(uniqid(), 7)) . rand(100,999);

    /* ======================
    TRACKING ID
    ====================== */

    $tracking_id =
    "TRK" . strtoupper(substr(uniqid(), 7)) . rand(100,999);

    /* ======================
    IMAGE
    ====================== */

    $image = '';

    if(!empty($_SESSION['cart'])){

        $first = reset($_SESSION['cart']);

        $image = $first['image'] ?? '';
    }

    if(!empty($_SESSION['buy_now']) && $product){

        $image = $product['image'] ?? '';
    }

    /* ======================
    PRODUCT INFO
    ====================== */

    $product_name = '';
    $product_id   = 0;

    if(!empty($_SESSION['cart'])){

        $first = reset($_SESSION['cart']);

        $product_id = (int)($first['id'] ?? 0);

        $product_name =
        $conn->real_escape_string(
        $first['name'] ?? ''
        );
    }

    if(!empty($_SESSION['buy_now'])){

        $product_id = (int)($buy['id'] ?? 0);

        $product_name =
        $conn->real_escape_string(
        $buy['name'] ?? ''
        );
    }

    /* ======================
    INSERT ORDER
    ====================== */

    $order_sql = "
    INSERT INTO orders
    (
    user_id,
    order_code,
    tracking_id,
    name,
    mobile,
    address,
    total,
    total_amount,
    payment_method,
    payment_status,
    order_status,
    image,
    voucher_code
    )
    VALUES
    (
    '$user_id',
    '$order_code',
    '$tracking_id',
    '$name',
    '$mobile',
    '$address',
   '$total',
".(int)$final_total.",
    '$payment_method',
    '$payment_status',
    'confirmed',
    '$image',
    '$voucher'
    )
    ";

if(!$conn->query($order_sql)){
    
    die("MYSQL ERROR: " . $conn->error);
}
    $order_id = $conn->insert_id;


    /* ======================
    NOTIFICATION
    ====================== */

    $conn->query("
    INSERT INTO notifications
    (
    user_id,
    title,
    message,
    product_name,
    product_image,
    product_id
    )
    VALUES
    (
    '$user_id',
    'Order Placed',
    'Your order has been placed successfully',
    '$product_name',
    '$image',
    '$product_id'
    )
    ");

    /* ======================
    CART ORDER
    ====================== */

    if(!empty($_SESSION['cart'])){

        foreach ($_SESSION['cart'] as $item) {

            $product_id =
            (int)($item['id'] ?? 0);

            $price =
            (int)($item['price'] ?? 0);

            $qty =
            (int)($item['qty'] ?? 1);

            $size = $conn->real_escape_string(
            $item['size'] ?? ''
            );

            $color = $conn->real_escape_string(
            $item['color'] ?? ''
            );

            if($product_id > 0){

                /* CHECK STOCK */

                $stock_q = $conn->query("
SELECT stock
FROM product_sizes
WHERE product_id='$product_id'
AND TRIM(size)=TRIM('$size')
");

/* size exists? */
if(!$stock_q || $stock_q->num_rows == 0){
    die("Size not found");
}

$stock_row = $stock_q->fetch_assoc();
$current_stock = (int)$stock_row['stock'];

/* stock check */
if($current_stock <= 0){
    die("Out Of Stock");
}

if($qty > $current_stock){
    die("Only $current_stock left in stock");
}
                /* INSERT ORDER ITEM */

                $conn->query("
                INSERT INTO order_items
                (
                order_id,
                product_id,
                price,
                qty,
                size,
                color
                )
                VALUES
                (
                '$order_id',
                '$product_id',
                '$price',
                '$qty',
                '$size',
                '$color'
                )
                ");

                /* STOCK UPDATE */

                $conn->query("
                UPDATE product_sizes
                SET stock = stock - $qty
                WHERE product_id='$product_id'
             AND TRIM(size)=TRIM('$size')
                ");
            }
        }
    }

    /* ======================
    BUY NOW ORDER
    ====================== */

    if(!empty($_SESSION['buy_now']) && $product){

        $buy = $_SESSION['buy_now'];

        $product_id =
        (int)($buy['id'] ?? 0);

        $size = $conn->real_escape_string(
        $buy['size'] ?? ''
        );

        $color = $conn->real_escape_string(
        $buy['color'] ?? ''
        );

        $qty =
        (int)($buy['qty'] ?? 1);

        $price =
        (int)$product['price'];

        if($product_id > 0){

            /* CHECK STOCK */

            $stock_q = $conn->query("
            SELECT stock
            FROM product_sizes
            WHERE product_id='$product_id'
          AND TRIM(size)=TRIM('$size')
            ");

            $stock_row =
            $stock_q->fetch_assoc();

            $current_stock =
            (int)($stock_row['stock'] ?? 0);

            if($current_stock < $qty){

                die("Out Of Stock");
            }

            /* INSERT ORDER ITEM */

            $conn->query("
            INSERT INTO order_items
            (
            order_id,
            product_id,
            price,
            qty,
            size,
            color
            )
            VALUES
            (
            '$order_id',
            '$product_id',
            '$price',
            '$qty',
            '$size',
            '$color'
            )
            ");

            /* STOCK UPDATE */

            $conn->query("
            UPDATE product_sizes
            SET stock = stock - $qty
            WHERE product_id='$product_id'
          AND TRIM(size)=TRIM('$size')
            ");
        }
    }

    /* ======================
    CLEAR SESSION
    ====================== */

    unset($_SESSION['cart']);
    unset($_SESSION['buy_now']);
    unset($_SESSION['address']);



    /* ======================
    REDIRECT
    ====================== */

    header(
    "Location: order-success.php?order_code=$order_code"
    );

    exit;
    ?>