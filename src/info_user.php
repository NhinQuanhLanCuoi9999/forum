<?php
session_start(); // Bắt đầu phiên
include '../config.php';
include '../app/info/php.php';




?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> <!-- Cấm phóng to, thu nhỏ -->
    <title>Thông tin người dùng</title>
    <!-- Nhúng font Poppins -->
    <link rel="stylesheet" href="../asset/css/Poppins.css">
    <link rel="stylesheet" type="text/css" href="/app/info/styles.css">
    <script src="/app/info/desc_switch.js"></script>

</head>
<body>

<div class="container">
    <h1>THÔNG TIN TÀI KHOẢN</h1>
    <div class="user-info">
        <p><span>Tên người dùng:</span> <strong><?php echo htmlspecialchars($username); ?></strong></p>
        <div class="line"></div>
        <p><span>ID:</span> <strong><?php echo htmlspecialchars($userId); ?></strong></p>
        <div class="line"></div>
        <p><span>Ngày tạo:</span> <strong><?php echo htmlspecialchars(date('d-m-Y H:i:s', strtotime($createdAt))); ?></strong></p>
        <div class="line"></div>
        <p><span>IPv4:</span><strong><?php $ip = $_SERVER['REMOTE_ADDR']; echo htmlspecialchars($ip); ?></strong></p>
        <div class="line"></div>
        <p><span>IPv6:</span><strong><?php echo htmlspecialchars($ipv6); ?></strong></p>
        <div class="line"></div>
        <p><span>User Agent:</span><strong><?php $agent = $_SERVER['HTTP_USER_AGENT']; echo htmlspecialchars($agent); ?></strong></p>
        <div class="line"></div>
        <!-- Hiển thị mô tả bản thân và nút sửa mô tả bên cạnh -->
        <div class="desc-container">
            <p><span>Mô tả bản thân:</span> <strong><?php echo htmlspecialchars($userDesc ?: 'Chưa có mô tả.'); ?></strong></p>
            <button class="button" onclick="toggleDescForm()">Cập nhật mô tả</button>
        </div>
        <div class="line"></div>


<!-- Hiển thị thông tin Gmail và trạng thái kích hoạt -->
<p>
  <span>Gmail hiện tại:</span>
  <strong id="gmailText"><?php echo htmlspecialchars($currentGmail ?: 'Chưa có Gmail'); ?></strong>
  <button id="editGmailBtn" class="button">Chỉnh sửa</button>
</p>
<p>
  <span>Trạng thái Gmail:</span>
  <strong><?php echo $isActive == '1' ? 'Đã kích hoạt' : 'Chưa kích hoạt'; ?></strong>
</p>



<!-- Form sửa Gmail (ẩn mặc định) -->
<div id="gmailForm" style="display: none; margin-top: 10px;">
  <form method="POST" action="">
    <label for="gmail">Cập nhật Gmail:</label>
    <input type="email" id="gmail" name="gmail" value="<?php echo htmlspecialchars($currentGmail ?: ''); ?>" required>
    <button type="submit" class="button">Lưu</button>
    <button type="button" id="cancelEdit" class="button">Hủy</button>
  </form>
</div>


<script src="/app/info/gmail_switch.js"></script>


        <!-- Form sửa mô tả (ẩn mặc định) -->
        <form id="update-desc-form" method="POST" action="" style="display:none;">
            <label for="desc">Cập nhật mô tả bản thân:</label>
            <textarea id="desc" name="desc" rows="4" cols="50" placeholder="Nhập mô tả của bạn..." maxlength="255"><?php echo htmlspecialchars($userDesc); ?></textarea>
            <br>
            <button type="submit" class="button">Lưu thay đổi</button>
        </form>

        <div class="line"></div>

<link href="/asset/css/Bootstrap.min.css" rel="stylesheet">

<form method="post">
    <div class="d-flex align-items-center">
        <label class="me-2" for="switch2fa">Kích hoạt 2FA:</label>
        <?php if ($user['gmail'] !== null && $user['is_active'] == 1): ?>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="switch2fa" name="switch2fa" value="1" 
                    onchange="this.form.submit()" <?php echo ($user['2fa'] == 1 ? 'checked' : ''); ?>
                    style="transform: scale(1.3);">
            </div>
        <?php else: ?>
            <span class="text-danger">Bạn không đủ điều kiện để kích hoạt, vui lòng xác thực gmail.</span>
        <?php endif; ?>
    </div>
</form>


<div class="line"></div>
        <!-- Đổi mật khẩu -->
        <p>
            <span>Đổi mật khẩu:</span>
            <strong>
                <a href="change_password.php" class="btn-red">Click vào đây</a>
            </strong>
        </p>
        <div class="line"></div>
        <button class="button" onclick="window.location.href='index.php'">Trang chủ</button>
    </div>
</div>

</body>
</html>
