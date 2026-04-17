<?php
require_once __DIR__ . '/init.php';
if (!isLoggedIn()) {
    flash('error', 'Please login before checkout.');
    redirect('login.php');
}

$cartController = new CartController();
$orderController = new OrderController();
$items = $cartController->getCartItems();
$total = $cartController->getTotal();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $orderController->placeOrder($_SESSION['user']['user_id'], $_POST, $items);
    if ($result['success']) {
        $cartController->clearCart();
        redirect('order_success.php?order_id=' . $result['order_id']);
    }
    $errors = $result['errors'];
}

require_once __DIR__ . '/views/layout/header.php';
?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <div class="glass p-4 shadow">
                <h3><?php echo e(translate('checkout')); ?></h3>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label"><?php echo e(translate('name')); ?></label>
                        <input type="text" name="name" class="form-control" value="<?php echo e($_SESSION['user']['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo e(translate('phone')); ?></label>
                        <input type="text" name="phone" class="form-control" value="<?php echo e($_SESSION['user']['phone']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo e(translate('address')); ?></label>
                        <textarea name="address" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-gold w-100"><?php echo e(translate('place_order')); ?></button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="glass p-4 shadow">
                <h4><?php echo e(translate('order_summary')); ?></h4>
                <?php if (empty($items)): ?>
                    <p>Your cart is empty.</p>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <strong><?php echo e($item['name']); ?></strong><br>
                                x<?php echo e($item['quantity']); ?>
                            </div>
                            <div>₹<?php echo e(number_format($item['subtotal'], 2)); ?></div>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong><?php echo e(translate('total')); ?></strong>
                        <strong>₹<?php echo e(number_format($total, 2)); ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/views/layout/footer.php';
