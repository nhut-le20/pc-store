<?php use App\Core\FlashMessage; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PC STORE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background:#f1f5f9;
            font-family:Arial;
        }

        .navbar-custom {
            background:#2563eb;
            padding:12px 20px;
            display:flex;
            align-items:center;
        }

        .logo {
            color:white;
            font-weight:bold;
            font-size:22px;
            text-decoration:none;
        }

        .search-box input {
            width:100%;
            padding:10px;
            border:none;
            border-radius:8px;
        }

        .menu {
            background:white;
            padding:12px;
            display:flex;
            gap:15px;
            border-bottom:1px solid #ddd;
            overflow-x:auto;
            white-space:nowrap;
        }

        .menu a {
            text-decoration:none;
            color:#111827;
            font-weight:500;
            padding:6px 10px;
            border-radius:6px;
        }

        .menu a.active,
        .menu a:hover {
            background:#e0ecff;
            color:#2563eb;
        }

        .product-card {
            border-radius:12px;
            overflow:hidden;
            transition:0.3s;
            background:white;
        }

        .product-card:hover {
            transform:translateY(-6px);
            box-shadow:0 10px 20px rgba(0,0,0,0.15);
        }

        .product-card img {
            height:200px;
            object-fit:cover;
        }

        .price-new {
            color:#2563eb;
            font-weight:bold;
        }

        .price-old {
            text-decoration:line-through;
            color:gray;
        }

        .product-rating {
            color:#f59e0b;
            font-size:14px;
            min-height:22px;
        }

        .badge-sale {
            position:absolute;
            top:10px;
            left:10px;
            background:#2563eb;
            color:white;
            padding:5px 8px;
            font-size:12px;
        }

        .btn-buy {
            background:#2563eb;
            color:white;
        }

        #slider img {
            width:100%;
            height:400px;
            object-fit:cover;
        }

        /* BLOG */
        .blog-card {
            background:white;
            border-radius:10px;
            overflow:hidden;
            transition:0.3s;
        }

        .blog-card:hover {
            transform:translateY(-5px);
            box-shadow:0 10px 20px rgba(0,0,0,0.1);
        }

        .order-success-overlay {
            position:fixed;
            inset:0;
            background:rgba(15,23,42,0.45);
            display:flex;
            align-items:center;
            justify-content:center;
            z-index:9999;
            padding:20px;
        }

        .order-success-box {
            width:min(420px, 100%);
            background:white;
            border-radius:10px;
            box-shadow:0 20px 50px rgba(15,23,42,0.25);
            padding:28px;
            text-align:center;
        }

        .order-success-box h3 {
            margin-bottom:10px;
            color:#16a34a;
            font-size:26px;
        }

        .order-success-actions {
            display:flex;
            gap:12px;
            justify-content:center;
            margin-top:22px;
            flex-wrap:wrap;
        }
    </style>
</head>

<body>
<?php if (!empty($_SESSION['checkout_success_order_id'])): ?>
    <?php $successOrderId = $_SESSION['checkout_success_order_id']; ?>
    <div class="order-success-overlay">
        <div class="order-success-box">
            <h3>Đặt hàng thành công!</h3>
            <p class="mb-0">Mã đơn hàng của bạn là #<?= htmlspecialchars($successOrderId) ?></p>

            <div class="order-success-actions">
                <a href="index.php" class="btn btn-primary">Về trang chủ</a>
                <a href="index.php?action=customer_order_detail&id=<?= urlencode($successOrderId) ?>"
                   class="btn btn-outline-primary">Sản phẩm của bạn</a>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['checkout_success_order_id']); ?>
<?php endif; ?>

<!-- NAVBAR -->
<div class="navbar-custom">

    <a href="index.php" class="logo">💻 PC STORE</a>

    <div class="search-box mx-3 flex-fill">
        <form method="GET">
            <input type="text" name="keyword"
                   placeholder="Tìm sản phẩm..."
                   value="<?= htmlspecialchars($keyword ?? '') ?>">
        </form>
    </div>

    <div class="nav-right">
        <?php if (isset($_SESSION['user'])): ?>

            <a href="index.php?action=profile" style="color:white; text-decoration:none;">
                👤 <?= $_SESSION['user'] ?>
            </a>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="index.php?action=add" class="btn btn-success btn-sm">+ Thêm SP</a>
                <a href="index.php?action=admin" class="btn btn-warning btn-sm">Dashboard</a>
                <a href="index.php?action=orders" class="btn btn-info btn-sm">Đơn hàng</a>
                <a href="index.php?action=users" class="btn btn-primary btn-sm">👤 Users</a>
            <?php else: ?>
                <?php if (!empty($_SESSION['last_order_id'])): ?>
                    <a href="index.php?action=customer_order_detail"
                       class="btn btn-outline-light btn-sm">Sản phẩm của bạn</a>
                <?php endif; ?>
                <a href="index.php?action=wishlist" class="btn btn-outline-light btn-sm">Yêu thích</a>
                <a href="index.php?action=cart" class="btn btn-light btn-sm">🛒</a>
            <?php endif; ?>

            <a href="index.php?action=logout" class="btn btn-dark btn-sm">Đăng xuất</a>

        <?php else: ?>

            <a href="index.php?action=login" class="btn btn-light btn-sm">Đăng nhập</a>
            <a href="index.php?action=register" class="btn btn-warning btn-sm">Đăng ký</a>

        <?php endif; ?>
    </div>
</div>

<!-- MENU -->
<div class="menu">
    <?php
        $currentCategory = $_GET['category'] ?? '';
        $keywordQuery = !empty($keyword) ? ['keyword' => $keyword] : [];
    ?>
    <a class="<?= $currentCategory === '' ? 'active' : '' ?>"
       href="index.php<?= $keywordQuery ? '?' . http_build_query($keywordQuery) : '' ?>">Tất cả</a>

    <?php foreach (($categories ?? []) as $categoryItem): ?>
        <?php
            $categoryName = $categoryItem['name'];
            $query = array_merge($keywordQuery, ['category' => $categoryName]);
        ?>
        <a class="<?= $currentCategory === $categoryName ? 'active' : '' ?>"
           href="index.php?<?= http_build_query($query) ?>">
            <?= htmlspecialchars($categoryName) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- SLIDER -->
<div id="slider" style="margin:20px auto; max-width:1200px;">
<img id="slide-img" src="uploads/banner1.jpg">
</div>

<!-- CONTENT -->
<div class="container mt-4">

    <?php FlashMessage::display(); ?>

    <h4>✨ Sản phẩm</h4>

    <div class="row" id="product-list">
        <?php foreach ($products as $p): ?>
            <div class="col-md-3 mb-4">

                <div class="card product-card">
                    <div class="badge-sale">HOT</div>

                    <?php if (!empty($p['image'])): ?>
                        <img src="uploads/<?= $p['image'] ?>" class="card-img-top">
                    <?php endif; ?>

                    <div class="card-body">
                        <?php
                            $avgRating = $p['avg_rating'] ?? 0;
                            $reviewCount = $p['review_count'] ?? 0;
                            $roundedRating = (int)round($avgRating);
                        ?>
                        <h6>
                            <a href="index.php?action=show&id=<?= urlencode($p['id']) ?>"
                               class="text-decoration-none text-dark">
                                <?= htmlspecialchars($p['name']) ?>
                            </a>
                        </h6>
                        <div class="product-rating">
                            <?= str_repeat('★', $roundedRating) ?><?= str_repeat('☆', 5 - $roundedRating) ?>
                            <span class="text-muted">
                                <?= $avgRating > 0 ? number_format($avgRating, 1) : 'Chưa đánh giá' ?>
                                <?php if ($reviewCount > 0): ?>
                                    (<?= $reviewCount ?>)
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="price-new"><?= number_format($p['price']) ?>đ</div>
                        <div class="price-old"><?= number_format($p['price'] * 1.1) ?>đ</div>
                    </div>

                    <div class="card-footer text-center">
                        <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="index.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="index.php?action=delete&id=<?= $p['id'] ?>" class="btn btn-danger btn-sm">Xóa</a>
                        <?php else: ?>
                            <a href="index.php?action=show&id=<?= $p['id'] ?>" class="btn btn-outline-primary btn-sm">Chi tiết</a>
                            <?php if (isset($_SESSION['user'])): ?>
                                <?php if (in_array((int)$p['id'], $favoriteProductIds ?? [])): ?>
                                    <a href="index.php?action=remove_wishlist&id=<?= $p['id'] ?>" class="btn btn-outline-danger btn-sm">Bỏ thích</a>
                                <?php else: ?>
                                    <a href="index.php?action=add_wishlist&id=<?= $p['id'] ?>" class="btn btn-outline-danger btn-sm">Yêu thích</a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <a href="index.php?action=add_cart&id=<?= $p['id'] ?>" class="btn btn-buy btn-sm">Mua</a>
                        <?php endif; ?>
                    </div>

                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <!-- BLOG / NEWS -->
    <h4 class="mt-5">📰 Tin tức</h4>

    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-4 mb-3">
                <div class="blog-card">

                    <?php if ($post['image']): ?>
                        <img src="uploads/<?= $post['image'] ?>"
                             style="width:100%; height:180px; object-fit:cover;">
                    <?php endif; ?>

                    <div class="p-3">
                        <h6><?= $post['title'] ?></h6>
                        <small><?= $post['type'] ?></small>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- LINK PAGE -->
<div class="text-center mb-4">
    <a href="index.php?action=page&type=about">Giới thiệu</a> |
    <a href="index.php?action=page&type=policy">Chính sách</a>
</div>

<!-- JS SLIDER -->
<script>
    let banners = <?= json_encode($banners ?? []) ?>;
    let index = 0;

    setInterval(() => {
        if (banners.length > 0) {
            index = (index + 1) % banners.length;
            document.getElementById("slide-img").src = "uploads/" + banners[index].image;
        }
    }, 3000);
</script>

</body>
</html>
