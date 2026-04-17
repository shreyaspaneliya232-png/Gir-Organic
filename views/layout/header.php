<?php
$currentUser = getCurrentUser();
$cartCount = array_sum($_SESSION['cart'] ?? []);
$lang = $_SESSION['lang'] ?? 'en';
$languageLabel = $lang === 'gu' ? translate('gujarati') : translate('english');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo e(SITE_TITLE); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<style>
:root { --forest: #14532d; --gold: #d97706; --chocolate: #3d241c; }
body { font-family: 'Outfit', sans-serif; background: #fefce8; }
h1, h2, h3 { font-family: 'Playfair Display', serif; }
.glass { background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); border-radius: 20px; }
.navbar-custom { position: fixed; width: 100%; z-index: 999; }
.hero { min-height: 100vh; background: url('images/story.jpeg') center/cover no-repeat; position: relative; color: white; }
.hero::before { content: ""; position: absolute; inset: 0; background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.3)); }
.hero-content { position: relative; z-index: 2; }
.btn-forest { background: var(--forest); color: white; border-radius: 12px; }
.btn-gold { background: var(--gold); color: white; border-radius: 12px; }
.product-card { border-radius: 30px; transition: 0.3s; }
.product-card:hover { transform: translateY(-8px); }
.product-img { border-radius: 25px; overflow: hidden; }
.product-img img { transition: 0.5s; }
.product-card:hover img { transform: scale(1.1); }
.footer { background: #1c1917; color: white; }
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom py-3">
<div class="container">
    <div class="glass p-2 d-flex w-100 justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="images/GIR.png" height="45" alt="<?php echo e(SITE_TITLE); ?>">
            <strong class="ms-2"><?php echo e(SITE_TITLE); ?></strong>
        </div>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">☰</button>
        <div id="menu" class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav gap-4 align-items-center">
                <li><a class="nav-link" href="index.php#home"><?php echo e(translate('home')); ?></a></li>
                <li><a class="nav-link" href="index.php#about"><?php echo e(translate('about')); ?></a></li>
                <li><a class="nav-link" href="index.php#products"><?php echo e(translate('products')); ?></a></li>
                <li><a class="nav-link" href="cart.php"><?php echo e(translate('cart')); ?> (<?php echo e($cartCount); ?>)</a></li>
                <?php if ($currentUser): ?>
                    <li><a class="nav-link" href="logout.php"><?php echo e(translate('logout')); ?></a></li>
                <?php else: ?>
                    <li><a class="nav-link" href="login.php"><?php echo e(translate('login')); ?></a></li>
                    <li><a class="nav-link" href="register.php"><?php echo e(translate('register')); ?></a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <a href="?lang=en" class="btn btn-sm btn-light"><?php echo e(translate('english')); ?></a>
            <a href="?lang=gu" class="btn btn-sm btn-light"><?php echo e(translate('gujarati')); ?></a>
        </div>
    </div>
</div>
</nav>
<div class="pt-5"></div>
