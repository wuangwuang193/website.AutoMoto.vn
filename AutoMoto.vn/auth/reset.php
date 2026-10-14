<?php
require_once "../config/config.php";
$error = "";
$success = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Kiểm tra token có hợp lệ và chưa hết hạn không
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        die("Yêu cầu không hợp lệ hoặc link đã hết hạn!");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (strlen($password) < 6) {
            $error = "Mật khẩu phải từ 6 ký tự!";
        } elseif ($password !== $confirm_password) {
            $error = "Mật khẩu xác nhận không khớp!";
        } else {
            // Hash mật khẩu mới
            $new_password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // Cập nhật mật khẩu và xóa token
            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
            $stmt->execute([$new_password_hashed, $user['id']]);

            $success = "Đổi mật khẩu thành công! <a href='login.php'>Đăng nhập ngay</a>";
        }
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="auth-container">
        <form action="" method="POST" class="auth-form">
            <h2>MẬT KHẨU MỚI</h2>
            <?php 
                if($error) echo "<p class='msg error'>$error</p>"; 
                if($success) echo "<p class='msg success'>$success</p>"; 
            ?>
            <input type="password" name="password" placeholder="Mật khẩu mới" required>
            <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu mới" required>
            <button type="submit">Cập nhật mật khẩu</button>
        </form>
    </div>
</body>
</html>