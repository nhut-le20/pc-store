<?php if (!empty($page)): ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($page['title']) ?></title>

<style>
body {
    background: #f1f5f9;
    font-family: Arial;
}

/* CONTAINER */
.page-container {
    max-width: 900px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* TITLE */
.page-title {
    font-size: 28px;
    font-weight: bold;
    color: #2563eb;
    margin-bottom: 20px;
    border-left: 5px solid #2563eb;
    padding-left: 10px;
}

/* CONTENT */
.page-content {
    line-height: 1.8;
    color: #374151;
    font-size: 16px;
}

/* BACK BUTTON */
.btn-back {
    display: inline-block;
    margin-top: 25px;
    padding: 10px 20px;
    background: #2563eb;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.3s;
}

.btn-back:hover {
    background: #1e40af;
}

/* EMPTY */
.empty {
    text-align: center;
    padding: 50px;
    color: red;
    font-size: 18px;
}
</style>
</head>

<body>

<div class="page-container">

    <div class="page-title">
        <?= htmlspecialchars($page['title']) ?>
    </div>

    <div class="page-content">
        <?= $page['content'] ?>
    </div>

    <a href="index.php" class="btn-back">← Quay lại trang chủ</a>

</div>

</body>
</html>

<?php else: ?>

<div class="empty">
    ❌ Không có nội dung
</div>

<a href="index.php" class="btn-back">← Quay lại</a>

<?php endif; ?>