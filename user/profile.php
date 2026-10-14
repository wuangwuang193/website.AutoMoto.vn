<?php
session_start();
include "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php"); exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $phone, $address, $user_id);
    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name; // Cập nhật lại session để hiển thị trên header
        $message = "Cập nhật thông tin thành công!";
    }
}

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản của tôi - AutoMoto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* CSS Dùng chung hoàn toàn với orders.php */
        @import url("https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap");

        :root {
            --primary-red: #d62828;
            --hover-red: #b91d1d;
            --accent-yellow: #ffcc00;
            --bg-light: #f4f7f6;
            --text-dark: #222;
            --shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Roboto Condensed", sans-serif; }
        body { background-color: var(--bg-light); color: var(--text-dark); }
        
        #header { background: var(--primary-red); padding: 0 60px; position: sticky; top: 0; z-index: 1000; height: 75px; display: flex; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header-container { display: flex; align-items: center; justify-content: space-between; width: 100%; }
        #navbar { display: flex; list-style: none; gap: 30px; }
        #navbar li a { text-decoration: none; color: #fff; font-weight: 700; font-size: 17px; transition: 0.3s; text-transform: uppercase; }
        #navbar li a.active, #navbar li a:hover { color: var(--accent-yellow); }
        .header-icons { display: flex; align-items: center; gap: 20px; color: #fff; }

        .section-p1 { padding: 50px 80px; }
        .container { max-width: 1250px; margin: 0 auto; display: grid; grid-template-columns: 280px 1fr; gap: 40px; }

        .user-nav { background: #fff; padding: 30px 20px; border-radius: 16px; box-shadow: var(--shadow); height: fit-content; }
        .user-nav h6 { color: #999; margin-bottom: 20px; text-transform: uppercase; font-size: 13px; letter-spacing: 1.5px; padding-left: 10px; }
        .user-nav a { display: flex; align-items: center; gap: 12px; padding: 14px 18px; text-decoration: none; color: #555; font-weight: 600; border-radius: 10px; margin-bottom: 8px; transition: 0.3s; }
        .user-nav a i { font-size: 18px; width: 25px; }
        .user-nav a.active { background: #fdecea; color: var(--primary-red); }
        .user-nav a:hover:not(.active) { background: #f8f9fa; color: var(--primary-red); }

        .main-card { background: #fff; padding: 40px; border-radius: 16px; box-shadow: var(--shadow); }
        h2 { font-size: 28px; margin-bottom: 30px; color: var(--text-dark); text-transform: uppercase; border-left: 6px solid var(--primary-red); padding-left: 20px; line-height: 1; }

        /* FORM STYLES */
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-weight: 700; margin-bottom: 10px; text-transform: uppercase; font-size: 13px; color: #666; }
        .form-group input, .form-group textarea { 
            width: 100%; padding: 14px 18px; border: 1.5px solid #eee; border-radius: 8px; 
            font-size: 16px; outline: none; transition: 0.3s; background: #fafafa;
        }
        .form-group input:focus, .form-group textarea:focus { border-color: var(--primary-red); background: #fff; box-shadow: 0 0 0 4px rgba(214, 40, 40, 0.05); }
        .form-group input:disabled { background: #f1f1f1; cursor: not-allowed; color: #999; }

        button.btn-save {
            font-size: 16px; font-weight: 700; padding: 16px 45px;
            color: #fff; background-color: var(--primary-red);
            border-radius: 8px; cursor: pointer; border: none; transition: 0.3s;
            text-transform: uppercase; letter-spacing: 1px;
        }
        button.btn-save:hover { background-color: var(--hover-red); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(214, 40, 40, 0.3); }
        
        .alert { background: #e8f5e9; color: #2e7d32; padding: 15px 20px; border-radius: 10px; margin-bottom: 25px; font-weight: 600; border-left: 5px solid #2e7d32; }
    </style>
</head>
<body>

<section id="header">
    <div class="header-container">
        <a href="../index.php"><img src="../img/logo.jpg" alt="Logo" style="height: 50px; border-radius: 5px;"></a>
        <ul id="navbar">
            <li><a href="../index.php">Trang chủ</a></li>
            <li><a href="../sanphamxe.php">Sản phẩm</a></li>
            <li><a href="orders.php">Đơn hàng</a></li>
            <li><a href="profile.php" class="active">Tài khoản</a></li>
            
        </ul>
        <div class="header-icons">
            <span class="user-name-tag"><i class="fa-regular fa-user"></i> <?= htmlspecialchars($user['name']) ?></span>
            <a href="../auth/logout.php" style="color: white;"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>
</section>

<section class="section-p1">
    <div class="container">
        <div class="user-nav">
            <h6>Bảng điều khiển</h6>
            <a href="profile.php" class="active"><i class="fas fa-user-circle"></i> Hồ sơ cá nhân</a>
            <a href="orders.php"><i class="fas fa-shopping-bag"></i> Lịch sử mua hàng</a>
            <a href="my_orders.php"><i class="fas fa-shopping-bag"></i> Đơn hàng của bạn</a>
            <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">
            <a href="../auth/logout.php" style="color: #d32f2f;"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>

        <div class="main-card">
            <h2>Cập nhật hồ sơ</h2>
            <?php if ($message): ?> <div class="alert"><i class="fas fa-check-circle"></i> <?= $message ?></div> <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Họ và tên khách hàng</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required placeholder="Nhập họ tên...">
                </div>
                <div class="form-group">
                    <label>Địa chỉ Email (Không thể thay đổi)</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Số điện thoại liên lạc</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" placeholder="Nhập số điện thoại...">
                </div>
                <div class="form-group">
                    <label>Địa chỉ nhận xe mặc định</label>
                    <textarea name="address" rows="4" placeholder="Nhập địa chỉ chi tiết..."><?= htmlspecialchars($user['address']) ?></textarea>
                </div>
                <button type="submit" class="btn-save">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</section>

</body>
</html>