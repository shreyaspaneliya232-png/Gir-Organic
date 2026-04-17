<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../config/functions.php';

class OrderController
{
    private Order $orderModel;
    private Product $productModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->productModel = new Product();
    }

    public function placeOrder(int $userId, array $shipping, array $cartItems): array
    {
        $name = clean($shipping['name'] ?? '');
        $phone = clean($shipping['phone'] ?? '');
        $address = clean($shipping['address'] ?? '');
        $errors = [];

        if ($name === '') {
            $errors[] = translate('name') . ' is required.';
        }
        if ($phone === '') {
            $errors[] = translate('phone') . ' is required.';
        }
        if ($address === '') {
            $errors[] = translate('address') . ' is required.';
        }
        if (empty($cartItems)) {
            $errors[] = 'Cart is empty.';
        }

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $totalAmount = 0.0;
        $orderItems = [];

        foreach ($cartItems as $item) {
            $product = $this->productModel->getById($item['product_id']);
            if (!$product) {
                continue;
            }
            $quantity = max(1, (int)$item['quantity']);
            $orderItems[] = [
                'product_id' => $product['product_id'],
                'quantity' => $quantity,
                'price' => $product['price'],
            ];
            $totalAmount += $product['price'] * $quantity;
        }

        if (empty($orderItems)) {
            return ['success' => false, 'errors' => ['Cart contains invalid items.']];
        }

        $orderId = $this->orderModel->createOrder($userId, [
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
        ], $totalAmount, $orderItems);

        if (!$orderId) {
            return ['success' => false, 'errors' => ['Unable to save order.']];
        }

        return ['success' => true, 'order_id' => $orderId];
    }

    public function getOrdersForUser(int $userId): array
    {
        return $this->orderModel->getOrdersByUser($userId);
    }

    public function getAllOrders(): array
    {
        return $this->orderModel->getAllOrders();
    }

    public function getOrderDetails(int $orderId): ?array
    {
        $order = $this->orderModel->getOrderById($orderId);
        if (!$order) {
            return null;
        }
        $order['items'] = $this->orderModel->getOrderItems($orderId);
        return $order;
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        return $this->orderModel->updateStatus($orderId, $status);
    }
}
