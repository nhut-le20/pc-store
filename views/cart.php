<?php
use App\Core\FlashMessage;

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

<?php FlashMessage::display(); ?>

<?php if (!empty($_SESSION['last_order_id'])): ?>
    <a href="index.php?action=customer_order_detail"
       class="btn btn-outline-primary mb-3">Sản phẩm của bạn</a>
<?php endif; ?>

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

<?php
    $coupon = $_SESSION['coupon'] ?? null;
    $discount = 0;
    if ($coupon) {
        $discount = $coupon['discount_type'] === 'percent'
            ? $total * ((float)$coupon['discount_value'] / 100)
            : (float)$coupon['discount_value'];
        $discount = min($discount, $total);
    }
    $finalTotal = max(0, $total - $discount);
?>

<h4 class="text-danger">
    Tổng tiền: <span id="total"><?= number_format($total) ?> VNĐ</span>
</h4>

<form method="POST" action="index.php?action=apply_coupon" class="row g-2 mb-3" style="max-width:520px;">
    <div class="col">
        <input type="text" name="coupon_code" class="form-control"
               placeholder="Nhập mã giảm giá"
               value="<?= htmlspecialchars($coupon['code'] ?? '') ?>">
    </div>
    <div class="col-auto">
        <button class="btn btn-primary">Áp dụng</button>
    </div>
    <?php if ($coupon): ?>
        <div class="col-auto">
            <a href="index.php?action=remove_coupon" class="btn btn-outline-danger">Xóa mã</a>
        </div>
    <?php endif; ?>
</form>

<?php if ($coupon): ?>
    <p class="text-success">
        Mã <?= htmlspecialchars($coupon['code']) ?> giảm <?= number_format($discount) ?> VNĐ.
    </p>
    <h4 class="text-danger">Cần thanh toán: <?= number_format($finalTotal) ?> VNĐ</h4>
<?php endif; ?>

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
