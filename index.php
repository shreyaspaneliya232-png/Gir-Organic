<?php
require_once __DIR__ . '/init.php';
$productController = new ProductController();
$products = $productController->listProducts();

require_once __DIR__ . '/views/layout/header.php';
?>
<section class="hero d-flex align-items-center" id="home">
<div class="container hero-content">
    <h1 class="display-3 fw-bold"><?php echo e(translate('our_roots')); ?></h1>
    <p class="lead mt-3"><?php echo e(translate('premium_text')); ?></p>
    <a href="#products" class="btn btn-gold mt-3 me-3 px-4 py-2"><?php echo e(translate('shop')); ?></a>
    <a href="#about" class="btn btn-light mt-3 px-4 py-2"><?php echo e(translate('story')); ?></a>
</div>
</section>

<section class="py-5 bg-white" id="about">
<div class="container">
<div class="row align-items-center">
    <div class="col-md-6">
        <img src="images/story.jpeg" class="img-fluid rounded-4 shadow" alt="About Gir Organic">
    </div>
    <div class="col-md-6">
        <h2 class="fw-bold"><?php echo e(translate('our_roots')); ?></h2>
        <p><?php echo e(translate('mission_text')); ?></p>
        <div class="p-3 bg-light rounded-3 fst-italic">
            &quot;<?php echo e(translate('mission_text')); ?>&quot;
        </div>
    </div>
</div>
</div>
</section>

<section class="py-5 bg-light" id="products">
<div class="container">
<h2 class="text-center fw-bold mb-5"><?php echo e(translate('our_collection')); ?></h2>
<div class="row g-4">
    <?php foreach ($products as $product):
        $text = translate('order_message') . "\nProduct: {$product['name']}\nQuantity: 1\nTotal: ₹{$product['price']}";
        $whatsappUrl = 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . urlencode($text);
    ?>
    <div class="col-md-4">
        <div class="product-card bg-white p-3 shadow">
            <div class="product-img mb-3">
                <img src="<?php echo e(IMAGE_UPLOAD_PATH . $product['image']); ?>" class="img-fluid" alt="<?php echo e($product['name']); ?>">
            </div>
            <h5><?php echo e($product['name']); ?></h5>
            <p><?php echo e($product['description']); ?></p>
            <p class="fw-semibold">₹<?php echo e(number_format($product['price'], 2)); ?></p>
            <div class="d-grid gap-2">
                <a href="cart.php?action=add&product_id=<?php echo e($product['product_id']); ?>" class="btn btn-forest"><?php echo e(translate('add_to_cart')); ?></a>
                <a href="<?php echo e($whatsappUrl); ?>" target="_blank" class="btn btn-gold"><?php echo e(translate('order_on_whatsapp')); ?></a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
</div>
</section>

<?php require_once __DIR__ . '/views/layout/footer.php';
