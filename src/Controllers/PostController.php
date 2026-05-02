<?php
namespace App\Controllers;

use App\Models\PostModel;

class PostController {
    private $model;

    public function __construct() {
        $this->model = new PostModel();
    }

    public function index() {
        $posts = $this->model->getAll();
        require_once __DIR__ . '/../../views/admin_posts.php';
    }

    public function add() {
        require_once __DIR__ . '/../../views/add_post.php';
    }

    public function save() {

        $title = $_POST['title'];
        $content = $_POST['content'];
        $type = $_POST['type'];

        $image = null;

        if (!empty($_FILES['image']['name'])) {
            $image = time() . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
        }

        $this->model->add($title, $content, $type, $image);

        header("Location: index.php?action=posts");
    }

    public function delete() {
        $this->model->delete($_GET['id']);
        header("Location: index.php?action=posts");
    }
}