<?php
namespace App\Controllers;

use App\Models\ProductModel;
use App\Core\FlashMessage;
use App\Database;
use App\Models\OrderModel;

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    // Hiển thị danh sách
    public function index() {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    header("Location: index.php?action=admin");
    exit();
    }
    $keyword = $_GET['keyword'] ?? '';
    $page = $_GET['page'] ?? 1;
    $limit = 8;
    $result = $this->model->getProducts($page, $limit, $keyword);

    $products = $result['data'];
    $totalPages = $result['totalPages'];

    require_once __DIR__ . '/../../views/banhang_list.php';
}

    // Thêm sản phẩm
    public function add() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = $_POST['name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $category_id = $_POST['category_id'] ?? 1;

        // upload ảnh
        $image = '';
        if (!empty($_FILES['image']['name'])) {
            $image = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
        }

        $this->model->add($name, $price, $quantity, $image, $category_id);

        header("Location: index.php?action=admin");
        exit();
    }

    require_once __DIR__ . '/../../views/banhang_add.php';
}

    // 🟢 SỬA - HIỂN THỊ FORM
    public function edit() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: index.php");
            exit();
        }

        $product = $this->model->getById($id);
        require_once __DIR__ . '/../../views/banhang_edit.php';
    }

    // 🟢 UPDATE - CẬP NHẬT
    public function update() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        if ($this->model->update($id, $name, $price, $quantity)) {
            FlashMessage::set('product', 'Cập nhật thành công!', 'success');
        } else {
            FlashMessage::set('product', 'Cập nhật thất bại!', 'error');
        }
    }

    header("Location: index.php");
    exit();
}
    // 🟢 XỬ LÝ XÓA SẢN PHẨM
    public function delete() {
    $id = $_GET['id'] ?? null;

    if ($id && $this->model->delete($id)) {
        FlashMessage::set('product', 'Xóa thành công!', 'success');
    } else {
        FlashMessage::set('product', 'Xóa thất bại!', 'error');
    }

    header("Location: index.php");
    exit();
    }
    public function dashboard() {
    $stats = $this->model->getStatistics();
    require_once __DIR__ . '/../../views/dashboard.php';
    }

    public function show() {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        header("Location: index.php");
        exit();
    }

    $product = $this->model->getById($id);

    require_once __DIR__ . '/../../views/banhang_detail.php';
}
   public function addCart() {

    $id = $_GET['id'] ?? null;
    $quantity = $_GET['quantity'] ?? 1;

    $product = $this->model->getById($id);

    if (!$product) {
        die("Sản phẩm không tồn tại");
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // nếu đã có thì cộng thêm
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $id, // 🔥 QUAN TRỌNG
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }

    header("Location: index.php?action=cart");
}

    public function cart() {
    $cart = $_SESSION['cart'] ?? [];

    require_once __DIR__ . '/../../views/cart.php';
}

    public function checkout() {
    require_once __DIR__ . '/../../views/checkout.php';
}

    public function processCheckout() {

    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "Giỏ hàng trống!";
        return;
    }

    $cart = $_SESSION['cart'];
    $user_id = $_SESSION['user_id'];

    $total = 0;

    // 🟢 TÍNH TỔNG
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // 🟢 KẾT NỐI
    $conn = \App\Database::getInstance()->getConnection();

    // 🟢 LƯU ORDER
    $stmt = $conn->prepare("
        INSERT INTO orders(user_id, total)
        VALUES (?, ?)
    ");

    $stmt->execute([$user_id, $total]);

    // 🟢 LẤY ID ĐƠN HÀNG
    $order_id = $conn->lastInsertId();

    // 🟢 LƯU CHI TIẾT
    foreach ($cart as $item) {

        $stmt = $conn->prepare("
            INSERT INTO order_details(order_id, product_name, price, quantity)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $order_id,
            $item['name'],
            $item['price'],
            $item['quantity']
        ]);
    }

    // 🧹 XÓA GIỎ
    unset($_SESSION['cart']);

    echo "<h3 style='color:green'>🎉 Đặt hàng thành công!</h3>";
}

    public function removeCart() {
    $id = $_GET['id'] ?? null;

    if ($id && isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }

    header("Location: index.php?action=cart");
}
    public function admin() {

    if ($_SESSION['role'] != 'admin') {
        die("❌ Không có quyền!");
    }

    // 👉 LẤY HẾT SẢN PHẨM (KHÔNG PHÂN TRANG)
    $result = $this->model->getProducts(1, 1000);

    $products = $result['data'];

    // 👉 TỔNG SẢN PHẨM
    $totalProducts = count($products);

    // 👉 TỔNG TỒN KHO
    $totalQuantity = 0;
    foreach ($products as $p) {
        $totalQuantity += $p['quantity'];
    }

    // 👉 DOANH THU + ĐƠN HÀNG
    $orderModel = new \App\Models\OrderModel();

    $totalRevenue = $orderModel->getTotalRevenue();
    $totalOrders = $orderModel->countOrders();

    require_once __DIR__ . '/../../views/admin.php';
}

    public function orders() {

    // 🔒 CHỈ ADMIN
    if ($_SESSION['role'] != 'admin') {
        die("❌ Không có quyền!");
    }

    $orderModel = new OrderModel();
    $orders = $orderModel->getAll();

    require_once __DIR__ . '/../../views/orders.php';
}
    public function orderDetail() {

    if ($_SESSION['role'] != 'admin') {
        die("❌ Không có quyền!");
    }

    $id = $_GET['id'];

    $orderModel = new OrderModel();
    $details = $orderModel->getDetails($id);

    require_once __DIR__ . '/../../views/order_detail.php';
}
    public function updateOrderStatus() {

    if ($_SESSION['role'] != 'admin') {
        die("❌ Không có quyền!");
    }

    $id = $_GET['id'];
    $status = $_GET['status'];

    $orderModel = new OrderModel();
    $orderModel->updateStatus($id, $status);

    header("Location: index.php?action=orders");
}
    public function exportOrdersCSV() {

    if ($_SESSION['role'] != 'admin') {
        die("❌ Không có quyền!");
    }

    require_once __DIR__ . '/../Models/OrderModel.php';
    $orderModel = new \App\Models\OrderModel();

    $orders = $orderModel->getAllOrders();

    // 🟢 HEADER DOWNLOAD FILE
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=orders.csv');

    $output = fopen('php://output', 'w');

    // 🟢 TIÊU ĐỀ
    fputcsv($output, ['ID', 'Khách hàng', 'Tổng tiền', 'Trạng thái', 'Ngày']);

    // 🟢 DATA
    foreach ($orders as $o) {
        fputcsv($output, [
            $o['id'],
            $o['user'],
            $o['total'],
            $o['status'],
            $o['created_at']
        ]);
    }

    fclose($output);
    exit();
    }

    public function updateQty() {

    $id = $_GET['id'] ?? null;
    $type = $_GET['type'] ?? null;

    if (!$id || !isset($_SESSION['cart'])) {
        header("Location: index.php?action=cart");
        return;
    }

    foreach ($_SESSION['cart'] as $key => $item) {

        if ($item['id'] == $id) {

            if ($type == 'plus') {
                $_SESSION['cart'][$key]['quantity']++;
            }

            if ($type == 'minus') {
                $_SESSION['cart'][$key]['quantity']--;

                if ($_SESSION['cart'][$key]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$key]);
                }
            }
        }
    }

    header("Location: index.php?action=cart");
    }

    public function updateQtyAjax() {

    $id = $_GET['id'];
    $type = $_GET['type'];

    if (!isset($_SESSION['cart'][$id])) {
        echo json_encode(['success' => false]);
        return;
    }

    if ($type == 'plus') {
        $_SESSION['cart'][$id]['quantity']++;
    }

    if ($type == 'minus') {
        $_SESSION['cart'][$id]['quantity']--;

        if ($_SESSION['cart'][$id]['quantity'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }

    $qty = $_SESSION['cart'][$id]['quantity'] ?? 0;
    $price = $_SESSION['cart'][$id]['price'] ?? 0;
    $subtotal = number_format($qty * $price);

    // tính tổng
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    echo json_encode([
        'success' => true,
        'qty' => $qty,
        'subtotal' => $subtotal,
        'total' => number_format($total)
    ]);
    }

    }