<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý user</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { margin:0; font-family:Arial; background:#f1f5f9; }

/* SIDEBAR */
.sidebar {
    width: 240px;
    height: 100vh;
    background: #111827;
    position: fixed;
    padding-top: 20px;
}

.sidebar a {
    display:block;
    color:#9ca3af;
    padding:12px 20px;
    text-decoration:none;
    margin:5px 10px;
    border-radius:8px;
}

.sidebar a:hover {
    background:#2563eb;
    color:white;
}

/* CONTENT */
.content {
    margin-left: 240px;
    padding: 20px;
}
</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4 class="text-center text-white">👑 ADMIN</h4>

    <a href="index.php?action=admin">Dashboard</a>
    <a href="index.php?action=orders">Đơn hàng</a>
    <a href="index.php?action=users">👤 Người dùng</a>
    <a href="index.php">Trang khách</a>
</div>

<!-- CONTENT -->
<div class="content">

<h2>👤 Quản lý người dùng</h2>

<a href="index.php?action=add_user" class="btn btn-success mb-3">+ Thêm user</a>

<table class="table table-bordered text-center bg-white">
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Role</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php foreach ($users as $u): ?>
<tr>
<td><?= $u['id'] ?></td>
<td><?= $u['username'] ?></td>
<td><?= $u['role'] ?></td>
<td>
    <span class="badge bg-<?= $u['status']=='active'?'success':'danger' ?>">
        <?= $u['status'] ?>
    </span>
</td>

<td>
    <a href="index.php?action=edit_user&id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>

    <a href="index.php?action=delete_user&id=<?= $u['id'] ?>" 
       class="btn btn-danger btn-sm"
       onclick="return confirm('Xóa user?')">Xóa</a>

    <a href="index.php?action=toggle_user&id=<?= $u['id'] ?>" 
       class="btn btn-secondary btn-sm">
       <?= $u['status']=='active' ? 'Khóa' : 'Mở' ?>
    </a>
</td>
</tr>
<?php endforeach; ?>
</table>
  <a href="index.php?action=admin" class="btn btn-back">← Quay lại</a>
</div>

</body>
</html>