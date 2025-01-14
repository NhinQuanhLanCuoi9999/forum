<?php
include('../config.php');
include('../app/profile/Handle.php');
include('../app/profile/Pagination.php');

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ của <?php echo htmlspecialchars($user_info['username']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../app/profile/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hồ sơ của <?php echo htmlspecialchars($user_info['username']); ?></h1>
        </div>

        <div class="profile-info">
            <h2>Thông tin người dùng</h2>
            <p><strong>Tên người dùng:</strong> <?php echo htmlspecialchars($user_info['username']); ?></p>
            <p><strong>Mô tả:</strong> <?php echo nl2br(htmlspecialchars($user_info['desc'])); ?></p>
        </div>

        <?php if ($result_posts->num_rows > 0): ?>
        <div class="posts">
            <h2>Bài viết của <?php echo htmlspecialchars($user_info['username']); ?></h2>
            <?php while ($post = $result_posts->fetch_assoc()): ?>
            <div class="post-item">
                <h3><?php echo htmlspecialchars($post['content']); ?></h3>
                <?php if (!empty($post['description'])): ?>
                <p class="description"><em><?php echo nl2br(htmlspecialchars($post['description'])); ?></em></p>
                <?php endif; ?>
                <p><small>Đăng vào: <?php echo $post['created_at']; ?></small></p>
                <small><a href="src/view.php?id=<?php echo $post['id']; ?>" class="read-more">Xem thêm</a></small>
            </div>
            <?php endwhile; ?>
        </div>

    <!-- Hiển thị phân trang -->
<nav>
    <ul class="pagination">
        <?php if ($current_page > 1): ?>
        <li class="page-item">
            <a class="page-link" href="?username=<?php echo urlencode($user_info['username']); ?>&page=1">&laquo; Đầu</a>
        </li>
        <?php endif; ?>

        <?php 
        // Hiển thị trang đầu tiên nếu không phải trang đầu tiên
        if ($current_page > 4) echo '<li class="page-item"><a class="page-link" href="?username=' . urlencode($user_info['username']) . '&page=1">1</a></li>';
        
        // Hiển thị dấu "..." nếu có trang ở giữa
        if ($current_page > 4) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
        
        // Các trang trước trang hiện tại
        for ($i = max(1, $current_page - 3); $i < $current_page; $i++): ?>
        <li class="page-item">
            <a class="page-link" href="?username=<?php echo urlencode($user_info['username']); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
        </li>
        <?php endfor; ?>

        <!-- Trang hiện tại -->
        <li class="page-item active">
            <a class="page-link" href="#"><?php echo $current_page; ?></a>
        </li>

        <!-- Các trang sau trang hiện tại -->
        <?php for ($i = $current_page + 1; $i <= min($total_pages, $current_page + 3); $i++): ?>
        <li class="page-item">
            <a class="page-link" href="?username=<?php echo urlencode($user_info['username']); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
        </li>
        <?php endfor; ?>

        <?php 
        // Hiển thị dấu "..." nếu có trang ở giữa
        if ($current_page < $total_pages - 3) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';

        // Hiển thị trang cuối cùng nếu không phải trang cuối cùng
        if ($current_page < $total_pages - 3) echo '<li class="page-item"><a class="page-link" href="?username=' . urlencode($user_info['username']) . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
        ?>

        <?php if ($current_page < $total_pages): ?>
        <li class="page-item">
            <a class="page-link" href="?username=<?php echo urlencode($user_info['username']); ?>&page=<?php echo $total_pages; ?>">Cuối &raquo;</a>
        </li>
        <?php endif; ?>
    </ul>
</nav>


        <?php else: ?>
        <p>Chưa có bài viết nào.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Đóng kết nối
$stmt_posts->close();
$conn->close();
?>
