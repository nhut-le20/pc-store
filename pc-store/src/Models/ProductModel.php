<?php
namespace App\Models;

use App\Database; // ✅ đúng
use PDO;

class ProductModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
    $stmt = $this->conn->prepare("SELECT * FROM products ORDER BY id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Lấy tất cả sản phẩm
   public function getProducts($page = 1, $limit = 8, $keyword = '') {

    $offset = ($page - 1) * $limit;
    

    // 🟢 LẤY SẢN PHẨM
    if ($keyword) {
        $sql = "SELECT * FROM products 
                WHERE name LIKE :keyword 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':keyword', "%$keyword%");
    } else {
        $sql = "SELECT * FROM products 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
    }

    $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);

    $stmt->execute();
    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // 🟢 ĐẾM TỔNG
    if ($keyword) {
        $countSql = "SELECT COUNT(*) FROM products WHERE name LIKE :keyword";
        $countStmt = $this->conn->prepare($countSql);
        $countStmt->bindValue(':keyword', "%$keyword%");
    } else {
        $countSql = "SELECT COUNT(*) FROM products";
        $countStmt = $this->conn->prepare($countSql);
    }

    $countStmt->execute();
    $total = $countStmt->fetchColumn();

    $totalPages = ceil($total / $limit);

    return [
        'data' => $data,
        'totalPages' => $totalPages
    ];
}

    // Thêm sản phẩm
   public function add($name, $price, $quantity, $image) {

    $stmt = $this->conn->prepare("
        INSERT INTO products(name, price, quantity, image)
        VALUES (?, ?, ?, ?)
    ");

    return $stmt->execute([
        $name,
        $price,
        $quantity,
        $image
    ]);
}

    // 🟢 Lấy sản phẩm theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🟢 Cập nhật sản phẩm
    public function update($id, $name, $price, $quantity) {
        $stmt = $this->conn->prepare(
            "UPDATE products SET name = :name, price = :price, quantity = :quantity WHERE id = :id"
        );

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);

        return $stmt->execute();
    }
    // 🟢 HÀM XÓA SẢN PHẨM
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
            return false;
}
    public function getStatistics() {
    $sql = "
        SELECT 
            COUNT(*) AS total,
            SUM(price * quantity) AS total_value,
            SUM(CASE WHEN quantity < 5 THEN 1 ELSE 0 END) AS low_stock
        FROM products
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    }