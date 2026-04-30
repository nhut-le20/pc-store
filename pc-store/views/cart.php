<?php
$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h2>🛒 Giỏ hàng</h2>

<?php if (empty($cart)): ?>
    <p>Giỏ hàng đang trống!</p>
    <a href="index.php" class="btn btn-primary">← Mua tiếp</a>

<?php else: ?>

<table class="table table-bordered text-center">
    <tr>
        <th>Tên</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
        <th>Xóa</th>
    </tr>

<?php 
$total = 0;

foreach ($cart as $id => $item): 
    $qty = $item['quantity']; // ✅ FIX CHUẨN
    $subtotal = $item['price'] * $qty;
    $total += $subtotal;
?>

<tr>
    <td><?= htmlspecialchars($item['name']) ?></td>
    <td><?= number_format($item['price']) ?> VNĐ</td>
    
    <td>
    <a href="index.php?action=update_qty&id=<?= $item['id'] ?>&type=minus" 
       class="btn btn-sm btn-secondary">-</a>

    <span class="mx-2"><?= $qty ?></span>

        <td>
        <button onclick="updateQty(<?= $item['id'] ?>, 'minus')" class="btn btn-sm btn-secondary">-</button>

        <span id="qty-<?= $item['id'] ?>" class="mx-2"><?= $qty ?></span>

        <button onclick="updateQty(<?= $item['id'] ?>, 'plus')" class="btn btn-sm btn-secondary">+</button>
        </td>

        <td id="subtotal-<?= $item['id'] ?>">
            <?= number_format($subtotal) ?> VNĐ
        </td>

    </td>
    <td><?= number_format($subtotal) ?> VNĐ</td>

    <td>
        <a href="index.php?action=remove_cart&id=<?= $id ?>"
           onclick="return confirm('Xóa sản phẩm này khỏi giỏ?')"
           class="btn btn-danger btn-sm">
           ❌
        </a>
    </td>
</tr>

<?php endforeach; ?>

</table>

<h4 class="text-danger">
    Tổng tiền: <span id="total"><?= number_format($total) ?> VNĐ</span>
</h4>

<a href="index.php" class="btn btn-secondary">← Tiếp tục mua</a>
<a href="index.php?action=checkout" class="btn btn-success">Thanh toán</a>

<?php endif; ?>

    <script>
    function updateQty(id, type) {

        fetch(`index.php?action=update_qty_ajax&id=${id}&type=${type}`)
        .then(res => res.json())
        .then(data => {

            if (data.success) {

                // cập nhật số lượng
                document.getElementById('qty-' + id).innerText = data.qty;

                // cập nhật thành tiền
                document.getElementById('subtotal-' + id).innerText = data.subtotal + " VNĐ";

                // cập nhật tổng
                document.getElementById('total').innerText = data.total + " VNĐ";

            } else {
                location.reload();
            }

        });
    }
    </script>

</body>
</html>