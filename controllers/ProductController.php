<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/functions.php';

class ProductController
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function listProducts(): array
    {
        return $this->productModel->getAll();
    }

    public function getProduct(int $id): ?array
    {
        return $this->productModel->getById($id);
    }

    public function saveProduct(array $data, array $file = null): array
    {
        $name = clean($data['name'] ?? '');
        $description = clean($data['description'] ?? '');
        $price = filter_var($data['price'] ?? '', FILTER_VALIDATE_FLOAT);
        $category = clean($data['category'] ?? '');
        $stock = filter_var($data['stock'] ?? '', FILTER_VALIDATE_INT);
        $errors = [];

        if ($name === '') {
            $errors[] = translate('name') . ' is required.';
        }
        if ($description === '') {
            $errors[] = translate('description') . ' is required.';
        }
        if ($price === false || $price < 0) {
            $errors[] = translate('price') . ' is invalid.';
        }
        if ($category === '') {
            $errors[] = translate('category') . ' is required.';
        }
        if ($stock === false || $stock < 0) {
            $errors[] = translate('stock') . ' is invalid.';
        }

        $imageName = '';
        if ($file && isset($file['image']) && $file['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $imageUpload = $this->uploadImage($file['image']);
            if ($imageUpload['success']) {
                $imageName = $imageUpload['filename'];
            } else {
                $errors[] = $imageUpload['error'];
            }
        }

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $productData = [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category' => $category,
            'stock' => $stock,
            'image' => $imageName,
        ];

        return ['success' => $this->productModel->create($productData)];
    }

    public function updateProduct(int $id, array $data, array $file = null): array
    {
        $name = clean($data['name'] ?? '');
        $description = clean($data['description'] ?? '');
        $price = filter_var($data['price'] ?? '', FILTER_VALIDATE_FLOAT);
        $category = clean($data['category'] ?? '');
        $stock = filter_var($data['stock'] ?? '', FILTER_VALIDATE_INT);
        $errors = [];

        if ($name === '') {
            $errors[] = translate('name') . ' is required.';
        }
        if ($description === '') {
            $errors[] = translate('description') . ' is required.';
        }
        if ($price === false || $price < 0) {
            $errors[] = translate('price') . ' is invalid.';
        }
        if ($category === '') {
            $errors[] = translate('category') . ' is required.';
        }
        if ($stock === false || $stock < 0) {
            $errors[] = translate('stock') . ' is invalid.';
        }

        $dataPayload = [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category' => $category,
            'stock' => $stock,
        ];

        if ($file && isset($file['image']) && $file['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $imageUpload = $this->uploadImage($file['image']);
            if ($imageUpload['success']) {
                $dataPayload['image'] = $imageUpload['filename'];
            } else {
                $errors[] = $imageUpload['error'];
            }
        }

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        return ['success' => $this->productModel->update($id, $dataPayload)];
    }

    public function deleteProduct(int $id): bool
    {
        return $this->productModel->delete($id);
    }

    public function uploadImage(array $file): array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Image upload failed.'];
        }

        $allowed = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file['type'], $allowed, true)) {
            return ['success' => false, 'error' => 'Only JPG, JPEG, and PNG files are allowed.'];
        }

        if ($file['size'] > MAX_UPLOAD_SIZE) {
            return ['success' => false, 'error' => 'Image must be less than 2MB.'];
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeName = 'product_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $destination = IMAGE_UPLOAD_DIR . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'error' => 'Unable to save uploaded image.'];
        }

        return ['success' => true, 'filename' => $safeName];
    }
}
