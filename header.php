<?php

$cartCount = 0;

if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $cartCount += $item['qty'] ?? 1;
    }
}
?>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

.navbar{
    width:100%;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:15px 40px;
    box-shadow:0 2px 10px rgba(0,0,0,.08);

    position:fixed;
    top:0;
    left:0;
    z-index:999999;
}

.nav-left{
    display:flex;
    align-items:center;
    gap:12px;
}

.nav-logo{
    width:55px;
    height:55px;
    object-fit:contain;
}

.store-name{
    font-size:26px;
    font-weight:800;
    color:#111;
}

.nav-menu{
    list-style:none;
    display:flex;
    align-items:center;
    gap:35px;
}

.nav-menu a{
    text-decoration:none;
    color:#000;
    font-size:15px;
    font-weight:700;
}

.nav-menu a:hover{
    color:#0a7d5f;
}

.search-box{
    width:280px;
    display:flex;
    align-items:center;
    border:1px solid #ddd;
    border-radius:30px;
    overflow:hidden;
    background:#fff;
}

.search-box input{
    flex:1;
    border:none;
    outline:none;
    padding:12px 15px;
    font-size:14px;
}

.search-box button{
    border:none;
    background:none;
    padding:0 15px;
    cursor:pointer;
    font-size:18px;
}

.nav-icons{
    display:flex;
    align-items:center;
    gap:20px;
}

.nav-icons a{
    text-decoration:none;
    color:#000;
    font-size:15px;
    font-weight:700;
}

.nav-icons a:hover{
    color:#0a7d5f;
}

/* Mobile */

@media(max-width:768px){

.navbar{
    flex-wrap:wrap;
    padding:12px;
    gap:10px;
}

.nav-left{
    width:100%;
    justify-content:center;
}

.store-name{
    font-size:22px;
}

.nav-menu{
    width:100%;
    justify-content:center;
    gap:15px;
}

.search-box{
    width:100%;
}

.nav-icons{
    width:100%;
    justify-content:center;
}

.nav-menu a,
.nav-icons a{
    font-size:14px;
}

}
.logo-link{
    text-decoration:none;
    color:inherit;
}
</style>

<div class="navbar">

    <a href="index.php" class="logo-link">
    <div class="nav-left">
        <img src="uploads/logo.png" class="nav-logo" alt="Trendy Store">
        <div class="store-name">Trendy Store</div>
    </div>
</a>
    <ul class="nav-menu">
        <li><a href="men.php">👔 MEN</a></li>
        <li><a href="women.php">👗 WOMEN</a></li>
        <li><a href="trending.php">🔥 TRENDING</a></li>
    </ul>

    <form action="search.php" method="GET" class="search-box">
        <input type="text" name="q" placeholder="Search products...">
        <button type="submit">🔍</button>
    </form>

    <div class="nav-icons">

        <?php if(isset($_SESSION['user_id'])){ ?>
            <a href="account.php">
                Hi, <?= htmlspecialchars($_SESSION['user_name']) ?>
            </a>
        <?php } else { ?>
            <a href="auth.php">Login</a>
        <?php } ?>

        <a href="view-cart.php">
            🛒 Cart (<?= $cartCount ?>)
        </a>

    </div>

</div>