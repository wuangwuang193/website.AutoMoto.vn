<?php
session_start();
include "includes/db_connect.php";

$order_id = intval($_GET['order_id']);
// Lấy thông tin đơn hàng để tạo QR
$res = $conn->query("SELECT total_price FROM orders WHERE id = $order_id");
$order = $res->fetch_assoc();

// Cấu hình ngân hàng của bạn
$bank_id = "VCB"; // Mã ngân hàng (Vietcombank, MB, ...)
$account_no = "1234567890"; // Số tài khoản admin
$account_name = "CONG TY AUTOMOTO";
$amount = $order['total_price'];
$description = "AUTOMOTO" . $order_id;

// Link API tạo VietQR
$qr_url = "https://img.vietqr.io/image/$bank_id-$account_no-compact2.png?amount=$amount&addInfo=$description&accountName=$account_name";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán chuyển khoản</title>
    <style>
        body { font-family: 'Roboto Condensed', sans-serif; background: #f4f4f4; text-align: center; padding: 50px; }
        .payment-card { background: #fff; max-width: 500px; margin: 0 auto; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .qr-code { width: 250px; margin: 20px 0; border: 1px solid #eee; }
        .bank-info { text-align: left; background: #f9f9f9; padding: 15px; border-left: 5px solid #e31e24; margin-bottom: 20px; }
        .btn-upload { background: #e31e24; color: #fff; border: none; padding: 12px 25px; cursor: pointer; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="payment-card">
        <h2>THANH TOÁN CHUYỂN KHOẢN</h2>
        <p>Vui lòng quét mã QR dưới đây để thanh toán:</p>
        
        <img src="<?= $qr_url ?>" alt="VietQR" class="qr-code">

        <div class="bank-info">
            <p><strong>Ngân hàng:</strong> <?= $bank_id ?></p>
            <p><strong>Số tài khoản:</strong> <?= $account_no ?></p>
            <p><strong>Chủ tài khoản:</strong> <?= $account_name ?></p>
            <p><strong>Số tiền:</strong> <span style="color:red; font-weight:bold;"><?= number_format($amount) ?>đ</span></p>
            <p><strong>Nội dung:</strong> <?= $description ?></p>
        </div>

        <form action="upload_receipt.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <p>Sau khi chuyển khoản, vui lòng tải ảnh biên lai lên:</p>
            <input type="file" name="receipt" required style="margin-bottom: 15px;">
            <br>
            <button type="submit" class="btn-upload">GỬI BIÊN LAI XÁC NHẬN</button>
        </form>
    </div>
</body>
</html>