<?php
namespace App\Models;

use App\Database;
use PDO;

class ContactModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function save($name, $email, $message) {
        $stmt = $this->conn->prepare(
            "INSERT INTO contacts (name, email, message)
             VALUES (:name, :email, :message)"
        );

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);

        return $stmt->execute();
    }
}