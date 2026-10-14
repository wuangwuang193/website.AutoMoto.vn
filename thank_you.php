<?php
session_start();
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 'N/A';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công - AutoMoto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700;900&display=swap");
        body { font-family: "Roboto Condensed", sans-serif; text-align: center; padding: 100px 20px; background: #f4f4f4; }
        .success-card { background: white; max-width: 600px; margin: 0 auto; padding: 50px; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .icon { font-size: 80px; color: #28a745; margin-bottom: 20px; }
        h1 { font-size: 32px; color: #1a1a1a; margin-bottom: 10px; }
        p { color: #666; margin-bottom: 30px; }
        .order-id { font-weight: bold; color: #e31e24; font-size: 20px; }
        .btn-home { background: #e31e24; color: white; padding: 15px 30px; text-decoration: none; font-weight: bold; text-transform: uppercase; border-radius: 4px; display: inline-block; }
    </style>
</head>
<body>

<div class="success-card">
    <div class="icon"><i class="fas fa-check-circle"></i></div>
    <h1>ĐẶT HÀNG THÀNH CÔNG!</h1>
    <p>Cảm ơn bạn đã tin tưởng AutoMoto. Mã đơn hàng của bạn là: <span class="order-id">#<?= $order_id ?></span></p>
    <p>Chúng tôi sẽ sớm liên hệ với bạn qua số điện thoại để xác nhận lịch giao xe.</p>
    
    <a href="index.php" class="btn-home">Quay lại trang chủ</a>
    <br><br>
    <a href="user/orders.php" style="color: #888; text-decoration: none;">Xem lịch sử đơn hàng</a>
</div>

</body>
</html>