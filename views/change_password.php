<?php use App\Core\FlashMessage; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5" style="max-width:500px;">

    <h3 class="text-center mb-4">🔐 Đổi mật khẩu</h3>

    <?php FlashMessage::display(); ?>

    <form action="index.php?action=do_change_password" method="POST">

        <div class="mb-3">
            <label>Mật khẩu cũ</label>
            <input type="password" name="old_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mật khẩu mới</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Xác nhận mật khẩu</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">Cập nhật</button>

    </form>

    <div class="text-center mt-3">
        <a href="index.php">← Quay về</a>
    </div>

</div>

</body>
</html>