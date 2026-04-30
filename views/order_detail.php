<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>🧾 Chi tiết đơn hàng</h2>

    <a href="index.php?action=orders" class="btn btn-secondary mb-3">⬅ Quay lại</a>

    <table class="table table-bordered">

        <tr>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
        </tr>

        <?php foreach ($details as $d): ?>
        <tr>
            <td><?= $d['product_name'] ?></td>
            <td><?= number_format($d['price']) ?>đ</td>
            <td><?= $d['quantity'] ?></td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>