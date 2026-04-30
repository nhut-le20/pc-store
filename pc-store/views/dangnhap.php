    <?php use App\Core\FlashMessage; ?>
    <?php FlashMessage::display(); ?>
    <form action="index.php?action=do_login" method="POST">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <button>Đăng nhập</button>
    </form>
    <div class="links">
    <a href="index.php?action=forgot">Quên mật khẩu?</a>
</div>
    <a href="index.php?action=register">Đăng ký ngay</a>
    <a href="index.php?action=contact">Liên hệ hỗ trợ</a>
