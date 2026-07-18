<style>
    .footer{
  background:#24384d;
  color:#fff;
  padding:30px 25px 90px;
  margin-top:30px;
  width:100%;
}

.footer-top{
  display:flex;
  justify-content:space-around;
  align-items:center;
  text-align:center;
  padding-bottom:25px;
  border-bottom:1px solid rgba(255,255,255,.15);
  
}

.footer-top .icon-box{
  width:200px;
}

.footer-top .icon{
  font-size:40px;
  margin-bottom:8px;
}

.footer-top h4{
  font-size:14px;
  margin:0;
}

.footer-links{
  display:flex;
  justify-content:space-between;
  flex-wrap:wrap;
  gap:20px;
  margin-top:25px;
}

.footer-links h3{
  font-size:18px;
  margin-bottom:12px;
}

.footer-links a{
  display:block;
  color:#ddd;
  text-decoration:none;
  margin-bottom:8px;
}

.footer-bottom{
  margin-top:25px;
  padding-top:20px;
  border-top:1px solid rgba(255,255,255,.2);
  display:flex;
  justify-content:space-between;
  align-items:center;
  flex-wrap:wrap;
}

.payment-icons{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}



.payment-icons img{
    width:125px ;
    height:85px;
    object-fit:contain;
    background:#fff;
    padding:8px;
    border-radius:8px;
}

.security-icons img{
    width:125px;
    height:85px;
    object-fit:contain;
    background:#fff;
    padding:8px;
    border-radius:8px;
}
.footer{
    margin-bottom:0 ;
}


</style>

<footer class="footer">

  <div class="footer-top">

    <div class="icon-box">
      <div class="icon">✔</div>
      <h4>ASSURED QUALITY</h4>
    </div>

    <div class="icon-box">
      <div class="icon">↩</div>
      <h4>EASY RETURNS</h4>
    </div>

    <div class="icon-box">
      <div class="icon">🚚</div>
      <h4>FREE SHIPPING</h4>
    </div>

  </div>

  <div class="footer-links">

    <div>
      <h3>Trendy Store</h3>
      <a href="about.php">About Us</a>
      <a href="terms.php">Terms & Conditions</a>
      <a href="privacy-policy.php">Privacy Policy</a>
      <a href="refund-policy.php">Returns Policy</a>
    </div>

    <div>
      <h3>Help</h3>
      <a href="track.php">Track Order</a>
      <a href="faq.php">FAQs</a>
      <a href="refund-policy.php">Returns</a>
      <a href="fees-payments.php">Payments</a>
    </div>

    <div>
      <h3>Shop</h3>
      <a href="men.php">Men</a>
      <a href="women.php">Women</a>
      <a href="trending.php">Trending</a>
    </div>

  </div>

  <div class="footer-bottom">

    <div>
      <h4>Payment Methods</h4>

      <div class="payment-icons">
        <img src="uploads/visa.png">
        <img src="uploads/mastercard.png">
        <img src="uploads/cod.png">
        <img src="uploads/paytm.png">
      </div>
    </div>

    <div>
      <h4>Secure Payments</h4>

      <div class="security-icons">
        <img src="uploads/ssl.png">
      </div>
    </div>

  </div>

</footer>