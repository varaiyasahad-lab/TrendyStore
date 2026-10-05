<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<style>
.mobile-nav{
  position:fixed;
  bottom:0;
  left:0;
  width:100%;
  height:65px;
  background:#fff;
  display:flex;
  justify-content:space-around;
  align-items:center;
  box-shadow:0 -2px 10px rgba(0,0,0,0.1);
  z-index:999;
}

.mobile-nav a{
  text-decoration:none;
  color:#555;
  text-align:center;
  font-size:12px;
  flex:1;
  transition:0.3s;
}

.mobile-nav span{
  display:block;
  font-size:22px;
}

.mobile-nav a.active{
  color:#e10600;
  font-weight:bold;
}

.mobile-nav a:hover{
  color:#e10600;
}


@media(min-width:769px){
  .mobile-nav{
    display:none;
  }
}


body{
  padding-bottom:70px;
}
</style>

<div class="mobile-nav">
  
  <a href="index.php" class="<?php if($current=='index.php') echo 'active'; ?>">
    <span>🏠</span>
    <p>Home</p>
  </a>

  <a href="wishlist.php" class="<?php if($current=='wishlist.php') echo 'active'; ?>">
    <span>❤</span>
    <p>Wishlist</p>
  </a>

  <a href="categories.php" class="<?php if($current=='categories.php') echo 'active'; ?>">
    <span>☰</span>
    <p>Categories</p>
  </a>

  <a href="orders.php" class="<?php if($current=='orders.php') echo 'active'; ?>">
    <span>📦</span>
    <p>Orders</p>
  </a>

</div>
