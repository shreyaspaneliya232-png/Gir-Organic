<?php
require_once __DIR__ . '/init.php';
$authController = new AuthController();
$authController->logout();
redirect('index.php');
