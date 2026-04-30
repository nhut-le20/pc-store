<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>
</head>
<body>

<h2>Chi tiết sản phẩm</h2>

<img src="uploads/<?= $product['image'] ?>" width="200">

<p><b>ID:</b> <?= $product['id'] ?></p>
<p><b>Tên:</b> <?= $product['name'] ?></p>
<p><b>Giá:</b> <?= $product['price'] ?></p>
<p><b>Số lượng:</b> <?= $product['quantity'] ?></p>

<a href="index.php">Quay lại</a>

</body>
</html>