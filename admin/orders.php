<?php
require_once __DIR__ . '/init.php';
$orderController = new OrderController();
$orders = $orderController->getAllOrders();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'], $_POST['order_id'])) {
    $orderController->updateStatus((int)$_POST['order_id'], clean($_POST['status']));
    redirect('orders.php');
}

require_once __DIR__ . '/header.php';
?>
<h1 class="mb-4">Orders</h1>
<div class="card p-4 shadow-sm">
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo e($order['order_id']); ?></td>
                        <td><?php echo e($order['customer_name'] ?? 'Guest'); ?></td>
                        <td>₹<?php echo e(number_format($order['total_amount'], 2)); ?></td>
                        <td><?php echo e($order['status']); ?></td>
                        <td><?php echo e($order['created_at']); ?></td>
                        <td>
                            <form method="post" class="d-flex gap-2">
                                <input type="hidden" name="order_id" value="<?php echo e($order['order_id']); ?>">
                                <select name="status" class="form-select form-select-sm">
                                    <?php foreach (['Pending', 'Confirmed', 'Shipped', 'Delivered', 'Cancelled'] as $status): ?>
                                        <option value="<?php echo e($status); ?>" <?php echo $order['status'] === $status ? 'selected' : ''; ?>><?php echo e($status); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php';
