<?php
require_once '../config/config.php';
$error = "";
$success = "";
$user_found = null;

// Bước 1: Kiểm tra email
if (isset($_POST['check_email'])) {
    $email = trim($_POST['email']);
    $stmt = $pdo->prepare("SELECT email, security_question FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user_found = $stmt->fetch();
    if (!$user_found) {
        $error = "Email không tồn tại trong hệ thống!";
    }
}

// Bước 2: Kiểm tra câu trả lời và đổi mật khẩu
if (isset($_POST['reset_password'])) {
    $email = $_POST['email_hidden'];
    $answer = trim($_POST['security_answer']);
    $new_pass = $_POST['new_password'];

    $stmt = $pdo->prepare("SELECT id, security_answer FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $answer === $user['security_answer']) {
        $new_hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->execute([$new_hashed, $user['id']]);
        $success = "Mật khẩu đã được cập nhật! <a href='login.php'>Đăng nhập ngay</a>";
    } else {
        $error = "Câu trả lời bảo mật không chính xác!";
        // Giữ lại trạng thái để người dùng trả lời lại
        $user_found = ['email' => $email, 'security_question' => $_POST['question_hidden']];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-form">
        <h2>LẤY LẠI MẬT KHẨU</h2>

        <?php if($error) echo "<p class='msg error'>$error</p>"; ?>
        <?php if($success) echo "<p class='msg success'>$success</p>"; ?>

        <?php if (!$user_found && !$success): ?>
            <!-- Form nhập Email -->
            <form method="POST">
                <p>Nhập email tài khoản của bạn:</p>
                <input type="email" name="email" placeholder="Email của bạn" required>
                <button type="submit" name="check_email">Tiếp tục</button>
            </form>
        <?php elseif ($user_found && !$success): ?>
            <!-- Form trả lời câu hỏi -->
            <form method="POST">
                <input type="hidden" name="email_hidden" value="<?= $user_found['email'] ?>">
                <input type="hidden" name="question_hidden" value="<?= $user_found['security_question'] ?>">
                
                <p>Câu hỏi bảo mật của bạn:</p>
                <p><strong><?= htmlspecialchars($user_found['security_question']) ?></strong></p>
                
                <input type="text" name="security_answer" placeholder="Nhập câu trả lời" required>
                <input type="password" name="new_password" placeholder="Mật khẩu mới (ít nhất 6 ký tự)" required minlength="6">
                
                <button type="submit" name="reset_password">Đổi mật khẩu</button>
            </form>
        <?php endif; ?>

        <p><a href="login.php">Quay lại đăng nhập</a></p>
    </div>
</div>
</body>
</html>