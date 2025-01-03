<?php
session_start();
include '../config.php'; // Kết nối database từ file config
include '../app/view/php.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <!-- Thêm Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Thêm Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../app/view/styles.css">
</head>
<body>
<div class="container">
    <h1>Bài viết</h1>
    <div class="post">
        <h2><?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?></h2>
        <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($post['description'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Tác giả:</strong> <?php echo htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Ngày tạo:</strong> <?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
        <!-- Hiển thị liên kết tải xuống nếu có tệp tin -->
<?php if ($post['file']): ?>
    <p><strong>Tệp đính kèm: </strong><a href="../uploads/<?php echo htmlspecialchars($post['file'], ENT_QUOTES, 'UTF-8'); ?>" download><?php echo htmlspecialchars($post['file'], ENT_QUOTES, 'UTF-8'); ?></a></p>
<?php endif; ?>
        <?php if ($isOwner): ?>
            <a href="view.php?id=<?php echo htmlspecialchars($postId, ENT_QUOTES, 'UTF-8'); ?>&delete_post=1" class="btn btn-danger btn-delete">Xóa bài viết</a>
        <?php endif; ?>
    </div>

    <h2>Bình luận</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['error']); ?></p>
    <?php elseif (isset($_SESSION['success'])): ?>
        <p class="success"><?php echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <?php if ($isLoggedIn): ?>
        <form action="view.php?id=<?php echo htmlspecialchars($postId, ENT_QUOTES, 'UTF-8'); ?>" method="POST">
            <textarea name="comment" placeholder="Viết bình luận..." required></textarea>
            <button type="submit">Bình luận</button>
        </form>
    <?php endif; ?>

    <div class="comments">
        <?php while ($comment = $comments->fetch_assoc()): ?>
            <div class="comment">
                <p><strong><?php echo htmlspecialchars($comment['username'], ENT_QUOTES, 'UTF-8'); ?></strong> (<?php echo htmlspecialchars($comment['created_at'], ENT_QUOTES, 'UTF-8'); ?>)</p>
                <p><?php echo preg_replace_callback('/https?:\/\/[^\s]+/', function ($matches) {
                    return '<a href="' . htmlspecialchars($matches[0], ENT_QUOTES, 'UTF-8') . '" target="_blank">' . htmlspecialchars($matches[0], ENT_QUOTES, 'UTF-8') . '</a>';
                }, htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8')); ?></p>

                <?php if ($isLoggedIn && $_SESSION['username'] === $comment['username']): ?>
                    <a href="view.php?id=<?php echo htmlspecialchars($postId, ENT_QUOTES, 'UTF-8'); ?>&delete_comment=<?php echo htmlspecialchars($comment['id'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-danger btn-delete">Xóa bình luận</a>
                    <a href="view.php?id=<?php echo htmlspecialchars($postId, ENT_QUOTES, 'UTF-8'); ?>&edit_comment=<?php echo htmlspecialchars($comment['id'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-warning btn-edit">Chỉnh sửa bình luận</a>
                <?php endif; ?>
            </div>

            <?php if (isset($_GET['edit_comment']) && $_GET['edit_comment'] == $comment['id'] && $_SESSION['username'] === $comment['username']): ?>
                <form action="view.php?id=<?php echo htmlspecialchars($postId, ENT_QUOTES, 'UTF-8'); ?>" method="POST">
                    <textarea name="comment_content" required><?php echo htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    <input type="hidden" name="comment_id" value="<?php echo htmlspecialchars($comment['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" name="edit_comment">Cập nhật bình luận</button>
                </form>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
