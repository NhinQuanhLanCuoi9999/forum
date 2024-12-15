<?php
include('../app/changePass/php.php');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"> <!-- Thêm font Poppins -->
    <link rel="stylesheet" type="text/css" href="/app/changePass/styles.css">
</head>
<body>
    <div class="container">
        <h2>ĐỔI MẬT KHẨU</h2>

        <!-- Hiển thị thông báo lỗi nếu có -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Hiển thị thông báo thành công nếu có -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <script>
                // Sau khi hiển thị thông báo thành công, chuyển hướng sau 3 giây
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 3000); // 3000 ms = 3 giây
            </script>
        <?php endif; ?>

        <!-- Form nhập mật khẩu -->
        <form action="change_password.php" method="POST">
            <label for="current_password">Mật khẩu cũ:</label>
            <input type="password" name="current_password" required>

            <label for="new_password">Mật khẩu mới:</label>
            <input type="password" name="new_password" required minlength="6" maxlength="30">

            <label for="confirm_password">Xác nhận mật khẩu mới:</label>
            <input type="password" name="confirm_password" required minlength="6" maxlength="30">
            <p>Chưa muốn đổi mật khẩu? <a href="index.php">Quay lại</a>.</p>
            <button type="submit" name="change_password" class="submit-btn">Đổi mật khẩu</button>
        </form>
    </div>
</body>
</html>