<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - PC STORE</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            margin:0;
            font-family: Arial;
            background:#f1f3f6;
        }

        /* SIDEBAR */
        .sidebar {
            width: 230px;
            height: 100vh;
            background: #1f2937;
            color: white;
            position: fixed;
            padding: 20px;
        }

        .sidebar h4 {
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #ccc;
            text-decoration: none;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background: #374151;
            color: white;
        }

        /* HEADER */
        .header {
            margin-left: 230px;
            height: 60px;
            background: white;
            display:flex;
            justify-content: space-between;
            align-items:center;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* CONTENT */
        .content {
            margin-left: 230px;
            padding: 20px;
        }

        /* BOX */
        .box {
            padding: 20px;
            border-radius: 12px;
            color: white;
            transition: 0.3s;
        }

        .box:hover {
            transform: translateY(-5px);
        }

        .table img {
            border-radius: 6px;
        }
        <style>
/* ===== SIDEBAR ===== */
.sidebar {
    width: 240px;
    height: 100vh;
    background: #111827;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 20px;
}

.sidebar h4 {
    color: #fff;
    text-align: center;
    margin-bottom: 20px;
}

.sidebar a {
    display: block;
    color: #9ca3af;
    padding: 12px 20px;
    text-decoration: none;
    transition: 0.3s;
    border-radius: 8px;
    margin: 5px 10px;
}

.sidebar a:hover {
    background: #2563eb;
    color: white;
}

.sidebar a.active {
    background: #2563eb;
    color: white;
}

/* ===== CONTENT ===== */
.main-content {
    margin-left: 250px;
    padding: 20px;
}

/* ===== CARD DASHBOARD ===== */
.card-box {
    border-radius: 15px;
    color: white;
    padding: 20px;
    transition: 0.3s;
}

.card-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.bg-blue { background: #3b82f6; }
.bg-green { background: #10b981; }
.bg-orange { background: #f59e0b; }
.bg-red { background: #ef4444; }

/* ===== TABLE ===== */
.table {
    border-radius: 10px;
    overflow: hidden;
}

.table th {
    background: #2563eb;
    color: white;
}

/* ===== BUTTON ===== */
.btn {
    border-radius: 8px;
}

/* ===== HEADER ===== */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.topbar .user {
    font-weight: bold;
}
    </style>
</head>


<!-- SIDEBAR -->
    <div class="sidebar">
        <h4>👑 PC ADMIN</h4>
         <a href="index.php?action=users">👤 Người dùng</a>
        <a href="index.php?action=admin"><i class="fa fa-chart-line"></i> Dashboard</a>
        <a href="index.php?action=admin"><i class="fa fa-box"></i> Sản phẩm</a>
        <a href="index.php?action=add"><i class="fa fa-plus"></i> Thêm sản phẩm</a>
        <a href="index.php?action=orders">📦 Đơn hàng</a>
        <a href="index.php?action=coupons"><i class="fa fa-tags"></i> Mã giảm giá</a>
        <a href="index.php?action=customers"><i class="fa-solid fa-users"></i> Khách hàng</a>
        <a href="index.php"><i class="fa fa-home"></i> Trang khách</a>
        <a href="index.php?action=posts"><i class="fa-solid fa-newspaper"></i> Bài viết</a>
        <a href="index.php?action=logout"><i class="fa fa-sign-out"></i> Đăng xuất</a>
    </div>

<!-- HEADER -->
<div class="header">
    <h5>📊 Trang quản trị</h5>

    <div>
        Xin chào <b><?= $_SESSION['user'] ?></b>
    </div>
</div>

<!-- CONTENT -->
<div class="content">

    <!-- DASHBOARD -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="box" style="background:#3b82f6;">
                <h6><i class="fa fa-box"></i> Tổng sản phẩm</h6>
                <h2><?= $totalProducts ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="box" style="background:#10b981;">
                <h6><i class="fa fa-warehouse"></i> Tổng tồn kho</h6>
                <h2><?= $totalQuantity ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="box" style="background:#f59e0b;">
                <h6><i class="fa fa-dollar-sign"></i> Doanh thu</h6>
                <h2><?= number_format($totalRevenue) ?>đ</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="box" style="background:#ef4444;">
                <h6><i class="fa fa-shopping-cart"></i> Đơn hàng</h6>
                <h2><?= $totalOrders ?></h2>
            </div>
        </div>

    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            📊 Biểu đồ doanh thu
        </div>

        <div class="card-body">
            <canvas id="revenueChart"></canvas>
        </div>

    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header bg-success text-white">🏆 Sản phẩm bán chạy</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Đã bán</th>
                            <th>Doanh thu</th>
                        </tr>
                        <?php foreach (($bestSellingProducts ?? []) as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><?= htmlspecialchars($item['sold_quantity']) ?></td>
                                <td><?= number_format($item['revenue']) ?>đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header bg-info text-white">📦 Tình trạng đơn hàng</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tr>
                            <th>Trạng thái</th>
                            <th>Số lượng</th>
                        </tr>
                        <?php foreach (($statusStatistics ?? []) as $status): ?>
                            <tr>
                                <td><?= htmlspecialchars($status['status'] ?? 'pending') ?></td>
                                <td><?= htmlspecialchars($status['total']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-secondary text-white">📅 Doanh thu 10 ngày gần nhất</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <tr>
                    <th>Ngày</th>
                    <th>Doanh thu</th>
                </tr>
                <?php foreach (($dailyRevenue ?? []) as $day): ?>
                    <tr>
                        <td><?= htmlspecialchars($day['day']) ?></td>
                        <td><?= number_format($day['revenue']) ?>đ</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

        <a href="index.php?action=export_orders" 
        class="btn btn-success mb-3">
             ⬇ Xuất CSV đơn hàng
        </a>

        <a href="index.php?action=add" class="btn btn-success mb-3">
    ➕ Thêm sản phẩm
</a>

    <!-- TABLE -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            📦 Quản lý sản phẩm
        </div>

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên</th>
                        <th>Giá</th>
                        <th>SL</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>

                        <td>
                            <?php if (!empty($p['image'])): ?>
                                <img src="uploads/<?= $p['image'] ?>" width="60">
                            <?php endif; ?>
                        </td>

                        <td><?= $p['name'] ?></td>

                        <td class="text-danger fw-bold">
                            <?= number_format($p['price']) ?>đ
                        </td>

                        <td><?= $p['quantity'] ?></td>

                        <td>
                            <a href="index.php?action=edit&id=<?= $p['id'] ?>" 
                               class="btn btn-warning btn-sm">
                               <i class="fa fa-edit"></i>
                            </a>

                            <a href="index.php?action=delete&id=<?= $p['id'] ?>" 
                               onclick="return confirm('Xóa sản phẩm?')" 
                               class="btn btn-danger btn-sm">
                               <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    const ctx = document.getElementById('revenueChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($monthlyRevenue ?? [], 'month')) ?>,
            datasets: [{
                label: 'VNĐ',
                data: <?= json_encode(array_map('floatval', array_column($monthlyRevenue ?? [], 'revenue'))) ?>,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
    </script>

</body>
</html>
