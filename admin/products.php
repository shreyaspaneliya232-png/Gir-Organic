<?php
require_once __DIR__ . '/init.php';
$productController = new ProductController();
$products = $productController->listProducts();
$selectedProduct = null;
$errors = [];
$success = null;

$action = $_GET['action'] ?? null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action === 'delete' && $id > 0) {
    $productController->deleteProduct($id);
    redirect('products.php');
}

if ($action === 'edit' && $id > 0) {
    $selectedProduct = $productController->getProduct($id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['product_id'])) {
        $result = $productController->updateProduct((int)$_POST['product_id'], $_POST, $_FILES);
    } else {
        $result = $productController->saveProduct($_POST, $_FILES);
    }

    if ($result['success']) {
        $success = 'Product saved successfully.';
        redirect('products.php');
    }
    $errors = $result['errors'];
}

require_once __DIR__ . '/header.php';
?>
<h1 class="mb-4">Products</h1>
<div class="row">
    <div class="col-lg-5">
        <div class="card p-4 shadow-sm mb-4">
            <h5><?php echo $selectedProduct ? 'Edit Product' : 'Add Product'; ?></h5>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo e($success); ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo e($selectedProduct['product_id'] ?? ''); ?>">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e($selectedProduct['name'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required><?php echo e($selectedProduct['description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo e($selectedProduct['price'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="<?php echo e($selectedProduct['category'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="<?php echo e($selectedProduct['stock'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control" <?php echo $selectedProduct ? '' : 'required'; ?>>
                </div>
                <button class="btn btn-primary w-100">Save Product</button>
            </form>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card p-4 shadow-sm">
            <h5>Product List</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo e($product['product_id']); ?></td>
                                <td><?php echo e($product['name']); ?></td>
                                <td>₹<?php echo e(number_format($product['price'], 2)); ?></td>
                                <td><?php echo e($product['stock']); ?></td>
                                <td>
                                    <a href="products.php?action=edit&id=<?php echo e($product['product_id']); ?>" class="btn btn-sm btn-secondary">Edit</a>
                                    <a href="products.php?action=delete&id=<?php echo e($product['product_id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php';
