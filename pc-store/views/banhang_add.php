<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h3 class="mb-4">➕ Thêm sản phẩm</h3>

    <form method="POST" action="index.php?action=add" enctype="multipart/form-data">

        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Số lượng</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Ảnh</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-success">Lưu</button>
        <a href="index.php?action=admin" class="btn btn-secondary">Quay lại</a>

    </form>

</div>

</body>
</html>