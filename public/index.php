
<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ProductController;
use App\Controllers\UserController;
use App\Controllers\PageController;

// Lấy action
$action = $_GET['action'] ?? 'index';

//  DANH SÁCH PUBLIC
$public_actions = [
    'index',
    'show',
    'login',
    'do_login',
    'register',
    'do_register',
    'contact',
    'submit_contact',
    'forgot',  
    'do_forgot','reset','do_reset'
];

    // 🛑 GATEKEEPER 
    if (!in_array($action, $public_actions) && !isset($_SESSION['user'])) {
    header("Location: index.php?action=login");
    exit();
}

    // PHÂN LOẠI CONTROLLER
    if (in_array($action, ['login','register','do_login','do_register','logout'])) {
        $controller = new UserController();

    } elseif (in_array($action, ['contact','submit_contact'])) {
        $controller = new PageController();

    } else {
        $controller = new ProductController();
    }

    // 🔒 CHẶN QUYỀN ADMIN
    $admin_actions = ['add','edit','update','delete','admin','orders','orderdetail','updateOrderStatus','exportOrdersCSV', 'users',
    'add_user',
    'edit_user',
    'update_user',
    'delete_user',
    'toggle_user'];
    $admin_actions = array_merge($admin_actions, [
        'coupons',
        'save_coupon',
        'delete_coupon',
        'order_detail',
        'update_order_status',
        'export_orders'
    ]);

    if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin' && in_array($action, $admin_actions)) {
        die("❌ Bạn không có quyền truy cập!");
    }

//  ROUTER
switch ($action) {

    // ===== USER =====
    case 'login':
        $controller->showLogin();
        break;

    case 'do_login':
        $controller->login();
        break;

    case 'register':
        $controller->showRegister();
        break;

    case 'do_register':
        $controller->register();
        break;

    case 'logout':
        $controller->logout();
        break;

    // ===== PRODUCT =====
    case 'add':
        $controller = new ProductController();
        $controller->add();
        break;

    case 'edit':
        $controller->edit();
        break;

    case 'update':
        $controller->update();
        break;

    case 'delete':
        $controller->delete();
        break;

    case 'dashboard':
        $controller->dashboard();
        break;

    // ===== CONTACT =====
    case 'contact':
        $controller->contact();
        break;

    case 'submit_contact':
        $controller->submit();
        break;

    // ===== CHI TIẾT
    case 'show':
        $controller->show();
        break;

    // ===== GIỎ HÀNG
    case 'add_cart':
        $controller->addCart();
        break;

    case 'cart':
        $controller->cart();
        break;

    case 'checkout':
        $controller->checkout();
        break;

    case 'process_checkout':
        $controller->processCheckout();
        break;

    case 'apply_coupon':
        $controller->applyCoupon();
        break;

    case 'remove_coupon':
        $controller->removeCoupon();
        break;

    case 'wishlist':
        $controller->wishlist();
        break;

    case 'add_wishlist':
        $controller->addWishlist();
        break;

    case 'remove_wishlist':
        $controller->removeWishlist();
        break;

    case 'customer_order_detail':
        $controller->customerOrderDetail();
        break;

    case 'cancel_customer_order':
        $controller->cancelCustomerOrder();
        break;

    case 'save_review':
        $controller->saveProductReview();
        break;

    case 'remove_cart':
        $controller->removeCart();
        break;

    // ===== HOME =====
    case 'index':
    default:
        $controller->index();
        break;

    case 'admin':
    $controller = new ProductController();
    $controller->admin();
    break;

    case 'orders':
    $controller->orders();
    break;

    case 'order_detail':
    $controller->orderDetail();
    break;

    case 'update_order_status':
    $controller->updateOrderStatus();
    break;

    case 'change_password':
    $controller = new UserController();
    $controller->changePassword();
    break;

    case 'do_change_password':
    $controller = new UserController();
    $controller->doChangePassword();
    break;

    case 'export_orders':
    $controller = new ProductController();
    $controller->exportOrdersCSV();
    break;

    case 'coupons':
    $controller = new ProductController();
    $controller->coupons();
    break;

    case 'save_coupon':
    $controller = new ProductController();
    $controller->saveCoupon();
    break;

    case 'delete_coupon':
    $controller = new ProductController();
    $controller->deleteCoupon();
    break;

    case 'update_qty':
    $controller->updateQty();
    break;

    case 'update_qty_ajax':
    $controller->updateQtyAjax();
    break;
    case 'forgot':
    (new App\Controllers\UserController())->showForgot();
    break;

case 'do_forgot':
    (new App\Controllers\UserController())->forgot();
    break;

case 'change_password':
    (new App\Controllers\UserController())->changePassword();
    break;

case 'do_change_password':
    (new App\Controllers\UserController())->doChangePassword();
    break;
    case 'profile':
    (new App\Controllers\UserController())->profile();
    break;

case 'update_profile':
    (new App\Controllers\UserController())->updateProfile();
    break;
    case 'users':
    (new App\Controllers\UserController())->listUsers();
    break;

case 'add_user':
    (new App\Controllers\UserController())->addUser();
    break;

case 'edit_user':
    (new App\Controllers\UserController())->editUser();
    break;

case 'update_user':
    (new App\Controllers\UserController())->updateUser();
    break;

case 'delete_user':
    (new App\Controllers\UserController())->deleteUser();
    break;

case 'toggle_user':
    (new App\Controllers\UserController())->toggleUser();
    break;
    case 'save_user':
    (new App\Controllers\UserController())->saveUser();
    break;
    case 'customers':
    (new App\Controllers\UserController())->customers();
    break;
    case 'posts':
    (new App\Controllers\PostController())->index();
    break;

case 'add_post':
    (new App\Controllers\PostController())->add();
    break;

case 'save_post':
    (new App\Controllers\PostController())->save();
    break;

case 'delete_post':
    (new App\Controllers\PostController())->delete();
    break;
    case 'forgot':
    (new UserController())->showForgot();
    break;

case 'do_forgot':
    (new UserController())->forgot();
    break;

case 'reset':
    (new UserController())->showReset();
    break;

case 'do_reset':
    (new UserController())->reset();
    break;
    case 'page':
    $controller->page();
    break;  
}
