<?php
require_once __DIR__ . '/Model.php';

class User extends Model
{
    public function registerUser(array $data): bool
    {
        $sql = 'INSERT INTO users (name, email, phone, password_hash, role, created_at) VALUES (:name, :email, :phone, :password, :role, NOW())';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => 'user',
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findByPhone(string $phone): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE phone = :phone LIMIT 1');
        $stmt->execute([':phone' => $phone]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function loginUser(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            unset($user['password_hash']);
            return $user;
        }
        return null;
    }

    public function loginAdmin(string $username, string $password): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM admin WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            unset($admin['password_hash']);
            return $admin;
        }

        return null;
    }

    public function getAllUsers(): array
    {
        $stmt = $this->db->query('SELECT user_id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function getById(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT user_id, name, email, phone, role, created_at FROM users WHERE user_id = :id LIMIT 1');
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function countUsers(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM users');
        return (int)$stmt->fetchColumn();
    }
}
