<?php use App\Core\FlashMessage; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng nhập</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #2563eb, #1e3a8a);
    font-family: Arial;
}

/* CONTAINER */
.login-container {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* CARD */
.login-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    width: 350px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

/* TITLE */
.login-title {
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
}

/* INPUT */
.form-control {
    border-radius: 10px;
    padding: 10px;
}

/* BUTTON */
.btn-login {
    background: #2563eb;
    color: white;
    border-radius: 10px;
    font-weight: bold;
}

.btn-login:hover {
    background: #1d4ed8;
}

/* LINKS */
.links {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    margin-top: 10px;
}

.links a {
    text-decoration: none;
    color: #2563eb;
}

.links a:hover {
    text-decoration: underline;
}

/* FOOTER */
.extra-links {
    text-align: center;
    margin-top: 15px;
}

.extra-links a {
    display: block;
    color: #2563eb;
    text-decoration: none;
    margin-top: 5px;
}
</style>
</head>

<body>

<div class="login-container">

<div class="login-card">

<h3 class="login-title">🔐 Đăng nhập</h3>

<?php FlashMessage::display(); ?>

<form action="index.php?action=do_login" method="POST">

    <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>

    <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>

    <button class="btn btn-login w-100">Đăng nhập</button>

</form>

<div class="links">
    <a href="index.php?action=forgot">Quên mật khẩu?</a>
</div>

<div class="extra-links">
    <a href="index.php?action=register">Đăng ký ngay</a>
    <a href="index.php?action=contact">Liên hệ hỗ trợ</a>
</div>

</div>

</div>

</body>
</html>