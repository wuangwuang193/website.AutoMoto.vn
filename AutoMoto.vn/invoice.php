<?php
session_start();
include "includes/db_connect.php"; // Đường dẫn này phải đúng từ thư mục gốc

if (!isset($_GET['order_id'])) { die("Thiếu mã đơn hàng."); }
$order_id = intval($_GET['order_id']);

// Kiểm tra quyền: Admin hoặc Chủ đơn hàng mới được xem
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$user_id = $_SESSION['user_id'] ?? 0;

$sql = "SELECT o.*, u.name, u.phone, u.address, p.payment_status 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        LEFT JOIN payments p ON o.id = p.order_id
        WHERE o.id = $order_id";

if (!$is_admin) {
    $sql .= " AND o.user_id = $user_id";
}

$result = $conn->query($sql);
$order = $result->fetch_assoc();

if (!$order) { die("Không tìm thấy đơn hàng hoặc bạn không có quyền xem."); }

$items_res = $conn->query("SELECT oi.*, b.name as bike_name FROM order_items oi 
                           JOIN bikes b ON oi.product_id = b.id 
                           WHERE oi.order_id = $order_id");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #<?= $order_id ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #eee; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; background: #fff; }
        table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        table td { padding: 10px; border: 1px solid #eee; }
        table th { background: #f9f9f9; padding: 10px; border: 1px solid #eee; }
        .total { font-weight: bold; font-size: 20px; text-align: right; margin-top: 20px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>HÓA ĐƠN BÁN HÀNG #<?= $order_id ?></h2>
        <p>Khách hàng: <?= htmlspecialchars($order['name']) ?> - <?= $order['phone'] ?></p>
        <p>Địa chỉ: <?= htmlspecialchars($order['address']) ?></p>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = $items_res->fetch_assoc()): ?>
                <tr>
                    <td><?= $item['bike_name'] ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price']) ?>đ</td>
                    <td><?= number_format($item['price'] * $item['quantity']) ?>đ</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="total">TỔNG CỘNG: <?= number_format($order['total_price']) ?>đ</div>
        <div class="no-print" style="margin-top:20px;">
            <button onclick="window.print()">IN HÓA ĐƠN</button>
        </div>
    </div>
</body>
</html>