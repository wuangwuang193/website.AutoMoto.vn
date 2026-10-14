<?php
require_once '../config/config.php';

$errors = [];
$success = false;
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $s_question = $_POST['security_question'] ?? '';
    $s_answer   = trim($_POST['security_answer'] ?? '');

    $form_data = $_POST;

    // Validate
    if (empty($name) || empty($email) || empty($password) || empty($s_answer)) {
        $errors[] = "Vui lòng điền đầy đủ các trường bắt buộc (*).";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Email này đã được sử dụng.";
        }
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, password, phone, address, role, security_question, security_answer)
                VALUES (?, ?, ?, ?, ?, 'customer', ?, ?)
            ");
            $stmt->execute([
                $name, $email, $hashed_password, $phone ?: null, $address ?: null, $s_question, $s_answer
            ]);
            $success = true;
            $form_data = [];
        } catch (PDOException $e) {
            $errors[] = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký - Câu hỏi bảo mật</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-form">
        <h2>ĐĂNG KÝ TÀI KHOẢN</h2>

        <?php if ($success): ?>
            <div class="msg success">Đăng ký thành công! <a href="login.php">Đăng nhập ngay</a></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="msg error"><?php foreach($errors as $err) echo "<div>$err</div>"; ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Họ và tên *" value="<?= htmlspecialchars($form_data['name'] ?? '') ?>" required>
            <input type="email" name="email" placeholder="Email *" value="<?= htmlspecialchars($form_data['email'] ?? '') ?>" required>
            <input type="password" name="password" placeholder="Mật khẩu *" required>
            
            <p style="text-align: left; margin-top: 10px; font-size: 14px; color: #666;">Thiết lập câu hỏi bảo mật để lấy lại mật khẩu:</p>
            <select name="security_question" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
                <option value="Tên con vật đầu tiên của bạn?">Tên con vật đầu tiên của bạn?</option>
                <option value="Tên trường tiểu học của bạn?">Tên trường tiểu học của bạn?</option>
                <option value="Tên mẹ của bạn là gì?">Tên mẹ của bạn là gì?</option>
                <option value="Món ăn yêu thích của bạn?">Món ăn yêu thích của bạn?</option>
            </select>
            <input type="text" name="security_answer" placeholder="Câu trả lời của bạn *" required>

            <input type="tel" name="phone" placeholder="Số điện thoại" value="<?= htmlspecialchars($form_data['phone'] ?? '') ?>">
            <textarea name="address" placeholder="Địa chỉ"><?= htmlspecialchars($form_data['address'] ?? '') ?></textarea>

            <button type="submit">ĐĂNG KÝ</button>
        </form>
        <?php endif; ?>
        
    </div>
</div>
</body>
</html>