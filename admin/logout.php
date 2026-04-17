<?php
require_once __DIR__ . '/init.php';
$authController = new AuthController();
$authController->adminLogout();
redirect('login.php');
