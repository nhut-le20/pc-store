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
        "SELECT * FROM order_details WHERE order_id = ? ORDER BY id ASC"
    );
    $stmt->execute([$order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getPendingForUser($user_id) {
    $stmt = $this->conn->prepare(
        "SELECT * FROM orders WHERE user_id = ? AND status = 'pending' ORDER BY id DESC LIMIT 1"
    );
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function addToTotal($order_id, $amount) {
    $stmt = $this->conn->prepare(
        "UPDATE orders SET total = COALESCE(total, 0) + ? WHERE id = ?"
    );
    return $stmt->execute([$amount, $order_id]);
}

    public function getProductsForUser($user_id) {
    $stmt = $this->conn->prepare("
        SELECT 
            orders.id AS order_id,
            orders.total,
            orders.created_at,
            orders.status,
            order_details.product_id,
            order_details.product_name,
            order_details.price,
            order_details.quantity
        FROM orders
        JOIN order_details ON orders.id = order_details.order_id
        WHERE orders.user_id = ?
        ORDER BY order_details.id ASC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getByIdForUser($order_id, $user_id) {
    $stmt = $this->conn->prepare(
        "SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1"
    );
    $stmt->execute([$order_id, $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function cancelForUser($order_id, $user_id) {
    $this->conn->beginTransaction();

    $stmt = $this->conn->prepare(
        "DELETE order_details FROM order_details
         JOIN orders ON orders.id = order_details.order_id
         WHERE orders.id = ? AND orders.user_id = ? AND orders.status = 'pending'"
    );
    $stmt->execute([$order_id, $user_id]);

    $stmt = $this->conn->prepare(
        "DELETE FROM orders WHERE id = ? AND user_id = ? AND status = 'pending'"
    );
    $result = $stmt->execute([$order_id, $user_id]);

    $this->conn->commit();
    return $result;
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

    public function getBestSellingProducts($limit = 5) {
    $stmt = $this->conn->prepare("
        SELECT 
            order_details.product_name,
            SUM(order_details.quantity) AS sold_quantity,
            SUM(order_details.price * order_details.quantity) AS revenue
        FROM order_details
        JOIN orders ON orders.id = order_details.order_id
        GROUP BY order_details.product_name
        ORDER BY sold_quantity DESC
        LIMIT ?
    ");
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getStatusStatistics() {
    $stmt = $this->conn->query("
        SELECT status, COUNT(*) AS total
        FROM orders
        GROUP BY status
        ORDER BY total DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getMonthlyRevenue() {
    $stmt = $this->conn->query("
        SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total) AS revenue
        FROM orders
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getDailyRevenue() {
    $stmt = $this->conn->query("
        SELECT DATE(created_at) AS day, SUM(total) AS revenue
        FROM orders
        GROUP BY DATE(created_at)
        ORDER BY day DESC
        LIMIT 10
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
