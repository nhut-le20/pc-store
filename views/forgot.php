<?php use App\Core\FlashMessage; ?>
<?php FlashMessage::display(); ?>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f9;
    }

    h2 {
        text-align: center;
        margin-top: 50px;
    }

    form {
        width: 300px;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    button {
        width: 100%;
        padding: 10px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background: #0056b3;
    }
</style>

<h2>Quên mật khẩu</h2>

<form method="POST" action="index.php?action=do_forgot">
    <input type="text" name="username" placeholder="Nhập username">
    <input type="password" name="new_password" placeholder="Mật khẩu mới">
    <button>Đổi mật khẩu</button>
</form>