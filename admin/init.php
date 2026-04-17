<?php
require_once __DIR__ . '/../init.php';

if (!isAdminLoggedIn() && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    redirect('admin/login.php');
}
