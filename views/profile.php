<h2>Hồ sơ cá nhân</h2>

<form method="POST" action="index.php?action=update_profile" enctype="multipart/form-data">

    <input type="text" name="name" value="<?= $user['name'] ?>" placeholder="Tên"><br>

    <input type="text" name="phone" value="<?= $user['phone'] ?>" placeholder="SĐT"><br>

    <input type="text" name="address" value="<?= $user['address'] ?>" placeholder="Địa chỉ"><br>

    <input type="file" name="avatar"><br>

    <?php if ($user['avatar']): ?>
        <img src="uploads/<?= $user['avatar'] ?>" width="100">
    <?php endif; ?>

    <button>Cập nhật</button>
</form>