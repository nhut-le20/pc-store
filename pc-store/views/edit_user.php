<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sửa user</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<h3>✏️ Sửa người dùng</h3>

<form method="POST" action="index.php?action=update_user&id=<?= $user['id'] ?>">

    <div class="mb-3">
        <label>Tên</label>
        <input type="text" name="name" value="<?= $user['name'] ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Username</label>
        <input type="text" value="<?= $user['username'] ?>" class="form-control" disabled>
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control">
            <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
            <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
        </select>
    </div>

    <button class="btn btn-primary">Cập nhật</button>
    <a href="index.php?action=users" class="btn btn-secondary">Quay lại</a>

</form>

</body>
</html>