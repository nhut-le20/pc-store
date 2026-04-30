<?php use App\Core\FlashMessage; ?>
<?php FlashMessage::display(); ?>
<form action="index.php?action=do_register" method="POST">
    <input type="text" name="name" placeholder="Tên">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <input type="email" name="email" placeholder="Email" required>
    <button>Đăng ký</button>
</form>