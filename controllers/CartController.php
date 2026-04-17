<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../config/functions.php';

class CartController
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function addToCart(int $productId, int $quantity = 1): void
    {
        $product = $this->productModel->getById($productId);
        if (!$product || $quantity < 1) {
            return;
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    public function updateCart(int $productId, int $quantity): void
    {
        if ($quantity < 1) {
            unset($_SESSION['cart'][$productId]);
            return;
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    public function removeFromCart(int $productId): void
    {
        unset($_SESSION['cart'][$productId]);
    }

    public function getCartItems(): array
    {
        $products = $this->productModel->getByIds(array_keys($_SESSION['cart']));
        $items = [];

        foreach ($products as $product) {
            $quantity = $_SESSION['cart'][$product['product_id']] ?? 0;
            if ($quantity <= 0) {
                continue;
            }
            $items[] = [
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity,
                'subtotal' => $product['price'] * $quantity,
            ];
        }

        return $items;
    }

    public function getTotal(): float
    {
        $items = $this->getCartItems();
        $total = 0.0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    public function clearCart(): void
    {
        $_SESSION['cart'] = [];
    }
}
