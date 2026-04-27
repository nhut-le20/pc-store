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
}