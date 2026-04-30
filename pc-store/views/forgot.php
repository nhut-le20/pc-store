<?php use App\Core\FlashMessage; ?>
<?php FlashMessage::display(); ?>

<h2>Quên mật khẩu</h2>

<form method="POST" action="index.php?action=do_forgot">
    <input type="text" name="username" placeholder="Nhập username">
    <input type="password" name="new_password" placeholder="Mật khẩu mới">
    <button>Đổi mật khẩu</button>
</form>