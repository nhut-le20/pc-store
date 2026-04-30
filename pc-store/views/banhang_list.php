<?php 

use App\Core\FlashMessage; ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PC STORE</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f1f5f9; font-family:Arial; }

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
}

.menu a {
    text-decoration:none;
    color:#111827;
    font-weight:500;
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
</style>
</head>

<body>

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
    <a href="#">CPU</a>
    <a href="#">RAM</a>
    <a href="#">VGA</a>
    <a href="#">Laptop</a>
    <a href="#">PC Gaming</a>
    <a href="#">Màn hình</a>
    <a href="#">Chuột</a>
    <a href="#">Bàn phím</a>
</div>

    <!-- SLIDER -->
    <div id="slider" style="margin:20px auto; max-width:1200px; position:relative;">
        <img id="slide-img" src="uploads/banner1.jpg">
    </div>

<!-- CONTENT -->
<div class="container mt-4">

<?php FlashMessage::display(); ?>

<h4>✨ Sản phẩm</h4>

<!-- ✅ CHỈ 1 PRODUCT LIST -->
<div class="row" id="product-list">

<?php foreach ($products as $p): ?>
<div class="col-md-3 mb-4">

<div class="card product-card">
<div class="badge-sale">HOT</div>

<?php if (!empty($p['image'])): ?>
<img src="uploads/<?= $p['image'] ?>" class="card-img-top">
<?php endif; ?>

<div class="card-body">
<h6><?= $p['name'] ?></h6>

<div class="price-new"><?= number_format($p['price']) ?>đ</div>
<div class="price-old"><?= number_format($p['price']*1.1) ?>đ</div>
</div>

<div class="card-footer text-center">
<?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

    <a href="index.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
    <a href="index.php?action=delete&id=<?= $p['id'] ?>" class="btn btn-danger btn-sm">Xóa</a>

<?php else: ?>

    <a href="index.php?action=add_cart&id=<?= $p['id'] ?>" class="btn btn-buy btn-sm">Mua</a>

<?php endif; ?>
</div>

</div>

</div>
<?php endforeach; ?>

</div>

<!-- LOAD MORE -->
<?php if ($page < $totalPages): ?>
<div class="text-center mt-4">
    <button id="loadMoreBtn" class="btn btn-primary">Xem thêm</button>
</div>
<?php endif; ?>

</div>

<!-- JS -->
<script>
let page = <?= $page ?>;

document.getElementById("loadMoreBtn")?.addEventListener("click", function() {

    page++;

    fetch("index.php?action=index&page=" + page)
    .then(res => res.text())
    .then(html => {

        let parser = new DOMParser();
        let doc = parser.parseFromString(html, "text/html");

        let newProducts = doc.querySelector("#product-list").innerHTML;

        document.getElementById("product-list").innerHTML += newProducts;

        if (!doc.querySelector("#loadMoreBtn")) {
            document.getElementById("loadMoreBtn").style.display = "none";
        }

    });
});
</script>

</body>
</html>