<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý mã giảm giá</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Quản lý khuyến mãi và mã giảm giá</h2>
        <a href="index.php?action=admin" class="btn btn-secondary">Quay lại admin</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Thêm mã giảm giá</div>
        <div class="card-body">
            <form method="POST" action="index.php?action=save_coupon" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Mã</label>
                    <input type="text" name="code" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Loại giảm</label>
                    <select name="discount_type" class="form-select">
                        <option value="percent">Phần trăm</option>
                        <option value="fixed">Số tiền</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Giá trị</label>
                    <input type="number" name="discount_value" class="form-control" min="0" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hết hạn</label>
                    <input type="date" name="expires_at" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active">active</option>
                        <option value="inactive">inactive</option>
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-success">Lưu mã</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Danh sách mã giảm giá</div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã</th>
                        <th>Loại</th>
                        <th>Giá trị</th>
                        <th>Hết hạn</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coupons as $coupon): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($coupon['code']) ?></td>
                            <td><?= htmlspecialchars($coupon['discount_type']) ?></td>
                            <td><?= number_format($coupon['discount_value']) ?></td>
                            <td><?= htmlspecialchars($coupon['expires_at'] ?? '') ?></td>
                            <td><?= htmlspecialchars($coupon['status']) ?></td>
                            <td>
                                <a href="index.php?action=delete_coupon&id=<?= urlencode($coupon['id']) ?>"
                                   onclick="return confirm('Xóa mã giảm giá này?')"
                                   class="btn btn-danger btn-sm">Xóa</a>
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
