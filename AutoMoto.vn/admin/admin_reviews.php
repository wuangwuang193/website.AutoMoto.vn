<?php
session_start();
include "../includes/db_connect.php"; 

// 1. KIỂM TRA QUYỀN ADMIN (Bật lại khi chạy thật)
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ../auth/login.php"); exit;
// }

$message = '';
$message_type = 'success';

// 2. XỬ LÝ XÓA ĐÁNH GIÁ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $review_id = intval($_POST['id']);
    try {
        $sql = "DELETE FROM reviews WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $review_id);
        $stmt->execute();
        $message = "Đã xóa đánh giá vi phạm thành công!";
    } catch (Exception $e) {
        $message = "Lỗi khi xóa: " . $e->getMessage();
        $message_type = 'error';
    }
}

// 3. LẤY DANH SÁCH ĐÁNH GIÁ (Sửa lại JOIN đúng tên cột trong CSDL)
$sql = "SELECT r.*, u.name as user_name, u.email as user_email, b.name as bike_name 
        FROM reviews r 
        LEFT JOIN users u ON r.user_id = u.id 
        LEFT JOIN bikes b ON r.product_id = b.id  /* Thay bike_id thành product_id theo ảnh CSDL */
        ORDER BY r.created_at DESC";

$result = $conn->query($sql);

if (!$result) {
    // Nếu vẫn lỗi, hãy bỏ comment dòng dưới để xem lỗi chính xác là gì:
    // die("Lỗi SQL: " . $conn->error); 
    $reviews = [];
} else {
    $reviews = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Quản lý Đánh giá</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">

</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">AUTOMOTO ADMIN</div>
    <a href="index_admin.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="admin_bikes.php"><i class="fas fa-motorcycle"></i> Sản phẩm</a>
    <a href="admin_users.php"><i class="fas fa-users"></i> Người dùng</a> 
    <a href="order.php"><i class="fas fa-shopping-cart"></i> Đơn hàng</a>
    <a href="admin_reviews.php" class="active"><i class="fas fa-star"></i>Đánh giá</a>
    <a href="../auth/logout.php" style="margin-top: auto; color: var(--primary);"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
</div>

<div class="main">
    <div class="header-action">
        <h2>Quản lý Đánh giá</h2>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th style="width: 200px;">Khách hàng</th>
                    <th style="width: 200px;">Xe</th>
                    <th>Nội dung đánh giá</th>
                    <th style="width: 150px;">Thời gian</th>
                    <th style="width: 50px;">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr><td colspan="5" style="text-align:center; padding:40px; color:#999;">Chưa có đánh giá nào.</td></tr>
                <?php else: ?>
                    <?php foreach ($reviews as $r): ?>
                    <tr>
                        <td>
                            <span class="user-info"><?= htmlspecialchars($r['user_name'] ?? 'Ẩn danh') ?></span>
                            <span class="user-email"><?= htmlspecialchars($r['user_email'] ?? '') ?></span>
                        </td>
                        <td>
                            <span class="bike-tag"><?= htmlspecialchars($r['bike_name'] ?? 'N/A') ?></span>
                            <div class="star-rating">
                                <?php for($i=1; $i<=5; $i++) echo ($i <= $r['rating'] ? '★' : '☆'); ?>
                            </div>
                        </td>
                        <td>
                            <div class="comment-text">"<?= htmlspecialchars($r['comment']) ?>"</div>
                        </td>
                        <td style="color: #888; font-size: 13px;">
                            <?= date('d/m/Y H:i', strtotime($r['created_at'])) ?>
                        </td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Xóa đánh giá này?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <button type="submit" class="btn-delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>