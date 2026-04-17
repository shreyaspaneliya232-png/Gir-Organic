<?php
require_once __DIR__ . '/Model.php';

class Product extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM products ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE product_id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch();
        return $product ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO products (name, description, price, image, category, stock, created_at)
             VALUES (:name, :description, :price, :image, :category, :stock, NOW())'
        );

        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':image' => $data['image'],
            ':category' => $data['category'],
            ':stock' => $data['stock'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $fields = [
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'category' => $data['category'],
            'stock' => $data['stock'],
            'product_id' => $id,
        ];

        if (!empty($data['image'])) {
            $sql = 'UPDATE products SET name = :name, description = :description, price = :price, image = :image, category = :category, stock = :stock WHERE product_id = :product_id';
            $fields['image'] = $data['image'];
        } else {
            $sql = 'UPDATE products SET name = :name, description = :description, price = :price, category = :category, stock = :stock WHERE product_id = :product_id';
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($fields);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE product_id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function countProducts(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM products');
        return (int)$stmt->fetchColumn();
    }

    public function getByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $in = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare('SELECT * FROM products WHERE product_id IN (' . $in . ')');
        $stmt->execute($ids);
        return $stmt->fetchAll();
    }
}
