<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm của bạn</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display:none !important;
            }

            body {
                background:white !important;
            }

            .card {
                box-shadow:none !important;
            }
        }

        .highlight-row {
            background:#eff6ff;
        }
    </style>
</head>

<body class="bg-light">

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h2 class="mb-0">Sản phẩm của bạn</h2>

        <div>
            <button onclick="window.print()" class="btn btn-success">In hóa đơn</button>
            <a href="index.php?action=cart" class="btn btn-outline-secondary">Giỏ hàng</a>
            <a href="index.php" class="btn btn-primary">Về trang chủ</a>
        </div>
    </div>

    <h2 class="mb-3 d-none d-print-block">Sản phẩm của bạn</h2>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Danh sách sản phẩm đã đặt
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Sản phẩm</th>
                        <th class="text-end">Giá</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-end">Thành tiền</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th class="text-center no-print">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($orderedProducts)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Bạn chưa có sản phẩm nào đã đặt.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($orderedProducts as $item): ?>
                        <tr class="<?= (string)$highlightOrderId === (string)$item['order_id'] ? 'highlight-row' : '' ?>">
                            <td>#<?= htmlspecialchars($item['order_id']) ?></td>
                            <td>
                                <?php if (!empty($item['product_id'])): ?>
                                    <a href="index.php?action=show&id=<?= urlencode($item['product_id']) ?>">
                                        <?= htmlspecialchars($item['product_name']) ?>
                                    </a>
                                <?php else: ?>
                                    <?= htmlspecialchars($item['product_name']) ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-end"><?= number_format($item['price']) ?>đ</td>
                            <td class="text-center"><?= htmlspecialchars($item['quantity']) ?></td>
                            <td class="text-end fw-bold">
                                <?= number_format($item['price'] * $item['quantity']) ?>đ
                            </td>
                            <td><?= htmlspecialchars($item['created_at']) ?></td>
                            <td><?= htmlspecialchars($item['status'] ?? 'pending') ?></td>
                            <td class="text-center no-print">
                                <?php if (($item['status'] ?? '') === 'pending'): ?>
                                    <a href="index.php?action=cancel_customer_order&id=<?= urlencode($item['order_id']) ?>"
                                       onclick="return confirm('Bạn muốn hủy sản phẩm này?')"
                                       class="btn btn-danger btn-sm">Hủy đơn</a>
                                <?php else: ?>
                                    <span class="text-muted">Không thể hủy</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
