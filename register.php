<?php
require_once __DIR__ . '/init.php';
$authController = new AuthController();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $authController->register($_POST);
    if ($result['success']) {
        flash('success', 'Registration completed successfully. Please login.');
        redirect('login.php');
    }
    $errors = $result['errors'];
}

require_once __DIR__ . '/views/layout/header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="glass p-4 shadow">
                <h2><?php echo e(translate('register')); ?></h2>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="post" novalidate>
                    <div class="mb-3">
                        <label class="form-label"><?php echo e(translate('name')); ?></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo e(translate('email')); ?></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo e(translate('phone')); ?></label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo e(translate('password')); ?></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm <?php echo e(translate('password')); ?></label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button class="btn btn-forest w-100"><?php echo e(translate('register')); ?></button>
                </form>
                <p class="mt-3 text-center">Already have account? <a href="login.php"><?php echo e(translate('login')); ?></a></p>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/views/layout/footer.php';
