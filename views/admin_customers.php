<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý khách hàng</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h2>👥 Quản lý khách hàng</h2>

<!-- THỐNG KÊ -->
<div class="row mb-4">

<div class="col-md-4">
    <div class="alert alert-primary">
        Tổng khách: <?= $stats['total_users'] ?>
    </div>
</div>

<div class="col-md-4">
    <div class="alert alert-success">
        Khách mới (7 ngày): <?= $stats['new_users'] ?>
    </div>
</div>

<div class="col-md-4">
    <div class="alert alert-warning">
        Khách thân thiết: <?= $stats['loyal_users'] ?>
    </div>
</div>

</div>

<!-- TABLE -->
<table class="table table-bordered text-center">

<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Đơn hàng</th>
    <th>Tổng chi</th>
    <th>Loại</th>
</tr>

<?php foreach ($customers as $c): ?>

<tr>
<td><?= $c['id'] ?></td>
<td><?= $c['username'] ?></td>
<td><?= $c['email'] ?></td>
<td><?= $c['phone'] ?></td>
<td><?= $c['address'] ?></td>
<td><?= $c['total_orders'] ?></td>
<td><?= number_format($c['total_spent']) ?> đ</td>

<td>
<?php if ($c['total_orders'] >= 3): ?>
    <span class="badge bg-success">Thân thiết</span>
<?php elseif ($c['total_orders'] == 0): ?>
    <span class="badge bg-secondary">Mới</span>
<?php else: ?>
    <span class="badge bg-primary">Bình thường</span>
<?php endif; ?>
</td>

</tr>

<?php endforeach; ?>

</table>
<div>  <a href="index.php?action=admin" class="btn btn-back">← Quay lại</a></div>
</body>
</html>