<?php
namespace App\Models;

use App\Database;
use PDO;

class PostModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM posts ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($title, $content, $type, $image) {
        $stmt = $this->conn->prepare("INSERT INTO posts(title, content, type, image) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $content, $type, $image]);
    }

    public function delete($id) {
        return $this->conn->prepare("DELETE FROM posts WHERE id=?")->execute([$id]);
    }
}