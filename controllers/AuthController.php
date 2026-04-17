<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/functions.php';

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register(array $data): array
    {
        $name = clean($data['name'] ?? '');
        $email = clean($data['email'] ?? '');
        $phone = clean($data['phone'] ?? '');
        $password = $data['password'] ?? '';
        $confirm = $data['confirm_password'] ?? '';
        $errors = [];

        if ($name === '') {
            $errors[] = translate('name') . ' is required.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = translate('email') . ' is invalid.';
        }
        if ($phone === '') {
            $errors[] = translate('phone') . ' is required.';
        }
        if (strlen($password) < 6) {
            $errors[] = translate('password') . ' must be at least 6 characters.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }
        if ($this->userModel->findByEmail($email)) {
            $errors[] = 'Email already exists.';
        }
        if ($this->userModel->findByPhone($phone)) {
            $errors[] = 'Phone already exists.';
        }

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $this->userModel->registerUser([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ]);

        return ['success' => true];
    }

    public function login(array $data): array
    {
        $email = clean($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = translate('email') . ' is invalid.';
        }
        if ($password === '') {
            $errors[] = translate('password') . ' is required.';
        }

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $user = $this->userModel->loginUser($email, $password);
        if (!$user) {
            return ['success' => false, 'errors' => ['Invalid credentials.']];
        }

        $_SESSION['user'] = $user;
        return ['success' => true];
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }

    public function adminLogin(array $data): array
    {
        $username = clean($data['username'] ?? '');
        $password = $data['password'] ?? '';
        $errors = [];

        if ($username === '') {
            $errors[] = 'Username is required.';
        }
        if ($password === '') {
            $errors[] = translate('password') . ' is required.';
        }

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $admin = $this->userModel->loginAdmin($username, $password);
        if (!$admin) {
            return ['success' => false, 'errors' => ['Invalid admin credentials.']];
        }

        $_SESSION['admin'] = $admin;
        return ['success' => true];
    }

    public function adminLogout(): void
    {
        unset($_SESSION['admin']);
        session_regenerate_id(true);
    }
}
