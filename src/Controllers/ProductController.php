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

    // 🔐 nếu là admin → vào dashboard
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        header("Location: index.php?action=admin");
        exit();
    }

    $keyword = $_GET['keyword'] ?? '';
    $category = $_GET['category'] ?? '';
    $page = $_GET['page'] ?? 1;
    $limit = 8;

    // 🟢 sản phẩm
    $result = $this->model->getProducts($page, $limit, $keyword, $category);
    $products = $result['data'];
    $totalPages = $result['totalPages'];
    $categories = $this->model->getProductCategories();
    $favoriteProductIds = !empty($_SESSION['user_id'])
        ? $this->model->getWishlistProductIds($_SESSION['user_id'])
        : [];

    // 🟢 BLOG + NEWS
    $posts = $this->model->getPostsByType('blog');
    $news  = $this->model->getPostsByType('news');

    // 🟢 SLIDER
    $sliders = $this->model->getSliders();

    // 🟢 LOAD VIEW
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
    $ratingSummary = $this->model->getRatingSummary($id);
    $reviews = $this->model->getReviews($id);
    $favoriteProductIds = !empty($_SESSION['user_id'])
        ? $this->model->getWishlistProductIds($_SESSION['user_id'])
        : [];

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
    $coupon = $_SESSION['coupon'] ?? null;
    $discountUsed = false;

    // 🟢 KẾT NỐI
    $conn = \App\Database::getInstance()->getConnection();
    $last_order_id = null;

    // Mỗi sản phẩm là một đơn riêng để có trạng thái và nút hủy riêng.
    foreach ($cart as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $discount = 0;

        if ($coupon && !$discountUsed) {
            $discount = $coupon['discount_type'] === 'percent'
                ? $subtotal * ((float)$coupon['discount_value'] / 100)
                : (float)$coupon['discount_value'];
            $discount = min($discount, $subtotal);
            $discountUsed = true;
        }

        $orderTotal = max(0, $subtotal - $discount);

        $stmt = $conn->prepare("
            INSERT INTO orders(user_id, total)
            VALUES (?, ?)
        ");

        $stmt->execute([$user_id, $orderTotal]);
        $order_id = $conn->lastInsertId();
        $last_order_id = $order_id;

        $stmt = $conn->prepare("
            INSERT INTO order_details(order_id, product_id, product_name, price, quantity)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $order_id,
            $item['id'],
            $item['name'],
            $item['price'],
            $item['quantity']
        ]);
    }

    // 🧹 XÓA GIỎ
    unset($_SESSION['cart']);
    unset($_SESSION['coupon']);

    $_SESSION['checkout_success_order_id'] = $last_order_id;
    $_SESSION['last_order_id'] = $last_order_id;
    header("Location: index.php");
    exit();
}

    public function applyCoupon() {

    $code = $_POST['coupon_code'] ?? '';
    $coupon = $this->model->getCouponByCode($code);

    if ($coupon) {
        $_SESSION['coupon'] = $coupon;
        FlashMessage::set('product', 'Áp dụng mã giảm giá thành công!', 'success');
    } else {
        unset($_SESSION['coupon']);
        FlashMessage::set('product', 'Mã giảm giá không hợp lệ hoặc đã hết hạn!', 'error');
    }

    header("Location: index.php?action=cart");
    exit();
}

    public function removeCoupon() {
    unset($_SESSION['coupon']);
    header("Location: index.php?action=cart");
    exit();
}

    public function customerOrderDetail() {

    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        header("Location: index.php?action=cart");
        exit();
    }

    $orderModel = new OrderModel();
    $orderedProducts = $orderModel->getProductsForUser($user_id);
    $highlightOrderId = $_GET['id'] ?? null;

    require_once __DIR__ . '/../../views/customer_order_detail.php';
}

    public function cancelCustomerOrder() {

    $id = $_GET['id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if ($id && $user_id) {
        $orderModel = new OrderModel();
        $orderModel->cancelForUser($id, $user_id);
    }

    header("Location: index.php?action=customer_order_detail");
    exit();
}

    public function saveProductReview() {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php");
        exit();
    }

    $product_id = $_POST['product_id'] ?? null;
    $rating = $_POST['rating'] ?? 5;
    $comment = $_POST['comment'] ?? '';
    $user_id = $_SESSION['user_id'] ?? null;

    if ($product_id && $user_id) {
        $this->model->addReview($product_id, $user_id, $rating, $comment);
        FlashMessage::set('product', 'Đã gửi đánh giá sản phẩm!', 'success');
    }

    header("Location: index.php?action=show&id=" . urlencode($product_id));
    exit();
}

    public function wishlist() {
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        header("Location: index.php?action=login");
        exit();
    }

    $products = $this->model->getWishlistProducts($user_id);
    require_once __DIR__ . '/../../views/wishlist.php';
}

    public function addWishlist() {
    $product_id = $_GET['id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if ($product_id && $user_id) {
        $this->model->addWishlist($user_id, $product_id);
    }

    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit();
}

    public function removeWishlist() {
    $product_id = $_GET['id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if ($product_id && $user_id) {
        $this->model->removeWishlist($user_id, $product_id);
    }

    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php?action=wishlist'));
    exit();
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
    $bestSellingProducts = $orderModel->getBestSellingProducts();
    $statusStatistics = $orderModel->getStatusStatistics();
    $monthlyRevenue = $orderModel->getMonthlyRevenue();
    $dailyRevenue = $orderModel->getDailyRevenue();

    require_once __DIR__ . '/../../views/admin.php';
}

    public function coupons() {
    if ($_SESSION['role'] != 'admin') {
        die("❌ Không có quyền!");
    }

    $coupons = $this->model->getCoupons();
    require_once __DIR__ . '/../../views/admin_coupons.php';
}

    public function saveCoupon() {
    if ($_SESSION['role'] != 'admin') {
        die("❌ Không có quyền!");
    }

    $this->model->saveCoupon(
        $_POST['code'] ?? '',
        $_POST['discount_type'] ?? 'percent',
        $_POST['discount_value'] ?? 0,
        $_POST['expires_at'] ?? null,
        $_POST['status'] ?? 'active'
    );

    header("Location: index.php?action=coupons");
    exit();
}

    public function deleteCoupon() {
    if ($_SESSION['role'] != 'admin') {
        die("❌ Không có quyền!");
    }

    $this->model->deleteCoupon($_GET['id'] ?? null);
    header("Location: index.php?action=coupons");
    exit();
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
            $o['username'],
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
public function page() {

    $type = $_GET['type']; // about / policy

    $page = $this->model->getPage($type);

    require_once __DIR__ . '/../../views/page.php';
}
    }
