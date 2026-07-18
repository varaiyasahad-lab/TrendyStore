    <?php
    session_start();
    include "db.php";

    if(!isset($_SESSION['user_id'])){
        header("Location: auth.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];

    /* EDIT FETCH */

    $editData = null;

    if(isset($_GET['edit'])){

        $edit_id = $_GET['edit'];

        $editQuery = mysqli_query($conn,"
        SELECT * FROM addresses
        WHERE id='$edit_id'
        AND user_id='$user_id'
        ");

        $editData = mysqli_fetch_assoc($editQuery);
    }


    /* UPDATE ADDRESS */

    if(isset($_POST['update'])){

        $id       = $_POST['id'];
        $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
        $mobile   = mysqli_real_escape_string($conn,$_POST['mobile']);
        $address  = mysqli_real_escape_string($conn,$_POST['address']);
        $city     = mysqli_real_escape_string($conn,$_POST['city']);
        $state    = mysqli_real_escape_string($conn,$_POST['state']);
        $pincode  = mysqli_real_escape_string($conn,$_POST['pincode']);

        mysqli_query($conn,"
        UPDATE addresses SET
        fullname='$fullname',
        mobile='$mobile',
        address='$address',
        city='$city',
        state='$state',
        pincode='$pincode'
        WHERE id='$id'
        AND user_id='$user_id'
        ");

        header("Location: address.php");
        exit;
    }


    /* SAVE ADDRESS */

    if(isset($_POST['save'])){

        $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
        $mobile   = mysqli_real_escape_string($conn,$_POST['mobile']);
        $address  = mysqli_real_escape_string($conn,$_POST['address']);
        $city     = mysqli_real_escape_string($conn,$_POST['city']);
        $state    = mysqli_real_escape_string($conn,$_POST['state']);
        $pincode  = mysqli_real_escape_string($conn,$_POST['pincode']);

        mysqli_query($conn,"
        INSERT INTO addresses
        (user_id, fullname, mobile, address, city, state, pincode)
        VALUES
        ('$user_id','$fullname','$mobile','$address','$city','$state','$pincode')
        ");

        header("Location: address.php");
        exit;
    }


    /* DELETE ADDRESS */

    if(isset($_GET['delete'])){

        $id = $_GET['delete'];

        mysqli_query($conn,"
        DELETE FROM addresses
        WHERE id='$id'
        AND user_id='$user_id'
        ");

        header("Location: address.php");
        exit;
    }

    ?>

    <!DOCTYPE html>
    <html>
    <head>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>My Address</title>

    <style>

    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:Arial;
        padding-top: 95px;
    }

    body{
        background:#f5f5f5;
        margin-top: 95px;
    }

    /* HEADER */

    .header{
        background:#fff;
        padding:18px;
        border-bottom:1px solid #eee;

        display:flex;
        justify-content:space-between;
        align-items:center;
    }

    .header h2{
        font-size:22px;
    }

    /* ADD BUTTON */

    .add-btn{
        background:#000;
        color:#fff;
        padding:10px 14px;
        border-radius:10px;
        text-decoration:none;
        font-size:14px;
    }

    /* CONTAINER */

    .container{
        width:95%;
        max-width:700px;
        margin:20px auto;
    }

    /* FORM BOX */

    .form-box{
        background:#fff;
        padding:22px;
        border-radius:16px;
        margin-bottom:20px;
        box-shadow:0 2px 8px rgba(0,0,0,0.05);

        display:none;
    }

    .form-box h3{
        margin-bottom:15px;
    }

    input,
    textarea{
        width:100%;
        padding:14px;
        border:1px solid #ddd;
        border-radius:10px;
        margin-bottom:14px;
        font-size:15px;
    }

    textarea{
        height:100px;
        resize:none;
    }

    button{
        width:100%;
        padding:14px;
        background:#000;
        color:#fff;
        border:none;
        border-radius:10px;
        font-size:16px;
        cursor:pointer;
    }

    /* ADDRESS CARD */

    .card{
        background:#fff;
        padding:20px;
        border-radius:16px;
        margin-bottom:15px;
        box-shadow:0 2px 8px rgba(0,0,0,0.05);
        position:relative;
    }

    .card h4{
        margin-bottom:8px;
    }

    .card p{
        color:#666;
        line-height:1.7;
        font-size:14px;
    }

    /* DELETE BUTTON */

    .delete-btn{
        position:absolute;
        top:15px;
        right:15px;

        background:red;
        color:#fff;
        padding:6px 10px;
        border-radius:8px;
        text-decoration:none;
        font-size:12px;
    }

    /* EDIT BUTTON */

    .edit-btn{
        position:absolute;
        top:50px;
        right:15px;

        background:orange;
        color:#fff;
        padding:6px 12px;
        border-radius:8px;
        text-decoration:none;
        font-size:12px;
    }

    </style>

    </head>

    <body>
  <?php include 'header.php'; ?>
    <!-- HEADER -->

    <div class="header">

    <h2>My Address</h2>

    <a href="#" class="add-btn" onclick="showForm()">
        + Add Address
    </a>

    </div>

    <div class="container">

    <!-- FORM -->

    <div class="form-box" id="addressForm"
    style="<?php if($editData){ echo 'display:block;'; } ?>">

    <h3>
    <?php if($editData){ ?>
        Edit Address
    <?php } else { ?>
        Add New Address
    <?php } ?>
    </h3>

    <form method="POST">

    <input type="hidden" name="id"
    value="<?php echo $editData['id'] ?? ''; ?>">

    <input type="text" name="fullname"
    placeholder="Full Name"
    value="<?php echo $editData['fullname'] ?? ''; ?>"
    required>

    <input type="text" name="mobile"
    placeholder="Mobile Number"
    value="<?php echo $editData['mobile'] ?? ''; ?>"
    required>

    <textarea name="address"
    placeholder="Full Address"
    required><?php echo $editData['address'] ?? ''; ?></textarea>

    <input type="text" name="city"
    placeholder="City"
    value="<?php echo $editData['city'] ?? ''; ?>"
    required>

    <input type="text" name="state"
    placeholder="State"
    value="<?php echo $editData['state'] ?? ''; ?>"
    required>

    <input type="text" name="pincode"
    placeholder="Pincode"
    value="<?php echo $editData['pincode'] ?? ''; ?>"
    required>

    <?php if($editData){ ?>

    <button type="submit" name="update">
        Update Address
    </button>

    <?php } else { ?>

    <button type="submit" name="save">
        Save Address
    </button>

    <?php } ?>

    </form>

    </div>

    <!-- SHOW ADDRESS -->

    <?php

    $get = mysqli_query($conn,"
    SELECT * FROM addresses
    WHERE user_id='$user_id'
    ORDER BY id DESC
    ");

    while($row = mysqli_fetch_assoc($get)){
    ?>

    <div class="card">

    <a class="delete-btn"
    href="?delete=<?php echo $row['id']; ?>">
    Delete
    </a>

    <a class="edit-btn"
    href="?edit=<?php echo $row['id']; ?>">
    Edit
    </a>

    <h4>
    <?php echo $row['fullname']; ?>
    </h4>

    <p>
    📞 <?php echo $row['mobile']; ?>
    </p>

    <p>
    🏠 <?php echo $row['address']; ?>
    </p>

    <p>
    <?php echo $row['city']; ?>,
    <?php echo $row['state']; ?> -
    <?php echo $row['pincode']; ?>
    </p>

    </div>

    <?php } ?>

    </div>

    <script>

    function showForm(){

        var form = document.getElementById("addressForm");

        if(form.style.display=="block"){
            form.style.display="none";
        }else{
            form.style.display="block";
        }

    }

    </script>
  <?php include 'footer.php'; ?>
    </body>
    </html>