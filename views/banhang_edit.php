<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        input { display:block; margin:10px 0; padding:8px; width:300px; }
        button { padding:10px; }
    </style>
</head>
<body>

<h1>Sửa sản phẩm</h1>

<form method="POST" action="index.php?action=update">
    <!-- ID ẩn -->
    <input type="hidden" name="id" value="<?= $product['id'] ?>">

    <label>Tên sản phẩm:</label>
    <input type="text" name="name" value="<?= $product['name'] ?>" required>

    <label>Giá:</label>
    <input type="number" name="price" value="<?= $product['price'] ?>" required>

    <label>Số lượng:</label>
    <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>

    <button type="submit">Cập nhật</button>
</form>

<br>
<a href="index.php">⬅ Quay lại</a>

</body>
</html>