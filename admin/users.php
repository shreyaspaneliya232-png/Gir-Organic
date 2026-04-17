<?php
require_once __DIR__ . '/init.php';
$userModel = new User();
$users = $userModel->getAllUsers();
require_once __DIR__ . '/header.php';
?>
<h1 class="mb-4">Users</h1>
<div class="card p-4 shadow-sm">
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo e($user['user_id']); ?></td>
                        <td><?php echo e($user['name']); ?></td>
                        <td><?php echo e($user['email']); ?></td>
                        <td><?php echo e($user['phone']); ?></td>
                        <td><?php echo e($user['role']); ?></td>
                        <td><?php echo e($user['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php';
