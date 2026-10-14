<?php
include "includes/db_connect.php";
$order_id = intval($_GET['order_id']);
$res = $conn->query("SELECT total_price FROM orders WHERE id = $order_id");
$order = $res->fetch_assoc();

// Cấu hình VietQR
$bank_id = "VCB"; 
$acc_no = "9901019598";
$acc_name = "CONG TY AUTOMOTO";
$amount = $order['total_price'];
$content = "AUTOMOTO" . $order_id;
$qr_url = "https://img.vietqr.io/image/$bank_id-$acc_no-compact2.png?amount=$amount&addInfo=$content&accountName=$acc_name";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin thanh toán</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 40px; background: #f4f4f4; }
        .container { background: white; max-width: 500px; margin: auto; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .qr-img { width: 250px; margin: 20px 0; border: 1px solid #ddd; }
        .alert-info { background: #e7f3ff; padding: 15px; border-radius: 8px; color: #0c5460; font-size: 14px; margin-top: 20px; }
        .btn-home { display: inline-block; margin-top: 25px; padding: 10px 20px; background: #e31e24; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color: #e31e24;">ĐẶT HÀNG THÀNH CÔNG!</h2>
        <p>Mã đơn hàng: <strong>#<?= $order_id ?></strong></p>
        <p>Vui lòng chuyển khoản để hoàn tất đơn hàng:</p>
        
        <img src="<?= $qr_url ?>" class="qr-img">

        <div style="text-align: left; background: #f9f9f9; padding: 15px; line-height: 1.8;">
            <div>Số tiền: <strong style="color:red"><?= number_format($amount) ?>đ</strong></div>
            <div>Nội dung: <strong><?= $content ?></strong></div>
        </div>

        <div class="alert-info">
            <i class="fas fa-info-circle"></i> Hệ thống sẽ tự động xác nhận sau khi nhận được tiền (thường từ 5-10 phút). Bạn có thể đóng trang này.
        </div>

        <a href="index.php" class="btn-home">QUAY LẠI TRANG CHỦ</a>
    </div>
</body>
</html>