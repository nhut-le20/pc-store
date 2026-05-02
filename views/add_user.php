<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thêm user</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

<body class="container mt-5">

<h3>➕ Thêm người dùng</h3>

<form method="POST" action="index.php?action=save_user">

    <div class="mb-3">
        <label>Tên</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button class="btn btn-success">Lưu</button>
    <a href="index.php?action=users" class="btn btn-secondary">Quay lại</a>

</form>

</body>
</html>