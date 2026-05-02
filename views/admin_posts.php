<?php use App\Core\FlashMessage; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý bài viết</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f1f5f9;
    font-family: Arial;
}

/* CONTAINER */
.container-box {
    max-width: 1000px;
    margin: 40px auto;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

/* TITLE */
.title {
    font-weight: bold;
    color: #1f2937;
}

/* CARD */
.card-box {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* TABLE */
.table {
    border-radius: 10px;
    overflow: hidden;
}

.table th {
    background: #2563eb;
    color: white;
}

.table tr:hover {
    background: #f3f4f6;
}

/* IMAGE */
.table img {
    border-radius: 8px;
}

/* BUTTON */
.btn {
    border-radius: 8px;
}

.btn-back {
    background: #6b7280;
    color: white;
}

.btn-back:hover {
    background: #4b5563;
}

/* TYPE BADGE */
.badge-type {
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 12px;
}

.blog { background:#3b82f6; color:white; }
.news { background:#10b981; color:white; }
.policy { background:#f59e0b; color:white; }
.about { background:#8b5cf6; color:white; }
</style>
</head>

<body>

<div class="container-box">

<div class="header">
    <h2 class="title">📰 Quản lý bài viết</h2>

    <div>
        <a href="index.php?action=admin" class="btn btn-back">← Quay lại</a>
        <a href="index.php?action=add_post" class="btn btn-success">+ Thêm bài</a>
    </div>
</div>

<?php FlashMessage::display(); ?>

<div class="card-box">

<table class="table table-bordered text-center align-middle">
<tr>
    <th>ID</th>
    <th>Tiêu đề</th>
    <th>Loại</th>
    <th>Ảnh</th>
    <th>Action</th>
</tr>

<?php foreach ($posts as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>

    <td style="text-align:left;">
        <?= htmlspecialchars($p['title']) ?>
    </td>

    <td>
        <span class="badge-type <?= $p['type'] ?>">
            <?= $p['type'] ?>
        </span>
    </td>

    <td>
        <?php if ($p['image']): ?>
            <img src="uploads/<?= $p['image'] ?>" width="80">
        <?php else: ?>
            <span class="text-muted">Không có</span>
        <?php endif; ?>
    </td>

    <td>
        <a href="index.php?action=delete_post&id=<?= $p['id'] ?>" 
           class="btn btn-danger btn-sm"
           onclick="return confirm('Xóa bài viết này?')">
           ❌ Xóa
        </a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</div>
</div>

</body>
</html>