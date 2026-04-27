<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Core\Mailer;
use App\Core\FlashMessage;
class UserController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function showLogin() {
        require_once __DIR__ . '/../../views/dangnhap.php';
    }

    public function showRegister() {
        require_once __DIR__ . '/../../views/dangky.php';
    }


public function register() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $email    = $_POST['email'] ?? '';

        // 🟢 VALIDATE CƠ BẢN
        if (empty($username) || empty($password) || empty($email)) {
            FlashMessage::set('msg', 'Vui lòng nhập đầy đủ thông tin!', 'error');
            header("Location: index.php?action=register");
            exit();
        }

        // 🟢 GỌI MODEL
        $result = $this->model->register($username, $password, $email);

        // 🧨 NẾU TRÙNG USERNAME
        if ($result === "exists") {
            FlashMessage::set('msg', 'Username đã tồn tại!', 'error');
            header("Location: index.php?action=register");
            exit();
        }

        // 🟢 GỬI MAIL (có try để tránh crash)
        try {
            Mailer::send(
                $email,
                "Chào mừng bạn đến PC STORE",
                "Đăng ký thành công",
                "<h2>Xin chào $username</h2>
                 <p>Bạn đã đăng ký thành công tài khoản tại <b>PC STORE</b></p>"
            );
        } catch (\Exception $e) {
            // Không cho crash web nếu lỗi mail
        }

        // 🟢 THÔNG BÁO THÀNH CÔNG
        FlashMessage::set('msg', 'Đăng ký thành công! Vui lòng đăng nhập.', 'success');

        header("Location: index.php?action=login");
        exit();
    }
}

   public function login() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->model->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");
            exit();

        } else {

            \App\Core\FlashMessage::set('msg', 'Sai tài khoản hoặc mật khẩu', 'error');
            header("Location: index.php?action=login");
            exit();
        }
    }
}

    public function logout() {
    session_unset();
    session_destroy();

    header("Location: index.php");
    exit();
}
    
    public function changePassword() {
    require_once __DIR__ . '/../../views/change_password.php';
}

    public function doChangePassword() {

        $old = $_POST['old_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        $userId = $_SESSION['user_id'] ?? null;

        // kiểm tra login
        if (!$userId) {
            header('Location: index.php?action=login');
            exit();
        }

        // 1. kiểm tra rỗng
        if (!$old || !$new || !$confirm) {
            FlashMessage::set('msg', 'Vui lòng nhập đầy đủ', 'error');
            header('Location: index.php?action=change_password');
            exit();
        }

        // 2. kiểm tra mật khẩu mới
        if ($new != $confirm) {
            FlashMessage::set('msg', 'Mật khẩu không khớp', 'error');
            header('Location: index.php?action=change_password');
            exit();
        }

        // 3. kiểm tra mật khẩu cũ
        $user = $this->model->findUserById($userId);

        if (!$user || !password_verify($old, $user['password'])) {
            FlashMessage::set('msg', 'Sai mật khẩu cũ', 'error');
            header('Location: index.php?action=change_password');
            exit();
        }

        // 4. cập nhật mật khẩu
        $this->model->updatePassword($userId, $new);

        // logout
        session_destroy();

        header('Location: index.php?action=login');
    }
    
}