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

    // ================= LOGIN / REGISTER =================

    public function showLogin() {
        require_once __DIR__ . '/../../views/dangnhap.php';
    }

    public function showRegister() {
        require_once __DIR__ . '/../../views/dangky.php';
    }

    public function register() {

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $email    = $_POST['email'] ?? '';

        if (!$username || !$password || !$email) {
            FlashMessage::set('msg', 'Nhập đầy đủ thông tin', 'error');
            header("Location: index.php?action=register");
            exit();
        }

        $result = $this->model->register($username, $password, $email);

        if ($result === "exists") {
            FlashMessage::set('msg', 'Username đã tồn tại!', 'error');
            header("Location: index.php?action=register");
            exit();
        }

        FlashMessage::set('msg', 'Đăng ký thành công!', 'success');
        header("Location: index.php?action=login");
    }

    public function login() {

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->model->getUserByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            FlashMessage::set('msg', 'Sai tài khoản hoặc mật khẩu', 'error');
            header("Location: index.php?action=login");
            exit();
        }

        if ($user['status'] != 'active') {
            FlashMessage::set('msg', 'Tài khoản bị khóa!', 'error');
            header("Location: index.php?action=login");
            exit();
        }

        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
    }

    // ================= CHANGE PASSWORD =================

    public function changePassword() {
        require_once __DIR__ . '/../../views/change_password.php';
    }

    public function doChangePassword() {

        $old = $_POST['old_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header('Location: index.php?action=login');
            exit();
        }

        if ($new != $confirm) {
            FlashMessage::set('msg', 'Mật khẩu không khớp', 'error');
            header('Location: index.php?action=change_password');
            exit();
        }

        $user = $this->model->findUserById($userId);

        if (!password_verify($old, $user['password'])) {
            FlashMessage::set('msg', 'Sai mật khẩu cũ', 'error');
            header('Location: index.php?action=change_password');
            exit();
        }

        $this->model->updatePassword($userId, $new);

        session_destroy();
        header("Location: index.php?action=login");
    }

    // ================= FORGOT PASSWORD (EMAIL TOKEN) =================

    public function showForgot() {
        require_once __DIR__ . '/../../views/forgot.php';
    }

    public function forgot() {

    $username = $_POST['username'] ?? '';
    $newPass  = $_POST['new_password'] ?? '';

    if (!$username || !$newPass) {
        FlashMessage::set('msg', 'Nhập đầy đủ thông tin', 'error');
        header("Location: index.php?action=forgot");
        exit();
    }

    $user = $this->model->getUserByUsername($username);

    if (!$user) {
        FlashMessage::set('msg', 'Không tìm thấy user', 'error');
        header("Location: index.php?action=forgot");
        exit();
    }

    // ✅ đổi mật khẩu luôn
    $this->model->updatePassword($user['id'], $newPass);

    FlashMessage::set('msg', 'Đổi mật khẩu thành công!', 'success');
    header("Location: index.php?action=login");
}

    // ================= RESET PASSWORD =================

    public function reset() {

        $token = $_GET['token'] ?? '';

        $user = $this->model->getUserByToken($token);

        if (!$user || strtotime($user['reset_expire']) < time()) {
            die("Token không hợp lệ hoặc đã hết hạn");
        }

        require_once __DIR__ . '/../../views/reset_password.php';
    }

    public function doReset() {

        $token = $_POST['token'];
        $password = $_POST['password'];

        $user = $this->model->getUserByToken($token);

        if (!$user) {
            die("Token sai");
        }

        $this->model->updatePassword($user['id'], $password);
        $this->model->clearToken($user['id']);

        FlashMessage::set('msg', 'Đổi mật khẩu thành công', 'success');
        header("Location: index.php?action=login");
    }

    // ================= PROFILE =================

    public function profile() {

        $user = $this->model->findUserById($_SESSION['user_id']);
        require_once __DIR__ . '/../../views/profile.php';
    }

    public function updateProfile() {

        $id = $_SESSION['user_id'];

        $avatar = null;

        if (!empty($_FILES['avatar']['name'])) {
            $avatar = time() . '_' . $_FILES['avatar']['name'];
            move_uploaded_file($_FILES['avatar']['tmp_name'], "uploads/" . $avatar);
        }

        $this->model->updateProfile(
            $id,
            $_POST['name'],
            $_POST['phone'],
            $_POST['address'],
            $avatar
        );

        header("Location: index.php?action=profile");
    }

    // ================= ADMIN USERS =================

    public function listUsers() {
        $users = $this->model->getAllUsers();
        require_once __DIR__ . '/../../views/admin_users.php';
    }

    public function addUser() {
        require_once __DIR__ . '/../../views/add_user.php';
    }

    public function saveUser() {
        $this->model->create(
            $_POST['name'],
            $_POST['username'],
            $_POST['password']
        );
        header("Location: index.php?action=users");
    }

    public function editUser() {
        $user = $this->model->findUserById($_GET['id']);
        require_once __DIR__ . '/../../views/edit_user.php';
    }

    public function updateUser() {
        $this->model->updateUser(
            $_GET['id'],
            $_POST['name'],
            $_POST['role']
        );
        header("Location: index.php?action=users");
    }

    public function deleteUser() {
        $this->model->deleteUser($_GET['id']);
        header("Location: index.php?action=users");
    }

    public function toggleUser() {
        $this->model->toggleStatus($_GET['id']);
        header("Location: index.php?action=users");
    }

    // ================= CUSTOMERS =================

    public function customers() {

        $customers = $this->model->getCustomers();
        $stats = $this->model->getCustomerStats();

        require_once __DIR__ . '/../../views/admin_customers.php';
    }
}