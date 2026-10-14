<?php
session_start();
include "../includes/db_connect.php"; 

// 1. KIỂM TRA QUYỀN (Nếu cần)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: ../auth/login.php"); exit; }

$message = '';

// 2. XỬ LÝ CẬP NHẬT (GIỮ NGUYÊN LOGIC CỦA BẠN)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);

    if (isset($_POST['update_order_status'])) {
        $new_status = $_POST['status'];
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        $message = "Đã cập nhật đơn hàng #$order_id";
    }

    if (isset($_POST['update_payment_status'])) {
        $pay_status = $_POST['pay_status'];
        $trans_code = $_POST['transaction_code'] ?? '';
        $stmt = $conn->prepare("UPDATE payments SET payment_status = ?, transaction_code = ? WHERE order_id = ?");
        $stmt->bind_param("ssi", $pay_status, $trans_code, $order_id);
        $stmt->execute();
        
        if ($pay_status === 'paid') {
            $conn->query("UPDATE orders SET status = 'confirmed' WHERE id = $order_id AND status = 'pending'");
        }
        $message = "Đã cập nhật thanh toán đơn #$order_id";
    }
}

// 3. TRUY VẤN DỮ LIỆU
$sql = "SELECT o.*, u.name as customer_name, u.phone as customer_phone, p.payment_status, p.transaction_code,
        GROUP_CONCAT(CONCAT(b.name, ' (x', oi.quantity, ')') SEPARATOR '<br>') as ds_ten_xe
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id 
        LEFT JOIN payments p ON o.id = p.order_id 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        LEFT JOIN bikes b ON oi.product_id = b.id 
        GROUP BY o.id ORDER BY o.created_at DESC";

$result = $conn->query($sql);
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - AutoMoto</title>
    <!-- LINK TƯƠNG TỰ ADMIN INDEX -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">AUTOMOTO ADMIN</div>
    <a href="index_admin.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="admin_bikes.php"><i class="fas fa-motorcycle"></i> Sản phẩm</a>
    <a href="admin_users.php"><i class="fas fa-users"></i> Người dùng</a> 
    <a href="order.php" class="active"><i class="fas fa-shopping-cart"></i> Đơn hàng</a>
    <a href="admin_reviews.php"><i class="fas fa-star"></i> Đánh giá</a>
    <a href="../auth/logout.php" style="margin-top: auto; color: var(--primary);">
        <i class="fas fa-sign-out-alt"></i> Đăng xuất
    </a>
</div>

<div class="main">
    <!-- HEADER GIỐNG INDEX -->
    <div class="admin-header">
        <div class="left">
            <div class="logo-box">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Quản lý đơn hàng</span>
            </div>
        </div>
        <div class="right">
            <div class="notify">
                <i class="fa-regular fa-bell"></i>
                <span class="badge">3</span>
            </div>
            <div class="profile">
                <img src="https://i.pravatar.cc/40" alt="admin">
                <span>Quang Nhật</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
    </div>

    <!-- NỘI DUNG CHÍNH -->
    <div style="padding: 20px;">
        <?php if ($message): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <i class="fas fa-check"></i> <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="card" style="padding: 0; overflow-x: auto;">
            <table width="100%" style="border-collapse: collapse; min-width: 1000px;">
                <thead>
                    <tr style="background: #f8f9fa; text-align: left; border-bottom: 2px solid #eee;">
                        <th style="padding: 15px;">Mã đơn</th>
                        <th style="padding: 15px;">Khách hàng</th>
                        <th style="padding: 15px;">Sản phẩm</th>
                        <th style="padding: 15px;">Tổng tiền</th>
                        <th style="padding: 15px;">Thanh toán</th>
                        <th style="padding: 15px;">Trạng thái</th>
                        <th style="padding: 15px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;">
                            <strong>#<?= $o['id'] ?></strong><br>
                            <small style="font-size: 10px; background: #eee; padding: 2px 5px; border-radius: 3px;">
                                <?= $o['payment_method'] == 'bank_transfer' ? '🏦 BANK' : '🚚 COD' ?>
                            </small>
                        </td>
                        <td style="padding: 15px;">
                            <?= htmlspecialchars($o['customer_name'] ?? 'Ẩn danh') ?><br>
                            <small style="color: #888; font-size: 12px;"><?= $o['customer_phone'] ?></small>
                        </td>
                        <td style="padding: 15px; font-size: 13px;"><?= $o['ds_ten_xe'] ?></td>
                        <td style="padding: 15px; color: #e10600; font-weight: bold;"><?= number_format($o['total_price']) ?>đ</td>
                        
                        <td style="padding: 15px;">
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                <select name="pay_status" style="width: 100%; padding: 5px; margin-bottom: 5px; border-radius: 4px; border: 1px solid #ddd;">
                                    <option value="unpaid" <?= $o['payment_status'] == 'unpaid' ? 'selected' : '' ?>>Chưa thu</option>
                                    <option value="paid" <?= $o['payment_status'] == 'paid' ? 'selected' : '' ?>>Đã nhận</option>
                                </select>
                                <input type="text" name="transaction_code" placeholder="Mã GD..." value="<?= htmlspecialchars($o['transaction_code'] ?? '') ?>" 
                                       style="width: 100%; font-size: 11px; padding: 5px; border: 1px solid #ddd; margin-bottom: 5px; border-radius: 4px;">
                                <button type="submit" name="update_payment_status" style="width: 100%; background: #28a745; color: white; border: none; padding: 5px; border-radius: 4px; cursor: pointer; font-size: 11px;">Cập nhật</button>
                            </form>
                        </td>

                        <td style="padding: 15px;">
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                <select name="status" onchange="this.form.submit()" style="padding: 8px; border-radius: 4px; border: 1px solid #ddd; width: 100%;">
                                    <option value="pending" <?= $o['status'] == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                    <option value="confirmed" <?= $o['status'] == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                                    <option value="shipping" <?= $o['status'] == 'shipping' ? 'selected' : '' ?>>Đang giao</option>
                                    <option value="completed" <?= $o['status'] == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                    <option value="cancelled" <?= $o['status'] == 'cancelled' ? 'selected' : '' ?>>Hủy</option>
                                </select>
                                <input type="hidden" name="update_order_status" value="1">
                            </form>
                        </td>

                        <td style="padding: 15px;">
                            <a href="../invoice.php?order_id=<?= $o['id'] ?>" target="_blank" 
                               style="display: block; text-align: center; background: #333; color: white; text-decoration: none; padding: 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                <i class="fas fa-file-invoice"></i> IN BILL
                            </a>
                            <div style="font-size: 10px; color: #999; margin-top: 8px; text-align: center;">
                                <?= date('d/m/Y H:i', strtotime($o['created_at'])) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>