<?php
require_once __DIR__ . '/init.php';
$cartController = new CartController();
$action = $_GET['action'] ?? null;
$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if ($action === 'add' && $productId > 0) {
    $cartController->addToCart($productId, 1);
    redirect('cart.php');
}

if ($action === 'remove' && $productId > 0) {
    $cartController->removeFromCart($productId);
    redirect('cart.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $id => $quantity) {
        $cartController->updateCart((int)$id, (int)$quantity);
    }
    redirect('cart.php');
}

$items = $cartController->getCartItems();
$total = $cartController->getTotal();
require_once __DIR__ . '/views/layout/header.php';
?>
<div class="container py-5">
    <h2 class="mb-4"><?php echo e(translate('cart')); ?></h2>
    <?php if (empty($items)): ?>
        <div class="alert alert-info">Your cart is empty.</div>
        <a href="index.php" class="btn btn-forest"><?php echo e(translate('shop')); ?></a>
    <?php else: ?>
        <form method="post">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                    <tr>
                        <th><?php echo e(translate('product')); ?></th>
                        <th><?php echo e(translate('price')); ?></th>
                        <th><?php echo e(translate('quantity')); ?></th>
                        <th><?php echo e(translate('total')); ?></th>
                        <th><?php echo e(translate('actions')); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?php echo e(IMAGE_UPLOAD_PATH . $item['image']); ?>" width="80" alt="<?php echo e($item['name']); ?>">
                                    <div>
                                        <strong><?php echo e($item['name']); ?></strong>
                                    </div>
                                </div>
                            </td>
                            <td>₹<?php echo e(number_format($item['price'], 2)); ?></td>
                            <td><input type="number" name="quantities[<?php echo e($item['product_id']); ?>]" value="<?php echo e($item['quantity']); ?>" min="1" class="form-control w-25"></td>
                            <td>₹<?php echo e(number_format($item['subtotal'], 2)); ?></td>
                            <td>
                                <a href="cart.php?action=remove&product_id=<?php echo e($item['product_id']); ?>" class="btn btn-sm btn-danger"><?php echo e(translate('remove')); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <button type="submit" class="btn btn-forest"><?php echo e(translate('update')); ?></button>
                <div class="text-end">
                    <h4><?php echo e(translate('total')); ?>: ₹<?php echo e(number_format($total, 2)); ?></h4>
                    <a href="checkout.php" class="btn btn-gold"><?php echo e(translate('checkout')); ?></a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/views/layout/footer.php';
