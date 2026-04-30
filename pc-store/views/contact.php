<?php use App\Core\FlashMessage; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liên hệ</title>
    <style>
        body { font-family: Arial; background:#f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh;}
        .box { background:white; padding:20px; width:400px; }
        input, textarea { width:100%; padding:8px; margin:5px 0; }
        button { width:100%; padding:10px; background:blue; color:white; }
        .flash-success { background:green; color:white; padding:10px; }
        .flash-error { background:red; color:white; padding:10px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Liên hệ</h2>

    <?php FlashMessage::display(); ?>

    <form method="POST" action="index.php?action=submit_contact">
        <input type="text" name="name" placeholder="Tên" required>
        <input type="email" name="email" placeholder="Email" required>
        <textarea name="message" placeholder="Nội dung" required></textarea>
        <button>Gửi</button>
    </form>

    <a href="index.php">← Quay lại</a>
</div>

</body>
</html>