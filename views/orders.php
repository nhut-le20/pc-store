<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f5f5;
        }

        .table {
            background: white;
        }

        .badge {
            font-size: 13px;
            padding: 6px 10px;
        }
    </style>
</head>
<body>

<div class="container mt-4">

    <h2>📦 Danh sách đơn hàng</h2>

    <a href="index.php?action=admin" class="btn btn-secondary mb-3">⬅ Quay lại Admin</a>

    <table class="table table-bordered table-hover shadow">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Khách</th>
                <th>Tổng tiền</th>
                <th>Ngày</th>
                <th>Xem</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($orders as $o): ?>
        <tr>
            <td><?= $o['id'] ?></td>

            <td><?= htmlspecialchars($o['username']) ?></td>

            <td class="text-danger fw-bold">
                <?= number_format($o['total']) ?>đ
            </td>

            <td><?= $o['created_at'] ?></td>

            <!-- XEM -->
            <td>
                <a href="index.php?action=order_detail&id=<?= $o['id'] ?>" 
                   class="btn btn-primary btn-sm">Xem</a>
            </td>

            <!-- TRẠNG THÁI -->
            <td>
                <?php
                    $color = "bg-warning";

                    if ($o['status'] == "Đang giao") {
                        $color = "bg-info";
                    } elseif ($o['status'] == "Hoàn thành") {
                        $color = "bg-success";
                    }
                ?>
                <span class="badge <?= $color ?>">
                    <?= $o['status'] ?>
                </span>
            </td>

            <!-- HÀNH ĐỘNG -->
            <td>

                <a href="index.php?action=update_order_status&id=<?= $o['id'] ?>&status=Đang giao" 
                   class="btn btn-info btn-sm btn-status">
                   🚚
                </a>

                <a href="index.php?action=update_order_status&id=<?= $o['id'] ?>&status=Hoàn thành" 
                   class="btn btn-success btn-sm btn-status">
                   ✅
                </a>

            </td>

        </tr>
        <?php endforeach; ?>
        </tbody>

    </table>

</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.btn-status').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();

        let url = this.getAttribute('href');
        let isDone = url.includes('Hoàn thành');

        Swal.fire({
            title: isDone ? 'Hoàn thành đơn?' : 'Đang giao đơn?',
            text: "Xác nhận cập nhật trạng thái!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});
</script>

</body>
</html>