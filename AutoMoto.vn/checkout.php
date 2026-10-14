<?php
session_start();
include "includes/db_connect.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php"); exit;
}

// Kiểm tra giỏ hàng trống
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php"); exit;
}

$user_id = $_SESSION['user_id'];
// Lấy thông tin user để tự điền vào form
$stmt = $conn->prepare("SELECT name, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Tính tổng tiền lại một lần nữa để hiển thị
$total_price = 0;
foreach ($_SESSION['cart'] as $id => $quantity) {
    $res = $conn->query("SELECT price FROM bikes WHERE id = " . (int)$id);
    if($row = $res->fetch_assoc()) {
        $total_price += $row['price'] * $quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán - AutoMoto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700;900&display=swap");
        :root { --primary-red: #e31e24; --dark-bg: #1a1a1a; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Roboto Condensed", sans-serif; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        
        /* Re-use Progress Steps from Cart */
        .progress-steps { display: flex; justify-content: center; align-items: center; margin-bottom: 50px; gap: 20px; }
        .step { display: flex; align-items: center; gap: 10px; font-weight: 700; color: #ccc; }
        .step.active { color: var(--primary-red); }
        .step-num { width: 30px; height: 30px; border: 2px solid #ccc; border-radius: 50%; display: grid; place-items: center; }
        .step.active .step-num { border-color: var(--primary-red); background: var(--primary-red); color: #fff; }
        .line { width: 50px; height: 2px; background: #eee; }

        .checkout-grid { display: grid; grid-template-columns: 1fr 450px; gap: 40px; }
        
        /* Form Styles */
        .checkout-form { background: #f9f9f9; padding: 30px; border-radius: 8px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 700; text-transform: uppercase; font-size: 13px; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; outline: none; font-size: 16px; }
        
        .payment-methods { margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
        .method-option { display: flex; align-items: center; padding: 15px; border: 1px solid #ddd; margin-bottom: 10px; cursor: pointer; background: #fff; }
        .method-option input { margin-right: 15px; width: 20px; height: 20px; accent-color: var(--primary-red); }

        .summary-box { background: var(--dark-bg); color: #fff; padding: 30px; border-radius: 4px; height: fit-content; }
        .total-row { display: flex; justify-content: space-between; font-size: 24px; font-weight: 900; color: var(--primary-red); border-top: 1px solid #444; padding-top: 20px; margin-top: 20px; }
        
        .btn-order { width: 100%; background: var(--primary-red); color: #fff; border: none; padding: 20px; font-weight: 900; text-transform: uppercase; cursor: pointer; margin-top: 20px; clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%); }
    </style>
</head>
<body>

<div class="container">
    <div class="progress-steps">
        <div class="step"><span class="step-num">1</span> GIỎ HÀNG</div>
        <div class="line"></div>
        <div class="step active"><span class="step-num">2</span> THANH TOÁN</div>
        <div class="line"></div>
        <div class="step"><span class="step-num">3</span> HOÀN TẤT</div>
    </div>

    <form action="process_checkout.php" method="POST" class="checkout-grid">
        <div class="checkout-form">
            <h2 style="margin-bottom: 25px;">THÔNG TIN GIAO HÀNG</h2>
            <div class="form-group">
                <label>Họ và tên người nhận</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
            </div>
            <div class="form-group">
                <label>Địa chỉ nhận xe</label>
                <textarea name="address" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
            </div>

            <div class="payment-methods">
                <label style="font-weight: 900; margin-bottom: 15px; display: block;">PHƯƠNG THỨC THANH TOÁN</label>
                <label class="method-option">
                    <input type="radio" name="payment_method" value="cod" checked>
                    <div><strong>Thanh toán khi nhận hàng (COD)</strong><br><small>Nhân viên sẽ liên hệ xác nhận đơn hàng</small></div>
                </label>
                <label class="method-option">
                    <input type="radio" name="payment_method" value="bank_transfer">
                    <div><strong>Chuyển khoản ngân hàng</strong><br><small>Thanh toán nhanh qua QR Code</small></div>
                </label>
            </div>
        </div>

        <aside class="summary-box">
            <h2 style="border-bottom: 1px solid #444; padding-bottom: 15px; margin-bottom: 20px;">TÓM TẮT ĐƠN HÀNG</h2>
            <!-- Hiển thị lại danh sách rút gọn nếu muốn -->
            <div style="font-size: 14px; color: #aaa;">
                <?php 
                foreach ($_SESSION['cart'] as $id => $qty) {
                    $res = $conn->query("SELECT name, price FROM bikes WHERE id = $id");
                    $b = $res->fetch_assoc();
                    echo "<div style='display:flex; justify-content:space-between; margin-bottom:10px;'>
                            <span>{$b['name']} x $qty</span>
                            <span>".number_format($b['price'] * $qty)."đ</span>
                          </div>";
                }
                ?>
            </div>
            <div class="total-row">
                <span>TỔNG CỘNG</span>
                <span><?= number_format($total_price, 0, ',', '.') ?>đ</span>
            </div>
            <input type="hidden" name="total_price" value="<?= $total_price ?>">
            <button type="submit" class="btn-order">ĐẶT HÀNG NGAY <i class="fas fa-check-circle" style="margin-left:10px;"></i></button>
        </aside>
    </form>
</div>

</body>
</html>