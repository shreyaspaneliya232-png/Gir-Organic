<?php
require_once __DIR__ . '/init.php';
$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$orderController = new OrderController();
$order = $orderController->getOrderDetails($orderId);
if (!$order) {
    redirect('index.php');
}

$text = translate('order_message') . "\n";
foreach ($order['items'] as $item) {
    $text .= translate('product') . ': ' . $item['product_name'] . ' x' . $item['quantity'] . '\n';
}
$text .= translate('total') . ': ₹' . number_format($order['total_amount'], 2);
$whatsappUrl = 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . urlencode($text);

require_once __DIR__ . '/views/layout/header.php';
?>
<div class="container py-5">
    <div class="glass p-5 shadow">
        <h2><?php echo e(translate('order_added')); ?></h2>
        <p>Order ID: <strong>#<?php echo e($order['order_id']); ?></strong></p>
        <p><?php echo e(translate('total')); ?>: ₹<?php echo e(number_format($order['total_amount'], 2)); ?></p>
        <p><?php echo e(translate('status')); ?>: <?php echo e($order['status']); ?></p>
        <a href="<?php echo e($whatsappUrl); ?>" class="btn btn-gold mt-3" target="_blank"><?php echo e(translate('order_on_whatsapp')); ?></a>
    </div>
</div>
<?php require_once __DIR__ . '/views/layout/footer.php';
