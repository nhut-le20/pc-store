<?php
namespace App\Models;

use App\Database;
use PDO;

class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function findUser($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :u");
        $stmt->bindParam(':u', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $username, $password) {

    // 🟢 CHECK TRÙNG USERNAME
        $check = $this->conn->prepare("SELECT * FROM users WHERE username = :u");
        $check->bindParam(':u', $username);
        $check->execute();

    if ($check->fetch()) {
        return "exists"; // đã tồn tại
    }

    // 🟢 HASH PASSWORD
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare(
            "INSERT INTO users (name, username, password) VALUES (:n, :u, :p)"
    );

    $stmt->bindParam(':n', $name);
    $stmt->bindParam(':u', $username);
    $stmt->bindParam(':p', $hash);

    $stmt->execute();

    return "ok";
}

   public function register($username, $password, $email) {

    // check trùng
    $check = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
    $check->execute([$username]);

    if ($check->fetch()) {
        return "exists";
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $this->conn->prepare(
        "INSERT INTO users(username, password, email, role) VALUES (?, ?, ?, 'user')"
    );

    $stmt->execute([$username, $hash, $email]);

    return "ok";
}

    public function getUserByUsername($username) {

    $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':username' => $username]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function findUserById($id) {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function updatePassword($id, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare(
            "UPDATE users SET password = ? WHERE id = ?"
        );

        return $stmt->execute([$hash, $id]);
    }
    public function updateProfile($id, $name, $phone, $address, $avatar) {

    if ($avatar) {
        $sql = "UPDATE users 
                SET name=?, phone=?, address=?, avatar=? 
                WHERE id=?";
        return $this->conn->prepare($sql)->execute([$name,$phone,$address,$avatar,$id]);
    } else {
        $sql = "UPDATE users 
                SET name=?, phone=?, address=? 
                WHERE id=?";
        return $this->conn->prepare($sql)->execute([$name,$phone,$address,$id]);
    }
}
public function getAllUsers() {
    return $this->conn->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}

public function updateUser($id, $name, $role) {
    $sql = "UPDATE users SET name=?, role=? WHERE id=?";
    return $this->conn->prepare($sql)->execute([$name, $role, $id]);
}

public function deleteUser($id) {
    return $this->conn->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
}

public function toggleStatus($id) {
    $user = $this->findUserById($id);

    $new = ($user['status'] == 'active') ? 'locked' : 'active';

    return $this->conn->prepare("UPDATE users SET status=? WHERE id=?")
        ->execute([$new, $id]);
}
public function getCustomers() {

    $sql = "
        SELECT 
            u.id,
            u.username,
            u.email,
            u.phone,
            u.address,
            COUNT(o.id) as total_orders,
            SUM(o.total) as total_spent
        FROM users u
        LEFT JOIN orders o ON u.id = o.user_id
        WHERE u.role = 'user'
        GROUP BY u.id
        ORDER BY total_spent DESC
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
public function getCustomerStats() {

    $sql = "
        SELECT 
            COUNT(*) as total_users,
            SUM(CASE WHEN created_at >= NOW() - INTERVAL 7 DAY THEN 1 ELSE 0 END) as new_users,
            SUM(CASE WHEN id IN (
                SELECT user_id FROM orders GROUP BY user_id HAVING COUNT(*) >= 3
            ) THEN 1 ELSE 0 END) as loyal_users
        FROM users
        WHERE role = 'user'
    ";

    return $this->conn->query($sql)->fetch(\PDO::FETCH_ASSOC);
}
public function setResetToken($email, $token, $expire) {
    $sql = "UPDATE users SET reset_token=?, reset_expire=? WHERE email=?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$token, $expire, $email]);
}

public function getByToken($token) {
    $sql = "SELECT * FROM users WHERE reset_token=? LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updatePasswordByToken($token, $newPass) {
    $hash = password_hash($newPass, PASSWORD_DEFAULT);

    $sql = "UPDATE users 
            SET password=?, reset_token=NULL, reset_expire=NULL 
            WHERE reset_token=?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$hash, $token]);
}
}