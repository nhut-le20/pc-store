<?php use App\Core\FlashMessage; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Hồ sơ cá nhân</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f1f5f9;
    font-family: Arial;
}

/* CONTAINER */
.profile-container {
    max-width: 600px;
    margin: 50px auto;
}

/* CARD */
.profile-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* TITLE */
.profile-title {
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
}

/* INPUT */
.form-control {
    border-radius: 10px;
}

/* BUTTON */
.btn-primary {
    background: #2563eb;
    border: none;
    border-radius: 10px;
}

.btn-primary:hover {
    background: #1d4ed8;
}

.btn-back {
    background: #6b7280;
    color: white;
    border-radius: 10px;
}

.btn-back:hover {
    background: #4b5563;
}

/* AVATAR */
.avatar {
    display: block;
    margin: 10px auto;
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #2563eb;
}
</style>
</head>

<body>

<div class="profile-container">

<div class="profile-card">

<h3 class="profile-title">👤 Hồ sơ cá nhân</h3>

<?php FlashMessage::display(); ?>

<form method="POST" action="index.php?action=update_profile" enctype="multipart/form-data">

    <!-- NAME -->
    <div class="mb-3">
        <label>Họ tên</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control">
    </div>

    <!-- PHONE -->
    <div class="mb-3">
        <label>Số điện thoại</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="form-control">
    </div>

    <!-- ADDRESS -->
    <div class="mb-3">
        <label>Địa chỉ</label>
        <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" class="form-control">
    </div>

    <!-- AVATAR -->
    <div class="mb-3 text-center">
        <label>Ảnh đại diện</label><br>

        <?php if (!empty($user['avatar'])): ?>
            <img src="uploads/<?= $user['avatar'] ?>" class="avatar">
        <?php else: ?>
            <img src="https://via.placeholder.com/120" class="avatar">
        <?php endif; ?>

        <input type="file" name="avatar" class="form-control mt-2">
    </div>

    <!-- BUTTON -->
    <button class="btn btn-primary w-100">💾 Cập nhật</button>

    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-back">← Quay lại trang chủ</a>
    </div>

</form>

</div>
</div>

</body>
</html>