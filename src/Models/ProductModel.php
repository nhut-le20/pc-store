<?php
namespace App\Models;

use App\Database; // ✅ đúng
use PDO;

class ProductModel {
    private $conn;
    private const CATEGORY_KEYWORDS = [
        'CPU' => ['cpu', 'core i', 'ryzen', 'intel', 'amd'],
        'RAM' => ['ram', 'ddr3', 'ddr4', 'ddr5'],
        'VGA' => ['vga', 'gpu', 'rtx', 'gtx', 'radeon', 'geforce'],
        'Laptop' => ['laptop', 'notebook', 'macbook'],
        'Mainboard' => ['mainboard', 'motherboard', 'bo mạch chủ'],
        'Ổ cứng' => ['ssd', 'hdd', 'ổ cứng', 'o cung', 'nvme'],
        'Nguồn' => ['psu', 'nguồn', 'nguon'],
        'Case' => ['case', 'vỏ máy', 'vo may'],
        'Màn hình' => ['màn hình', 'man hinh', 'monitor'],
        'Bàn phím' => ['bàn phím', 'ban phim', 'keyboard'],
        'Chuột' => ['chuột', 'chuot', 'mouse'],
        'Tai nghe' => ['tai nghe', 'headphone', 'headset'],
    ];

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
        $this->ensureReviewsTable();
        $this->ensureWishlistsTable();
        $this->ensureCouponsTable();
    }

    public function getAll() {
    $stmt = $this->conn->prepare("SELECT * FROM products ORDER BY id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Lấy tất cả sản phẩm
   public function getProducts($page = 1, $limit = 8, $keyword = '', $category = '') {

    $offset = ($page - 1) * $limit;
    $where = [];
    $params = [];

    // 🟢 LẤY SẢN PHẨM
    if ($keyword) {
        $where[] = "name LIKE :keyword";
        $params[':keyword'] = "%$keyword%";
    }

    $categoryWhere = $this->buildCategoryWhere($category);
    if ($categoryWhere) {
        $where[] = $categoryWhere['sql'];
        $params = array_merge($params, $categoryWhere['params']);
    }

    $whereSql = $where ? " WHERE " . implode(" AND ", $where) : "";
    $sql = "SELECT * FROM products" . $whereSql . " LIMIT :limit OFFSET :offset";

    $stmt = $this->conn->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);

    $stmt->execute();
    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $data = $this->attachRatingSummaries($data);

    // 🟢 ĐẾM TỔNG
    $countSql = "SELECT COUNT(*) FROM products" . $whereSql;
    $countStmt = $this->conn->prepare($countSql);
    foreach ($params as $key => $value) {
        $countStmt->bindValue($key, $value);
    }

    $countStmt->execute();
    $total = $countStmt->fetchColumn();

    $totalPages = ceil($total / $limit);

    return [
        'data' => $data,
        'totalPages' => $totalPages
    ];
}

private function ensureReviewsTable() {
    $this->conn->exec("
        CREATE TABLE IF NOT EXISTS product_reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            user_id INT DEFAULT NULL,
            rating TINYINT NOT NULL,
            comment TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}

private function ensureWishlistsTable() {
    $this->conn->exec("
        CREATE TABLE IF NOT EXISTS wishlists (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            product_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY user_product_unique (user_id, product_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}

private function ensureCouponsTable() {
    $this->conn->exec("
        CREATE TABLE IF NOT EXISTS coupons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) NOT NULL UNIQUE,
            discount_type VARCHAR(20) NOT NULL DEFAULT 'percent',
            discount_value DECIMAL(10,2) NOT NULL DEFAULT 0,
            status VARCHAR(20) NOT NULL DEFAULT 'active',
            expires_at DATE DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}

private function attachRatingSummaries($products) {
    if (empty($products)) {
        return $products;
    }

    $ids = array_column($products, 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $this->conn->prepare("
        SELECT product_id, AVG(rating) AS avg_rating, COUNT(*) AS review_count
        FROM product_reviews
        WHERE product_id IN ($placeholders)
        GROUP BY product_id
    ");
    $stmt->execute($ids);

    $summaries = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $summaries[$row['product_id']] = [
            'avg_rating' => round((float)$row['avg_rating'], 1),
            'review_count' => (int)$row['review_count'],
        ];
    }

    foreach ($products as &$product) {
        $summary = $summaries[$product['id']] ?? ['avg_rating' => 0, 'review_count' => 0];
        $product['avg_rating'] = $summary['avg_rating'];
        $product['review_count'] = $summary['review_count'];
    }

    return $products;
}

public function getRatingSummary($product_id) {
    $stmt = $this->conn->prepare("
        SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count
        FROM product_reviews
        WHERE product_id = ?
    ");
    $stmt->execute([$product_id]);
    $summary = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        'avg_rating' => round((float)($summary['avg_rating'] ?? 0), 1),
        'review_count' => (int)($summary['review_count'] ?? 0),
    ];
}

public function getReviews($product_id) {
    $stmt = $this->conn->prepare("
        SELECT product_reviews.*, users.username
        FROM product_reviews
        LEFT JOIN users ON product_reviews.user_id = users.id
        WHERE product_reviews.product_id = ?
        ORDER BY product_reviews.id DESC
    ");
    $stmt->execute([$product_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function addReview($product_id, $user_id, $rating, $comment) {
    $rating = max(1, min(5, (int)$rating));

    $stmt = $this->conn->prepare("
        INSERT INTO product_reviews(product_id, user_id, rating, comment)
        VALUES (?, ?, ?, ?)
    ");

    return $stmt->execute([
        $product_id,
        $user_id,
        $rating,
        trim($comment)
    ]);
}

public function addWishlist($user_id, $product_id) {
    $stmt = $this->conn->prepare("
        INSERT IGNORE INTO wishlists(user_id, product_id)
        VALUES (?, ?)
    ");
    return $stmt->execute([$user_id, $product_id]);
}

public function removeWishlist($user_id, $product_id) {
    $stmt = $this->conn->prepare("
        DELETE FROM wishlists WHERE user_id = ? AND product_id = ?
    ");
    return $stmt->execute([$user_id, $product_id]);
}

public function getWishlistProductIds($user_id) {
    $stmt = $this->conn->prepare("SELECT product_id FROM wishlists WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

public function getWishlistProducts($user_id) {
    $stmt = $this->conn->prepare("
        SELECT products.*
        FROM wishlists
        JOIN products ON products.id = wishlists.product_id
        WHERE wishlists.user_id = ?
        ORDER BY wishlists.id DESC
    ");
    $stmt->execute([$user_id]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $this->attachRatingSummaries($products);
}

public function getCoupons() {
    $stmt = $this->conn->prepare("SELECT * FROM coupons ORDER BY id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getCouponByCode($code) {
    $stmt = $this->conn->prepare("
        SELECT * FROM coupons
        WHERE UPPER(code) = UPPER(?) AND status = 'active'
        AND (expires_at IS NULL OR expires_at >= CURDATE())
        LIMIT 1
    ");
    $stmt->execute([trim($code)]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function saveCoupon($code, $discount_type, $discount_value, $expires_at, $status) {
    $stmt = $this->conn->prepare("
        INSERT INTO coupons(code, discount_type, discount_value, expires_at, status)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            discount_type = VALUES(discount_type),
            discount_value = VALUES(discount_value),
            expires_at = VALUES(expires_at),
            status = VALUES(status)
    ");
    return $stmt->execute([
        strtoupper(trim($code)),
        $discount_type,
        $discount_value,
        $expires_at ?: null,
        $status
    ]);
}

public function deleteCoupon($id) {
    $stmt = $this->conn->prepare("DELETE FROM coupons WHERE id = ?");
    return $stmt->execute([$id]);
}

public function getProductCategories() {
    $stmt = $this->conn->prepare("SELECT name FROM products ORDER BY name ASC");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $counts = [];
    foreach ($products as $product) {
        $category = $this->detectCategory($product['name'] ?? '');
        if (!$category) {
            continue;
        }

        $counts[$category] = ($counts[$category] ?? 0) + 1;
    }

    $categories = [];
    foreach (array_keys(self::CATEGORY_KEYWORDS) as $category) {
        $categories[] = [
            'name' => $category,
            'count' => $counts[$category] ?? 0,
        ];
    }

    return $categories;
}

private function buildCategoryWhere($category) {
    if (!$category || empty(self::CATEGORY_KEYWORDS[$category])) {
        return null;
    }

    $conditions = [];
    $params = [];

    foreach (self::CATEGORY_KEYWORDS[$category] as $index => $keyword) {
        $param = ':category_' . $index;
        $conditions[] = "LOWER(name) LIKE " . $param;
        $params[$param] = '%' . $this->toLower($keyword) . '%';
    }

    return [
        'sql' => '(' . implode(' OR ', $conditions) . ')',
        'params' => $params,
    ];
}

private function detectCategory($name) {
    $name = $this->toLower($name);

    foreach (self::CATEGORY_KEYWORDS as $category => $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($name, $this->toLower($keyword)) !== false) {
                return $category;
            }
        }
    }

    return null;
}

private function toLower($value) {
    if (function_exists('mb_strtolower')) {
        return mb_strtolower($value, 'UTF-8');
    }

    return strtolower($value);
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
// ===== POSTS =====
public function getPostsByType($type) {
    $sql = "SELECT * FROM posts WHERE type = ? ORDER BY id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$type]);
    return $stmt->fetchAll();
}

public function getPage($type) {
    $sql = "SELECT * FROM posts WHERE type = ? LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$type]);
    return $stmt->fetch();
}

// ===== SLIDER =====
public function getSliders() {
    $sql = "SELECT * FROM sliders ORDER BY id DESC";
    return $this->conn->query($sql)->fetchAll();
}

    }
