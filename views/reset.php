<h2>Đặt lại mật khẩu</h2>

<form method="POST" action="index.php?action=do_reset">
    <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
    <input type="password" name="password" placeholder="Mật khẩu mới">
    <button>Đổi mật khẩu</button>
</form>