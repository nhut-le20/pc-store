<?php use App\Core\FlashMessage; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thêm bài viết</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f1f5f9;
    font-family: Arial;
}

/* CARD FORM */
.form-container {
    max-width: 700px;
    margin: 50px auto;
}

.form-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* TITLE */
.form-title {
    text-align: center;
    margin-bottom: 25px;
    font-weight: bold;
    color: #1f2937;
}

/* INPUT */
.form-control {
    border-radius: 10px;
    padding: 10px;
    transition: 0.2s;
}

.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 5px rgba(37,99,235,0.3);
}

/* BUTTON */
.btn-primary {
    background: #2563eb;
    border: none;
    border-radius: 10px;
    padding: 10px;
    font-weight: bold;
}

.btn-primary:hover {
    background: #1d4ed8;
}

/* PREVIEW IMAGE */
.preview-img {
    margin-top: 10px;
    max-width: 100%;
    border-radius: 10px;
    display: none;
}
</style>
</head>

<body>

<div class="form-container">

<div class="form-card">

<h3 class="form-title">📝 Thêm bài viết</h3>

<?php FlashMessage::display(); ?>

<form method="POST" action="index.php?action=save_post" enctype="multipart/form-data">

    <!-- TITLE -->
    <div class="mb-3">
        <label>Tiêu đề</label>
        <input type="text" name="title" class="form-control" placeholder="Nhập tiêu đề..." required>
    </div>

    <!-- TYPE -->
    <div class="mb-3">
        <label>Loại bài viết</label>
        <select name="type" class="form-control">
            <option value="blog">Blog</option>
            <option value="news">Tin tức</option>
            <option value="policy">Chính sách</option>
            <option value="about">Giới thiệu</option>
        </select>
    </div>

    <!-- CONTENT -->
    <div class="mb-3">
        <label>Nội dung</label>
        <textarea name="content" rows="6" class="form-control" placeholder="Nhập nội dung..."></textarea>
    </div>

    <!-- IMAGE -->
    <div class="mb-3">
        <label>Ảnh</label>
        <input type="file" name="image" class="form-control" onchange="previewImage(event)">
        <img id="preview" class="preview-img">
    </div>

    <!-- BUTTON -->
    <button class="btn btn-primary w-100">💾 Lưu bài viết</button>

</form>

</div>
</div>

<script>
function previewImage(event) {
    const img = document.getElementById('preview');
    img.src = URL.createObjectURL(event.target.files[0]);
    img.style.display = 'block';
}
</script>

</body>
</html>