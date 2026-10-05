<?php
include "db.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Care</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#f5f5f5;
    margin-top: 120px;
}


.top-bar{
    background:#111;
    color:#fff;
    text-align:center;
    padding:12px;
    font-size:18px;
    font-weight:bold;
}


.care-container{
    width:95%;
    max-width:1100px;
    margin:25px auto;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:20px;
}


.care-card{
    background:#fff;
    border-radius:14px;
    padding:25px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
    transition:0.3s;
}

.care-card:hover{
    transform:translateY(-5px);
}

.care-card h2{
    color:#111;
    margin-bottom:15px;
    font-size:24px;
}

.care-card p{
    color:#555;
    line-height:1.7;
    font-size:15px;
}


.contact-btn{
    display:inline-block;
    margin-top:15px;
    padding:12px 20px;
    background:#111;
    color:#fff;
    text-decoration:none;
    border-radius:8px;
    transition:0.3s;
    font-size:15px;
}

.contact-btn:hover{
    background:#ff6600;
}


form{
    display:flex;
    flex-direction:column;
    gap:15px;
}

input,
textarea{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:8px;
    outline:none;
    font-size:15px;
}

textarea{
    resize:none;
    height:120px;
}

button{
    padding:14px;
    border:none;
    background:#111;
    color:#fff;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
    transition:0.3s;
}

button:hover{
    background:#ff6600;
}


@media(max-width:768px){

    .top-bar{
        font-size:16px;
    }

    .care-card{
        padding:20px;
    }

    .care-card h2{
        font-size:22px;
    }

}
.back-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    margin:15px 14px 5px;
    padding:10px 16px;
    background:#111;
    color:#fff;
    border-radius:8px;
    font-size:14px;
    font-weight:bold;
    text-decoration:none;
    transition:0.3s;
}

.back-btn:hover{
    background:#333;
}

.back-arrow{
    font-size:20px;
    line-height:1;
}
</style>
</head>
<body>
    <?php include "header.php"; ?>
<a href="account.php" class="back-btn">
    <span class="back-arrow">←</span>
    Back to Account
</a>
<div class="top-bar">
    Contact Us
</div>

<div class="care-container">


    <div class="care-card">
        <h2>Contact Us</h2>

        <p>
            Need help with your order, payment, delivery or returns?
            Our support team is available 24/7.
        </p>

        <a href="tel:+918140948101" class="contact-btn">
            Call Now
        </a>
        
        <a href="https://wa.me/918140948101" class="contact-btn" target="_blank">
    WhatsApp Chat
</a>

        <a href="mailto:support@trendystore.com" class="contact-btn">
            Email Us
        </a>
    </div>

 
    <div class="care-card">
        <h2>FAQs</h2>

        <p>
            • Order tracking problems<br><br>
            • Return & refund support<br><br>
            • Payment issues<br><br>
            • Product exchange help<br><br>
            • Account login problems
        </p>
    </div>

  
    <div class="care-card">
        <h2>Send Message</h2>

        <form action="" method="POST">

            <input type="text" name="name" placeholder="Your Name" required>

            <input type="email" name="email" placeholder="Your Email" required>

            <textarea name="message" placeholder="Write your issue..." required></textarea>

            <button type="submit" name="send">
                Submit
            </button>

        </form>

        <?php
        if(isset($_POST['send'])){

            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];

            $sql = "INSERT INTO customer_support(name,email,message)
                    VALUES('$name','$email','$message')";

            if(mysqli_query($conn,$sql)){
                echo "<p style='color:green;margin-top:15px;'>
                        Message Sent Successfully
                      </p>";
            }else{
                echo "<p style='color:red;margin-top:15px;'>
                        Failed to Send Message
                      </p>";
            }
        }
        ?>

    </div>

</div>
<?php include "footer.php"; ?>
</body>
</html>