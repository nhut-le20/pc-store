<?php use App\Core\FlashMessage; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background:#f1f5f9;
        }

        .product-image {
            width:100%;
            max-height:360px;
            object-fit:contain;
            background:white;
            border-radius:8px;
        }

        .stars {
            color:#f59e0b;
            font-size:18px;
            letter-spacing:1px;
        }

        .rating-option {
            display:inline-flex;
            align-items:center;
            gap:4px;
            margin-right:12px;
        }
    </style>
</head>

<body>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Chi tiết sản phẩm</h2>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </div>

    <?php FlashMessage::display(); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-5">
                    <?php if (!empty($product['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" class="product-image">
                    <?php endif; ?>
                </div>

                <div class="col-md-7">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>

                    <?php
                        $avgRating = $ratingSummary['avg_rating'] ?? 0;
                        $reviewCount = $ratingSummary['review_count'] ?? 0;
                        $roundedRating = (int)round($avgRating);
                    ?>

                    <div class="stars mb-2">
                        <?= str_repeat('★', $roundedRating) ?><?= str_repeat('☆', 5 - $roundedRating) ?>
                        <span class="text-muted fs-6">
                            <?= $avgRating > 0 ? number_format($avgRating, 1) : 'Chưa có đánh giá' ?>
                            <?php if ($reviewCount > 0): ?>
                                (<?= $reviewCount ?> đánh giá)
                            <?php endif; ?>
                        </span>
                    </div>

                    <p><b>Mã sản phẩm:</b> <?= htmlspecialchars($product['id']) ?></p>
                    <p><b>Giá:</b> <span class="text-danger fw-bold"><?= number_format($product['price']) ?>đ</span></p>
                    <p><b>Số lượng:</b> <?= htmlspecialchars($product['quantity']) ?></p>

                    <a href="index.php?action=add_cart&id=<?= urlencode($product['id']) ?>" class="btn btn-primary">
                        Thêm vào giỏ
                    </a>
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if (in_array((int)$product['id'], $favoriteProductIds ?? [])): ?>
                            <a href="index.php?action=remove_wishlist&id=<?= urlencode($product['id']) ?>" class="btn btn-outline-danger">
                                Bỏ yêu thích
                            </a>
                        <?php else: ?>
                            <a href="index.php?action=add_wishlist&id=<?= urlencode($product['id']) ?>" class="btn btn-outline-danger">
                                Thêm yêu thích
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            Bình luận và đánh giá
        </div>

        <div class="card-body">
            <?php if (isset($_SESSION['user'])): ?>
                <form method="POST" action="index.php?action=save_review">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Đánh giá sao</label>
                        <div>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <label class="rating-option">
                                    <input type="radio" name="rating" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?>>
                                    <span><?= $i ?> sao</span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Bình luận</label>
                        <textarea name="comment" class="form-control" rows="4" required></textarea>
                    </div>

                    <button class="btn btn-success">Gửi đánh giá</button>
                </form>
            <?php else: ?>
                <p class="mb-0">
                    <a href="index.php?action=login">Đăng nhập</a> để bình luận và đánh giá sản phẩm.
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Danh sách bình luận
        </div>

        <div class="card-body">
            <?php if (empty($reviews)): ?>
                <p class="text-muted mb-0">Chưa có bình luận nào cho sản phẩm này.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <?php $rating = (int)$review['rating']; ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <b><?= htmlspecialchars($review['username'] ?? 'Khách hàng') ?></b>
                            <small class="text-muted"><?= htmlspecialchars($review['created_at']) ?></small>
                        </div>
                        <div class="stars">
                            <?= str_repeat('★', $rating) ?><?= str_repeat('☆', 5 - $rating) ?>
                        </div>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
