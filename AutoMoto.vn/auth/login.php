<?php
// Đảm bảo đã có session_start() ở đầu file (thường nằm trong config.php hoặc gọi ở đây)
session_start(); 
require_once "../config/config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } else {

        // Truy vấn lấy cả cột status
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // 1. Kiểm tra mật khẩu
            if (password_verify($password, $user['password'])) {

                // 2. KIỂM TRA TRẠNG THÁI (STATUS)
                // Nếu trạng thái là 'locked', từ chối đăng nhập
                if (isset($user['status']) && $user['status'] === 'locked') {
                    $error = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên!";
                } else {
                    // Nếu không bị khóa, tiến hành lưu session
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['role']      = $user['role'];

                    // Điều hướng theo role
                    if ($user['role'] === 'admin') {
                        header("Location: ../admin/index_admin.php");
                    } else {
                        header("Location: ../user/profile.php");
                    }
                    exit();
                }

            } else {
                $error = "Mật khẩu không chính xác!";
            }

        } else {
            $error = "Email không tồn tại!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="auth-container">
        <form action="" method="POST" class="auth-form">
            <h2>ĐĂNG NHẬP</h2>
            <?php 
                if(isset($_GET['register'])) echo "<p class='msg success'>Đăng ký thành công! Hãy đăng nhập.</p>";
                if($error) echo "<p class='msg error' style='color: red; background: #fee; padding: 10px; border-radius: 5px; margin-bottom: 10px;'>$error</p>"; 
            ?>
            
            <input type="email" name="email" placeholder="Email của bạn" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            
            <button type="submit">Đăng nhập</button>
            <p>Chưa có tài khoản? 
                <a href="register.php">Đăng Ký</a>
            </p>
            <a href="forgot_password.php">Quên mật khẩu</a>
        </form>
    </div>
</body>
</html>