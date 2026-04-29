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

    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //     $username = $_POST['username'] ?? '';
    //     $password = $_POST['password'] ?? '';

    //     $user = $this->model->getUserByUsername($username);

    //     if ($user && password_verify($password, $user['password'])) {

    //         $_SESSION['user'] = $user['username'];
    //         $_SESSION['user_id'] = $user['id'];
    //         $_SESSION['role'] = $user['role'];

    //         header("Location: index.php");
    //         exit();

    //     } else {

    //         \App\Core\FlashMessage::set('msg', 'Sai tài khoản hoặc mật khẩu', 'error');
    //         header("Location: index.php?action=login");
    //         exit();
    //     }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->model->getUserByUsername($username);

        // ❌ sai tài khoản hoặc mật khẩu
        if (!$user || !password_verify($password, $user['password'])) {
            \App\Core\FlashMessage::set('msg', 'Sai tài khoản hoặc mật khẩu', 'error');
            header("Location: index.php?action=login");
            exit();
        }

        // ❌ tài khoản bị khóa
        if ($user['status'] != 'active') {
            \App\Core\FlashMessage::set('msg', 'Tài khoản đã bị khóa!', 'error');
            header("Location: index.php?action=login");
            exit();
        }

        // ✅ đăng nhập thành công
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        exit();
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

    $this->model->updatePassword($user['id'], $newPass);

    FlashMessage::set('msg', 'Đổi mật khẩu thành công!', 'success');
    header("Location: index.php?action=login");
}
public function profile() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }

    $user = $this->model->findUserById($_SESSION['user_id']);

    require_once __DIR__ . '/../../views/profile.php';
}

public function updateProfile() {

    $id = $_SESSION['user_id'];

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // 🟢 upload ảnh
    $avatar = null;

    if (!empty($_FILES['avatar']['name'])) {
        $avatar = time() . '_' . $_FILES['avatar']['name'];
        move_uploaded_file($_FILES['avatar']['tmp_name'], "uploads/" . $avatar);
    }

    $this->model->updateProfile($id, $name, $phone, $address, $avatar);

    header("Location: index.php?action=profile");
}
// Danh sách user
public function listUsers() {
    $users = $this->model->getAllUsers();
    require_once __DIR__ . '/../../views/admin_users.php';
}

// Thêm user
public function addUser() {
    require_once __DIR__ . '/../../views/add_user.php';
}

// Lưu user mới
public function saveUser() {
    $this->model->create(
        $_POST['name'],
        $_POST['username'],
        $_POST['password']
    );
    header("Location: index.php?action=users");
}

// Sửa
public function editUser() {
    $user = $this->model->findUserById($_GET['id']);
    require_once __DIR__ . '/../../views/edit_user.php';
}

// Update
public function updateUser() {
    $this->model->updateUser(
        $_GET['id'],
        $_POST['name'],
        $_POST['role']
    );
    header("Location: index.php?action=users");
}

// Xóa
public function deleteUser() {
    $this->model->deleteUser($_GET['id']);
    header("Location: index.php?action=users");
}

// Khóa / mở
public function toggleUser() {
    $this->model->toggleStatus($_GET['id']);
    header("Location: index.php?action=users");
}
}