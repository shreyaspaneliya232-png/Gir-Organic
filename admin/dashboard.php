<?php
require_once __DIR__ . '/init.php';
$userModel = new User();
$productModel = new Product();
$orderModel = new Order();

$totalUsers = $userModel->countUsers();
$totalProducts = $productModel->countProducts();
$totalOrders = $orderModel->countOrders();

require_once __DIR__ . '/header.php';
?>
<h1 class="mb-4">Admin Dashboard</h1>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card p-4 shadow-sm">
            <h5><?php echo e(translate('total_users')); ?></h5>
            <p class="display-6"><?php echo e($totalUsers); ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 shadow-sm">
            <h5><?php echo e(translate('total_products')); ?></h5>
            <p class="display-6"><?php echo e($totalProducts); ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 shadow-sm">
            <h5><?php echo e(translate('total_orders')); ?></h5>
            <p class="display-6"><?php echo e($totalOrders); ?></p>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php';
