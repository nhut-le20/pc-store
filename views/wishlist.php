<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Danh sách yêu thích</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Danh sách yêu thích</h2>
        <a href="index.php" class="btn btn-primary">Về trang chủ</a>
    </div>

    <div class="row">
        <?php if (empty($products)): ?>
            <div class="col-12">
                <div class="alert alert-info">Bạn chưa có sản phẩm yêu thích.</div>
            </div>
        <?php endif; ?>

        <?php foreach ($products as $p): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($p['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($p['image']) ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h6><?= htmlspecialchars($p['name']) ?></h6>
                        <div class="text-warning">
                            <?php $roundedRating = (int)round($p['avg_rating'] ?? 0); ?>
                            <?= str_repeat('★', $roundedRating) ?><?= str_repeat('☆', 5 - $roundedRating) ?>
                            <span class="text-muted"><?= ($p['avg_rating'] ?? 0) > 0 ? number_format($p['avg_rating'], 1) : 'Chưa đánh giá' ?></span>
                        </div>
                        <div class="text-danger fw-bold"><?= number_format($p['price']) ?>đ</div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="index.php?action=show&id=<?= urlencode($p['id']) ?>" class="btn btn-outline-primary btn-sm">Chi tiết</a>
                        <a href="index.php?action=add_cart&id=<?= urlencode($p['id']) ?>" class="btn btn-primary btn-sm">Mua</a>
                        <a href="index.php?action=remove_wishlist&id=<?= urlencode($p['id']) ?>" class="btn btn-danger btn-sm">Xóa</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
