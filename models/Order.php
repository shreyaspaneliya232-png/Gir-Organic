<?php
require_once __DIR__ . '/Model.php';

class Order extends Model
{
    public function createOrder(int $userId, array $shipping, float $totalAmount, array $items): ?int
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO orders (user_id, total_amount, status, shipping_name, shipping_phone, shipping_address, created_at)
                 VALUES (:user_id, :total_amount, :status, :shipping_name, :shipping_phone, :shipping_address, NOW())'
            );

            $stmt->execute([
                ':user_id' => $userId,
                ':total_amount' => $totalAmount,
                ':status' => 'Pending',
                ':shipping_name' => $shipping['name'],
                ':shipping_phone' => $shipping['phone'],
                ':shipping_address' => $shipping['address'],
            ]);

            $orderId = (int)$this->db->lastInsertId();

            $itemStmt = $this->db->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)'
            );

            foreach ($items as $item) {
                $itemStmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['product_id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price'],
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $exception) {
            $this->db->rollBack();
            return null;
        }
    }

    public function getAllOrders(): array
    {
        $stmt = $this->db->query(
            'SELECT o.order_id, o.total_amount, o.status, o.created_at, u.name AS customer_name, u.email AS customer_email
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.user_id
             ORDER BY o.created_at DESC'
        );
        return $stmt->fetchAll();
    }

    public function getOrderById(int $orderId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE order_id = :id LIMIT 1');
        $stmt->execute([':id' => $orderId]);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    public function getOrderItems(int $orderId): array
    {
        $stmt = $this->db->prepare(
            'SELECT oi.*, p.name AS product_name, p.image AS product_image
             FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.product_id
             WHERE oi.order_id = :order_id'
        );
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    public function getOrdersByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT o.order_id, o.total_amount, o.status, o.created_at
             FROM orders o
             WHERE o.user_id = :user_id
             ORDER BY o.created_at DESC'
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE orders SET status = :status WHERE order_id = :id');
        return $stmt->execute([':status' => $status, ':id' => $orderId]);
    }

    public function countOrders(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM orders');
        return (int)$stmt->fetchColumn();
    }
}
