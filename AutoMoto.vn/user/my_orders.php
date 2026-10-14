<?php
session_start();
include "../includes/db_connect.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php"); exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Xử lý Hủy đơn (Chỉ cho phép khi trạng thái là 'pending')
if (isset($_POST['cancel_id'])) {
    $id = intval($_POST['cancel_id']);
    $update_sql = "UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'pending'";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $id, $user_id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $message = "Đã hủy đơn hàng #$id thành công!";
    } else {
        $message = "Không thể hủy đơn hàng này.";
    }
}

// Truy vấn danh sách đơn hàng kèm trạng thái thanh toán và tên xe
$sql = "SELECT o.*, p.payment_status, GROUP_CONCAT(b.name SEPARATOR ', ') as ds_ten_xe 
        FROM orders o 
        LEFT JOIN payments p ON o.id = p.order_id 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        LEFT JOIN bikes b ON oi.product_id = b.id 
        WHERE o.user_id = ? 
        GROUP BY o.id 
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của tôi - AutoMoto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
        
        /* HEADER (Giữ nguyên phong cách của bạn) */
        #header { background: var(--primary-red); padding: 0 60px; position: sticky; top: 0; z-index: 1000; height: 75px; display: flex; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header-container { display: flex; align-items: center; justify-content: space-between; width: 100%; }
        #navbar { display: flex; list-style: none; gap: 30px; }
        #navbar li a { text-decoration: none; color: #fff; font-weight: 700; font-size: 17px; transition: 0.3s; text-transform: uppercase; }
        #navbar li a.active, #navbar li a:hover { color: var(--accent-yellow); }
        .header-icons { display: flex; align-items: center; gap: 20px; color: #fff; }

        /* LAYOUT CHÍNH (Sidebar + Content) */
        .section-p1 { padding: 50px 80px; }
        .container { max-width: 1250px; margin: 0 auto; display: grid; grid-template-columns: 280px 1fr; gap: 40px; }

        /* SIDEBAR (BẢNG ĐIỀU KHIỂN - Y chang Profile) */
        .user-nav { background: #fff; padding: 30px 20px; border-radius: 16px; box-shadow: var(--shadow); height: fit-content; }
        .user-nav h6 { color: #999; margin-bottom: 20px; text-transform: uppercase; font-size: 13px; letter-spacing: 1.5px; padding-left: 10px; }
        .user-nav a { display: flex; align-items: center; gap: 12px; padding: 14px 18px; text-decoration: none; color: #555; font-weight: 600; border-radius: 10px; margin-bottom: 8px; transition: 0.3s; }
        .user-nav a i { font-size: 18px; width: 25px; }
        .user-nav a.active { background: #fdecea; color: var(--primary-red); }
        .user-nav a:hover:not(.active) { background: #f8f9fa; color: var(--primary-red); }

        /* CONTENT CARD (Khối nội dung bên phải) */
        .main-card { background: #fff; padding: 40px; border-radius: 16px; box-shadow: var(--shadow); }
        h2 { font-size: 28px; margin-bottom: 30px; color: var(--text-dark); text-transform: uppercase; border-left: 6px solid var(--primary-red); padding-left: 20px; line-height: 1; }

        /* TABLE (Bảng danh sách đơn hàng) */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th { background: #fcfcfc; padding: 18px 15px; text-align: left; font-weight: 700; text-transform: uppercase; font-size: 13px; color: #888; border-bottom: 2px solid #eee; }
        table td { padding: 22px 15px; border-bottom: 1px solid #f1f1f1; font-size: 15px; vertical-align: middle; }

        /* TRẠNG THÁI (Badges) */
        .status { padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 11px; display: inline-block; }
        .status.pending { background: #fff3e0; color: #ef6c00; }
        .status.shipping { background: #e3f2fd; color: #1565c0; }
        .status.completed { background: #e8f5e9; color: #2e7d32; }
        .status.cancelled { background: #ffebee; color: #c62828; }

        /* THANH TOÁN */
        .pay-status { font-weight: 700; font-size: 14px; }
        .paid { color: #2e7d32; }
        .unpaid { color: #d62828; }

        .price { font-weight: 700; color: var(--primary-red); font-size: 17px; }
        
        /* NÚT BẤM */
        .btn-invoice { background: #222; color: #fff; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-weight: 700; font-size: 12px; transition: 0.3s; display: inline-flex; align-items: center; gap: 5px; }
        .btn-invoice:hover { background: var(--primary-red); }
        
        .btn-cancel { background: transparent; border: 1.5px solid #d32f2f; color: #d32f2f; padding: 7px 15px; border-radius: 6px; cursor: pointer; font-weight: 700; transition: 0.3s; font-family: inherit; font-size: 12px; }
        .btn-cancel:hover { background: #d32f2f; color: #fff; }
        
        .alert { background: #e8f5e9; color: #2e7d32; padding: 15px 20px; border-radius: 10px; margin-bottom: 25px; font-weight: 600; border-left: 5px solid #2e7d32; display: flex; align-items: center; gap: 10px; }
    </style>
</head>
<body>

<section id="header">
    <div class="header-container">
        <a href="../index.php"><img src="../img/logo.jpg" alt="Logo" style="height: 50px; border-radius: 5px;"></a>
        <ul id="navbar">
            <li><a href="../index.php">Trang chủ</a></li>
            <li><a href="../sanphamxe.php">Sản phẩm</a></li>
            <li><a href="orders.php" class="active">Đơn hàng</a></li>
            <li><a href="profile.php">Tài khoản</a></li>
            
        </ul>
        <div class="header-icons">
            <span><i class="fa-regular fa-user"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? 'Khách') ?></span>
            <a href="../auth/logout.php" style="color: white; margin-left:15px;"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>
</section>

<section class="section-p1">
    <div class="container">
        <!-- BẢNG ĐIỀU KHIỂN (SIDEBAR) -->
        <div class="user-nav">
            <h6>Bảng điều khiển</h6>
            <a href="profile.php"><i class="fas fa-user-circle"></i> Hồ sơ cá nhân</a>
            <a href="orders.php" ><i class="fas fa-shopping-bag"></i> Lịch sử mua hàng</a>
            <a href="my_orders.php" class="active"><i class="fas fa-shopping-bag"></i> Đơn hàng của bạn</a>
            <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">
            <a href="../auth/logout.php" style="color: #d32f2f;"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>

        <!-- NỘI DUNG ĐƠN HÀNG -->
        <div class="main-card">
            <h2>Đơn hàng của tôi</h2>
            
            <?php if ($message): ?>
                <div class="alert"><i class="fas fa-check-circle"></i> <?= $message ?></div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="6" style="text-align:center; padding:80px; color: #bbb;">Bạn chưa có đơn hàng nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><strong>#<?= $o['id'] ?></strong><br><small style="color:#999"><?= date('d/m/y', strtotime($o['created_at'])) ?></small></td>
                            <td style="max-width: 250px; font-weight: 600; line-height: 1.4;">
                                <?= !empty($o['ds_ten_xe']) ? htmlspecialchars($o['ds_ten_xe']) : 'Sản phẩm đã xóa' ?>
                            </td>
                            <td class="price"><?= number_format($o['total_price'], 0, ',', '.') ?>đ</td>
                            <td>
                                <?php if($o['payment_status'] == 'paid'): ?>
                                    <span class="pay-status paid">✅ Đã thu tiền</span>
                                <?php else: ?>
                                    <span class="pay-status unpaid">❌ Chưa trả</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status <?= $o['status'] ?>">
                                    <?php 
                                        switch($o['status']) {
                                            case 'pending': echo "CHỜ DUYỆT"; break;
                                            case 'shipping': echo "ĐANG GIAO"; break;
                                            case 'completed': echo "HOÀN TẤT"; break;
                                            case 'cancelled': echo "ĐÃ HỦY"; break;
                                            default: echo strtoupper($o['status']);
                                        }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <a href="../invoice.php?order_id=<?= $o['id'] ?>" class="btn-invoice">
                                        <i class="fas fa-file-invoice"></i> BILL
                                    </a>

                                    <?php if($o['status'] == 'pending'): ?>
                                        <form method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                            <input type="hidden" name="cancel_id" value="<?= $o['id'] ?>">
                                            <button type="submit" class="btn-cancel">HỦY</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

</body>
</html>