    <?php
    session_start();
    include "db.php";

    /* ======================
      VALIDATION FIX
    ====================== */
    $user_id = $_SESSION['user_id'];

    $checkAddress = mysqli_query($conn,"
    SELECT *
    FROM addresses
    WHERE user_id='$user_id'
    LIMIT 1
    ");

    if(mysqli_num_rows($checkAddress)==0){
        header("Location: adresses1.php");
        exit;
    }

    $addressData = mysqli_fetch_assoc($checkAddress);

    /* ======================
      TOTAL CALCULATION FIX
    ====================== */

    $grandTotal = 0;

    /* CART */
    if(!empty($_SESSION['cart'])){
      foreach($_SESSION['cart'] as $c){
        if(is_array($c) && isset($c['price'], $c['qty'])){
          $grandTotal += $c['price'] * $c['qty'];
        }
      }
    }

    /* BUY NOW */
    $product = null;
    $qty = 1;

    if(empty($_SESSION['cart']) && !empty($_SESSION['buy_now'])){
      
      $id = (int)$_SESSION['buy_now']['id'];
      $qty = (int)$_SESSION['buy_now']['qty'];

      $q = $conn->query("SELECT name, price FROM products WHERE id=$id");

      if($q && $q->num_rows > 0){

        $product = $q->fetch_assoc();

        $grandTotal += $product['price'] * $qty;
      }
    }
    $_SESSION['grand_total'] = $grandTotal;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Payment | Trendy Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body{
      background:#f8f9fa;
      font-family:'Segoe UI',Tahoma,sans-serif;
    }

    /* LAYOUT */
    @media(min-width:992px){
      .pay-layout{
        display:grid;
        grid-template-columns:1fr 360px;
        gap:30px;
      }
    }

    /* STEPS */
    .checkout-steps{
      display:flex;
      justify-content:center;
      align-items:center;
      gap:20px;
      margin-bottom:30px;
    }
    .step{font-size:14px;color:#999;font-weight:500;text-align:center}
    .step.done,.step.active{color:#198754}
    .circle{
      width:36px;height:36px;border-radius:50%;
      background:#e9ecef;
      display:flex;align-items:center;justify-content:center;
      margin:auto;font-weight:600;
    }
    .step.done .circle{background:#198754;color:#fff}
    .step.active .circle{border:2px solid #198754;background:#fff;color:#198754}
    .line{width:60px;height:3px;background:#e9ecef}
    .line.active{background:#198754}

    /* CARD */
    .checkout-card{
      background:#fff;
      padding:30px;
      border-radius:16px;
      box-shadow:0 12px 35px rgba(0,0,0,.08);
    }

    /* OPTIONS */
    .form-check{
      background:#f8f9fa;
      padding:14px;
      border-radius:12px;
      border:2px solid transparent;
      transition:.3s;
    }
    .form-check.active{
      border-color:#198754;
      background:#eafaf1;
      transform:scale(1.02);
    }

    #gpayBox{
      background:#f8fff9;
      border:1px solid #198754 !important;
    }

    #gpayBox img{
      display:block;
      margin-bottom:15px;
    }

    #gpayBox p{
      margin-bottom:8px;
    }

    /* SUMMARY */
    .order-summary{
      background:#fff;
      padding:20px;
      border-radius:16px;
      box-shadow:0 10px 30px rgba(0,0,0,.08);
    }
    .order-summary h5{font-weight:600}

    /* FORM */
    .form-control{border-radius:8px}
    .coupon-list{
        border:1px solid #ddd;
        border-radius:10px;
        padding:10px;
        background:#fff;
    }

    .coupon-item{
        display:flex;
        gap:10px;
        padding:12px;
        border-bottom:1px solid #eee;
        align-items:flex-start;
    }

    .coupon-item:last-child{
        border-bottom:none;
    }

    .coupon-item input{
        margin-top:4px;
    }
    </style>
    </head>

    <body>

    <div class="container my-5">

    <!-- STEPS -->
    <div class="checkout-steps">
      <div class="step done"><div class="circle">✓</div>Order</div>
      <div class="line active"></div>
      <div class="step done"><div class="circle">✓</div>Address</div>
      <div class="line active"></div>
      <div class="step active"><div class="circle">3</div>Payment</div>
    </div>

    <div class="pay-layout">

    <!-- PAYMENT BOX -->
    <div class="checkout-card">

    <h4 class="text-center mb-3">💳 Choose Payment Method</h4>

    <?php if(isset($_SESSION['voucher_error'])){ ?>
    <div class="alert alert-danger" id="voucherError">
        <?= $_SESSION['voucher_error']; ?>
    </div>
    <?php unset($_SESSION['voucher_error']); } ?> 

    <form action="place-order.php" method="post" id="paymentForm">

    <!-- GOOGLE PAY -->
    <div class="form-check mb-3">
      <input class="form-check-input" type="radio" name="payment_method" value="GPAY" required>
      <label class="fw-bold ms-2">Google Pay</label>

    <div id="gpayBox" style="display:none;" class="mt-3 p-3 border rounded">

        <p class="fw-bold text-success mb-3">Scan & Pay</p>

        
       <img id="gpayQR"
src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?= urlencode('upi://pay?pa=varaiyasahad@okhdfcbank&pn=TrendyStore&am='.$grandTotal.'&cu=INR') ?>"
width="220">
        <p class="text-danger mb-0">
            QR expires in: <span id="qrTimer">05:00</span>
        </p>

        

    </div>
    </div>

    <!-- CARD -->
    <div class="form-check mb-3">
      <input class="form-check-input" type="radio" name="payment_method" value="Card">
      <label class="fw-bold ms-2">💳 Credit / Debit Card</label>

      <div id="cardBox" style="display:none" class="mt-2">
        <input type="text" class="form-control mb-2" id="cardNumber" placeholder="Card Number">
        <div class="row">
          <div class="col">
            <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY">
          </div>
          <div class="col">
            <input type="password" class="form-control" id="cardCVV" placeholder="CVV">
          </div>
        </div>
      </div>
    </div>

    <!-- COD -->

    <div class="form-check mb-3">
      <input class="form-check-input" type="radio" name="payment_method" value="COD" required>
      <label class="fw-bold ms-2">🚚 Cash on Delivery</label>
    </div>


    <!-- VOUCHER -->
    <div class="form-check mb-4 active">
      
  <input class="form-check-input" type="radio" name="payment_method" value="Voucher" checked>
      <label class="fw-bold ms-2">🎟️ Voucher</label>

    <div id="voucherBox" style="display:block;" class="mt-2">
      <?php if(isset($_SESSION['voucher_error'])){ ?>
  <div style="color:red; font-weight:600; margin-bottom:10px;">
      <?= $_SESSION['voucher_error']; ?>
  </div>
  <?php unset($_SESSION['voucher_error']); } ?>

      <div class="d-flex mb-2">
        <input type="text" id="voucherInput" name="voucher_code"
              class="form-control"
              placeholder="Enter Voucher Code">
    <button type="button" class="btn btn-dark ms-2" id="applyBtn">
        APPLY
    </button>
    </div>

  <?php
  $checkFirst = mysqli_query($conn,"
  SELECT id
  FROM orders
  WHERE user_id='$user_id'
  LIMIT 1
  ");

  ?>
  <div class="coupon-list mt-3">

<?php
$checkFirst = mysqli_query($conn,"
SELECT id FROM orders
WHERE user_id='$user_id'
LIMIT 1
");
?>

<!-- WELCOME100 -->
<div class="coupon-item <?= ($grandTotal >= 499 && mysqli_num_rows($checkFirst)==0) ? 'active-coupon' : 'disabled-coupon' ?>">
    
    <input type="radio"
           name="coupon_select"
           class="coupon-radio"
           value="WELCOME100"
           data-discount="100"
           <?= ($grandTotal >= 499 && mysqli_num_rows($checkFirst)==0) ? '' : 'disabled' ?>>

    <div>
        <b>WELCOME100</b><br>
        <small>₹100 OFF on First Order</small>
    </div>
</div>

<!-- SAVE100 -->
<div class="coupon-item <?= ($grandTotal >= 899) ? 'active-coupon' : 'disabled-coupon' ?>">

    <input type="radio"
           name="coupon_select"
           class="coupon-radio"
           value="SAVE100"
           data-discount="100"
           <?= ($grandTotal >= 899) ? '' : 'disabled' ?>>

    <div>
        <b>SAVE100</b><br>
        <small>Flat ₹100 OFF on cart value ₹899+</small>
    </div>
</div>

<!-- SAVE200 -->
<div class="coupon-item <?= ($grandTotal >= 1099) ? 'active-coupon' : 'disabled-coupon' ?>">

    <input type="radio"
           name="coupon_select"
           class="coupon-radio"
           value="SAVE200"
           data-discount="200"
           <?= ($grandTotal >= 1099) ? '' : 'disabled' ?>>

    <div>
        <b>SAVE200</b><br>
        <small>Flat ₹200 OFF on cart value ₹1099+</small>
    </div>
</div>

<!-- SAVE300 -->
<div class="coupon-item <?= ($grandTotal >= 1599) ? 'active-coupon' : 'disabled-coupon' ?>">

    <input type="radio"
           name="coupon_select"
           class="coupon-radio"
           value="SAVE300"
           data-discount="300"
           <?= ($grandTotal >= 1599) ? '' : 'disabled' ?>>

    <div>
        <b>SAVE300</b><br>
        <small>Flat ₹300 OFF on cart value ₹1599+</small>
    </div>
</div>
</div>
</div>
</div>

    <!-- HIDDEN -->
    <input type="hidden" name="name" value="<?= $addressData['fullname'] ?>">
    <input type="hidden" name="mobile" value="<?= $addressData['mobile'] ?>">
    <input type="hidden" name="address" value="<?= $addressData['address'] ?>">
    <input type="hidden" name="total" value="<?= $grandTotal ?>">
    <input type="hidden" name="discount" value="0">
  <input type="hidden" name="final_total" id="finalTotalInput">

    <div class="d-flex justify-content-between">
      <a href="view-cart.php" class="btn btn-secondary">⬅ Back</a>
      <button type="submit" class="btn btn-success px-4">Place Order</button>
    </div>

    </form>
    </div>

    <!-- ORDER SUMMARY -->
    <div class="order-summary">
    <h5>🧾 Order Summary</h5>
    <hr>

    <?php if(!empty($_SESSION['cart'])){ ?>

      <?php foreach($_SESSION['cart'] as $item){ 

        $name = $item['name'] ?? 'Product';

        $qty = isset($item['qty'])
              ? (int)$item['qty']
              : 1;

        $price = isset($item['price'])
                ? (float)$item['price']
                : 0;

        $subtotal = $price * $qty;

      ?>

        <div class="d-flex justify-content-between mb-2">

          <span>
            <?= htmlspecialchars($name) ?> × <?= $qty ?>
          </span>

          <span>
            ₹<?= $subtotal ?>
          </span>

        </div>

      <?php } ?>

    <?php } ?>

    <?php if(empty($_SESSION['cart']) && !empty($_SESSION['buy_now'])){ ?>
      <div class="d-flex justify-content-between mb-2">
        <span><?= $product['name'] ?> × <?= $qty ?></span>
        <span>₹<?= $product['price'] * $qty ?></span>
      </div>
    <?php } ?>

    <hr>
    <p id="originalTotal">
    Total: ₹<?= $grandTotal ?>
    </p>

    <p id="discountRow" style="display:none; color:green;">
    Discount: -₹<span id="discountText">0</span>
    </p>

    <h5 id="finalTotal">
    Final Total: ₹<?= $grandTotal ?>
    </h5>
    </div>

    </div>
    </div>

    <script>
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const boxes = document.querySelectorAll('.form-check');

    const cardBox = document.getElementById('cardBox');
    const voucherBox = document.getElementById('voucherBox');
    const gpayBox = document.getElementById('gpayBox');

    radios.forEach(r => {
        r.addEventListener('change', () => {

            boxes.forEach(b => b.classList.remove('active'));

            r.closest('.form-check').classList.add('active');

            /* sab hide */
            cardBox.style.display = "none";
            voucherBox.style.display = "none";
            gpayBox.style.display = "none";

            /* selected open */
            if(r.value === "GPAY"){
                gpayBox.style.display = "block";
                time = 300;
                startQRTimer();
            }

            if(r.value === "Card"){
                cardBox.style.display = "block";
            }

            if(r.value === "Voucher"){
                voucherBox.style.display = "block";
            }
        });
    });

    /* QR TIMER */
    let time = 300;
    let countdown;

    function startQRTimer() {
        const timer = document.getElementById("qrTimer");

        clearInterval(countdown);

        countdown = setInterval(() => {
            let minutes = Math.floor(time / 60);
            let seconds = time % 60;

            if(seconds < 10){
                seconds = "0" + seconds;
            }

            timer.innerHTML = minutes + ":" + seconds;
            time--;

            if(time < 0){
                clearInterval(countdown);
                timer.innerHTML = "Expired";
                gpayBox.style.display = "none";
                alert("QR Expired! Refresh for new QR.");
            }
        }, 1000);
    }
    const couponRadios = document.querySelectorAll('.coupon-item input[type="radio"]');
const voucherInput = document.getElementById('voucherInput');
const applyBtn = document.getElementById('applyBtn');

/* radio select */
couponRadios.forEach(coupon => {
    coupon.addEventListener('change', function () {
        voucherInput.value = this.value;
    });
});

/* apply coupon */
applyBtn.addEventListener('click', function () {

    let code = voucherInput.value.trim();
    let discount = 0;

    if(code === "WELCOME100"){
        discount = 100;
    }
    else if(code === "SAVE100"){
        discount = 100;
    }
    else if(code === "SAVE200"){
        discount = 200;
    }
    else if(code === "SAVE300"){
        discount = 300;
    }

    let total = <?= $grandTotal ?>;
    let final = total - discount;

    if(final < 0){
        final = 0;
    }

    /* Discount row show */
    document.getElementById("discountText").innerText = discount;
    document.getElementById("discountRow").style.display = "block";

    /* Final total update */
    document.getElementById("finalTotal").innerHTML =
        "Final Total: ₹" + final;

    /* Hidden inputs */
    document.querySelector('input[name="voucher_code"]').value = code;
    document.querySelector('input[name="discount"]').value = discount;
    document.querySelector('input[name="final_total"]').value = final;
    document.getElementById("finalTotalInput").value = final;
    /* Update GPay QR after discount */
let upi =
"upi://pay?pa=varaiyasahad@okhdfcbank&pn=TrendyStore&am=" + final + "&cu=INR";

document.getElementById("gpayQR").src =
"https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" +
encodeURIComponent(upi);
});
    couponRadios.forEach(coupon => {
    coupon.addEventListener('change', function () {
        voucherInput.value = this.value;
        document.getElementById("selectedCoupon").value = this.value;
    });
});
      </script>

    </body>
    </html>