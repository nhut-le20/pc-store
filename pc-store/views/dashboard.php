<?php use App\Core\FlashMessage; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; background:#f4f4f4; padding:20px; }
        .container { max-width:900px; margin:auto; background:white; padding:20px; }
        .stats { display:flex; justify-content:space-around; margin-top:30px; }
        .card {
            padding:20px;
            color:white;
            border-radius:8px;
            width:200px;
            text-align:center;
        }
        .blue { background:#007bff; }
        .green { background:#28a745; }
        .orange { background:#ffc107; color:black; }
        .number { font-size:30px; font-weight:bold; }
    </style>
</head>
<body>

<div class="container">
    <h1>Dashboard Thống kê</h1>

    <div class="stats">
        <div class="card blue">
            <div class="number"><?= $stats['total'] ?? 0 ?></div>
            <div>Tổng sản phẩm</div>
        </div>

        <div class="card green">
            <div class="number"><?= $stats['total_value'] ?? 0 ?></div>
            <div>Tổng giá trị kho</div>
        </div>

        <div class="card orange">
            <div class="number"><?= $stats['low_stock'] ?? 0 ?></div>
            <div>Sắp hết hàng</div>
        </div>
    </div>

    <br>
    <a href="index.php">⬅ Quay lại</a>
</div>

</body>
</html>