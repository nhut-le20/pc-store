<?php
namespace App\Models;

use App\Database;
use PDO;

class OrderModel {

    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // 🟢 LẤY DANH SÁCH ĐƠN HÀNG
    public function getAll() {
    $stmt = $this->conn->prepare("
        SELECT orders.*, users.username 
        FROM orders
        JOIN users ON orders.user_id = users.id
        ORDER BY orders.id DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getDetails($order_id) {
    $stmt = $this->conn->prepare(
        "SELECT * FROM order_details WHERE order_id = ?"
    );
    $stmt->execute([$order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function updateStatus($id, $status) {
    $stmt = $this->conn->prepare(
        "UPDATE orders SET status = ? WHERE id = ?"
    );
    return $stmt->execute([$status, $id]);
}

    public function getTotalRevenue() {
    $stmt = $this->conn->query("SELECT SUM(total) as revenue FROM orders");
    return $stmt->fetch()['revenue'] ?? 0;
}

    public function countOrders() {
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM orders");
        return $stmt->fetch()['total'];
    }

    public function getAllOrders() {
    $sql = "
        SELECT orders.*, users.username 
        FROM orders
        JOIN users ON orders.user_id = users.id
        ORDER BY orders.id DESC
    ";

    $stmt = $this->conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    }