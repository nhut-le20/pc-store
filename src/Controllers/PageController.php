<?php
namespace App\Controllers;

use App\Models\ContactModel;
use App\Core\FlashMessage;

class PageController {
    private $model;

    public function __construct() {
        $this->model = new ContactModel();
    }

    public function contact() {
        require_once __DIR__ . '/../../views/contact.php';
    }

    public function submit() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';

        if (!$name || !$email || !$message) {
            FlashMessage::set('msg', 'Vui lòng nhập đủ thông tin', 'error');
        } else {
            $this->model->save($name, $email, $message);
            FlashMessage::set('msg', 'Gửi thành công!', 'success');
        }

        header("Location: index.php?action=contact");
        exit();
    }
}