<?php

// Kiểm tra CSRF token
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post'])) {
    if (!isset($_POST['csrf_token2']) || $_POST['csrf_token2'] !== $_SESSION['csrf_token2']) {
        $_SESSION['error'] = "Yêu cầu không hợp lệ.";
        header("Location: index.php");
        exit();
    }

    $content = $_POST['content'];
    $description = $_POST['description'];
    $username = $_SESSION['username'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Kiểm tra nếu nội dung không rỗng và có chứa từ cấm
    if ((!empty($content) && containsBadWords($content)) || 
        (!empty($description) && containsBadWords($description))) {
        $_SESSION['error_message'] = "Nội dung không phù hợp, vui lòng kiểm tra lại.";
        header("Location: index.php");
        exit();
    }

    // Kiểm tra nếu đang trong thời gian chờ (10 phút) để đăng bài
    if (isset($_SESSION['post_cooldown'])) {
        $remaining_time = $_SESSION['post_cooldown'] - time();
        if ($remaining_time > 0) {
            $_SESSION['error_message'] = "Bạn chỉ có thể đăng bài sau " . ceil($remaining_time / 60) . " phút nữa.";
            header("Location: index.php");
            exit();
        }
    }

    // Kiểm tra có emoji trong nội dung và mô tả
    if (preg_match('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', $content) || 
        preg_match('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', $description)) {
        $_SESSION['error'] = "Nội dung và mô tả không được chứa emoji.";
        header("Location: index.php");
        exit();
    }

    $newFileName = null; // Khởi tạo biến cho tên tệp mới

    // Các loại MIME type được phép
    $allowedTypes = [
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
        'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain', 'application/zip', 'application/x-zip-compressed', 'application/x-rar-compressed', 
        'application/x-tar', 'application/gzip', 'application/pdf', 'image/jpeg', 'image/png', 'image/gif', 
        'image/bmp', 'image/tiff', 'video/mp4', 'video/x-msvideo', 'video/x-matroska', 'video/quicktime', 
        'audio/mpeg', 'audio/wav', 'audio/ogg', 'application/json', 'application/xml', 'text/csv', 'application/epub+zip',
        'application/vnd.android.package-archive', 'application/x-iso9660-image', 'application/x-bittorrent',
        'application/x-font-ttf', 'application/x-font-otf', 'application/font-woff', 'application/font-woff2', 
        'application/vnd.ms-fontobject', 'application/x-font-woff', 'text/html', 'application/javascript',
        'text/css', 'application/javascript', 'application/x-sh', 'application/x-bash', 'text/x-python', 'application/x-php'
    ];

    // Kiểm tra nếu có tệp tin được tải lên
    if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Kiểm tra loại tệp
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = "Bạn không thể đăng loại tệp này";
            header("Location: index.php");
            exit();
        } elseif ($fileSize > 5 * 1024 * 1024) {
            $_SESSION['error'] = "Bạn chỉ có thể đăng tệp dưới 5 MB.";
            header("Location: index.php");
            exit();
        } elseif ($fileError !== 0) {
            $_SESSION['error'] = "Có lỗi khi tải tệp: $fileError.";
            header("Location: index.php");
            exit();
        } else {
            // Tạo tên tệp mới để tránh trùng lặp
            $newFileName = uniqid('', true) . '.' . $fileExt;
            $fileDestination = 'uploads/' . $newFileName;

            // Di chuyển tệp đến thư mục uploads
            if (!move_uploaded_file($fileTmpName, $fileDestination)) {
                $_SESSION['error'] = "Đã xảy ra lỗi khi tải tệp lên.";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['success'] = "Tệp đã được tải lên thành công.";
            }
        }
    }

    // Lưu bài viết vào cơ sở dữ liệu sau khi tải tệp thành công hoặc không có tệp
    $stmt = $conn->prepare("INSERT INTO posts (content, description, file, username) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $content, $description, $newFileName, $_SESSION['username']);

    if ($stmt->execute()) {
        // Log hành động của người dùng
        logAction("Đăng bài mới của người dùng: {$_SESSION['username']}");

        // Đặt thời gian chờ (10 phút) trong session
        $_SESSION['post_cooldown'] = time() + 600; // 600 giây = 10 phút

        // Sau khi lưu bài viết thành công
        $_SESSION['success'] = "Bài viết đã được đăng thành công.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Có lỗi xảy ra khi lưu bài viết.";
        header("Location: index.php");
        exit();
    }
}

// Tạo CSRF token khi bắt đầu phiên làm việc
if (!isset($_SESSION['csrf_token2'])) {
    $_SESSION['csrf_token2'] = bin2hex(random_bytes(32));
}
?>
