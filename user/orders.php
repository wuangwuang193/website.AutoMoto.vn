<?php
session_start();
include "../includes/db_connect.php";

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php"); exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = 'success';

// 2. XỬ LÝ HỦY ĐƠN HÀNG
if (isset($_POST['cancel_id'])) {
    $id = intval($_POST['cancel_id']);
    $update_sql = "UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'pending'";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $id, $user_id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $message = "Đã hủy đơn hàng #$id thành công!";
        $message_type = 'success';
    } else {
        $message = "Không thể hủy đơn hàng này.";
        $message_type = 'error';
    }
}

// 3. XỬ LÝ GỬI ĐÁNH GIÁ (OVERLAY)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $p_id = intval($_POST['product_id']);
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    if ($rating > 0 && $p_id > 0) {
        $sql_review = "INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt_rev = $conn->prepare($sql_review);
        $stmt_rev->bind_param("iiis", $p_id, $user_id, $rating, $comment);
        
        if ($stmt_rev->execute()) {
            $message = "Cảm ơn bạn đã đánh giá sản phẩm!";
            $message_type = 'success';
        } else {
            $message = "Lỗi CSDL: " . $stmt_rev->error;
            $message_type = 'error';
        }
    } else {
        $message = "Vui lòng chọn số sao đánh giá!";
        $message_type = 'error';
    }
}

// 4. TRUY VẤN DANH SÁCH ĐƠN HÀNG (KÈM THANH TOÁN & ID ĐỂ ĐÁNH GIÁ)
$sql = "SELECT o.*, 
               p.payment_status,
               GROUP_CONCAT(b.name SEPARATOR ', ') as ds_ten_xe, 
               (SELECT product_id FROM order_items WHERE order_id = o.id LIMIT 1) as id_xe_de_danh_gia 
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
        
        /* HEADER */
        #header { background: var(--primary-red); padding: 0 60px; position: sticky; top: 0; z-index: 1000; height: 75px; display: flex; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header-container { display: flex; align-items: center; justify-content: space-between; width: 100%; }
        #navbar { display: flex; list-style: none; gap: 30px; }
        #navbar li a { text-decoration: none; color: #fff; font-weight: 700; font-size: 17px; transition: 0.3s; text-transform: uppercase; }
        #navbar li a.active, #navbar li a:hover { color: var(--accent-yellow); }
        .header-icons { display: flex; align-items: center; gap: 20px; color: #fff; }

        /* LAYOUT CHÍNH */
        .section-p1 { padding: 50px 80px; }
        .container { max-width: 1250px; margin: 0 auto; display: grid; grid-template-columns: 280px 1fr; gap: 40px; }

        /* SIDEBAR */
        .user-nav { background: #fff; padding: 30px 20px; border-radius: 16px; box-shadow: var(--shadow); height: fit-content; }
        .user-nav h6 { color: #999; margin-bottom: 20px; text-transform: uppercase; font-size: 13px; letter-spacing: 1.5px; padding-left: 10px; }
        .user-nav a { display: flex; align-items: center; gap: 12px; padding: 14px 18px; text-decoration: none; color: #555; font-weight: 600; border-radius: 10px; margin-bottom: 8px; transition: 0.3s; }
        .user-nav a i { font-size: 18px; width: 25px; }
        .user-nav a.active { background: #fdecea; color: var(--primary-red); }
        .user-nav a:hover:not(.active) { background: #f8f9fa; color: var(--primary-red); }

        /* CONTENT CARD */
        .main-card { background: #fff; padding: 40px; border-radius: 16px; box-shadow: var(--shadow); }
        h2 { font-size: 28px; margin-bottom: 30px; color: var(--text-dark); text-transform: uppercase; border-left: 6px solid var(--primary-red); padding-left: 20px; line-height: 1; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th { background: #fcfcfc; padding: 18px 15px; text-align: left; font-weight: 700; text-transform: uppercase; font-size: 13px; color: #888; border-bottom: 2px solid #eee; }
        table td { padding: 22px 15px; border-bottom: 1px solid #f1f1f1; font-size: 15px; vertical-align: middle; }

        /* STATUS BADGES */
        .status { padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 11px; display: inline-block; }
        .status.pending { background: #fff3e0; color: #ef6c00; }
        .status.shipping { background: #e3f2fd; color: #1565c0; }
        .status.completed { background: #e8f5e9; color: #2e7d32; }
        .status.cancelled { background: #ffebee; color: #c62828; }

        .pay-status { font-weight: 700; font-size: 14px; }
        .paid { color: #2e7d32; }
        .unpaid { color: #d62828; }
        .price { font-weight: 700; color: var(--primary-red); font-size: 17px; }
        
        /* BUTTONS */
        .btn-invoice { background: #222; color: #fff; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-weight: 700; font-size: 12px; transition: 0.3s; display: inline-flex; align-items: center; gap: 5px; border: none; cursor: pointer; }
        .btn-invoice:hover { background: var(--primary-red); }
        
        .btn-cancel { background: transparent; border: 1.5px solid #d32f2f; color: #d32f2f; padding: 7px 12px; border-radius: 6px; cursor: pointer; font-weight: 700; transition: 0.3s; font-size: 12px; }
        .btn-cancel:hover { background: #d32f2f; color: #fff; }

        .btn-review { background: transparent; border: 1.5px solid #ff9800; color: #ff9800; padding: 7px 12px; border-radius: 6px; cursor: pointer; font-weight: 700; transition: 0.3s; font-size: 12px; }
        .btn-review:hover { background: #ff9800; color: #fff; }
        
        /* ALERT */
        .alert { padding: 15px 20px; border-radius: 10px; margin-bottom: 25px; font-weight: 600; border-left: 5px solid; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #e8f5e9; color: #2e7d32; border-color: #2e7d32; }
        .alert-error { background: #ffebee; color: #c62828; border-color: #c62828; }

        /* MODAL RATING */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 2000; justify-content: center; align-items: center; }
        .modal-content { background: #fff; padding: 30px; border-radius: 15px; width: 450px; position: relative; animation: slideDown 0.3s ease; }
        @keyframes slideDown { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .close-modal { position: absolute; top: 15px; right: 20px; font-size: 24px; cursor: pointer; color: #aaa; }
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 10px; font-size: 35px; margin: 20px 0; }
        .star-rating input { display: none; }
        .star-rating label { color: #ddd; cursor: pointer; }
        .star-rating label:hover, .star-rating label:hover ~ label, .star-rating input:checked ~ label { color: var(--accent-yellow); }
        .modal-content textarea { width: 100%; height: 120px; padding: 15px; border: 1px solid #ddd; border-radius: 10px; margin-bottom: 20px; resize: none; font-family: inherit; }
        .btn-submit-review { background: var(--primary-red); color: #fff; border: none; width: 100%; padding: 15px; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .btn-submit-review:hover { background: var(--hover-red); }
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
        <div class="user-nav">
            <h6>Bảng điều khiển</h6>
            <a href="profile.php"><i class="fas fa-user-circle"></i> Hồ sơ cá nhân</a>
            <a href="orders.php" class="active"><i class="fas fa-shopping-bag"></i> Lịch sử mua hàng</a>
            <a href="my_orders.php"><i class="fas fa-shopping-bag"></i> Đơn hàng của bạn</a>
            <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">
            <a href="../auth/logout.php" style="color: #d32f2f;"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>

        <div class="main-card">
            <h2>Đơn hàng của tôi</h2>
            
            <?php if ($message): ?>
                <div class="alert alert-<?= $message_type ?>"><i class="fas fa-info-circle"></i> <?= $message ?></div>
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
                            <td style="max-width: 220px; font-weight: 600; line-height: 1.4;">
                                <?= !empty($o['ds_ten_xe']) ? htmlspecialchars($o['ds_ten_xe']) : 'Sản phẩm đã xóa' ?>
                            </td>
                            <td class="price"><?= number_format($o['total_price'], 0, ',', '.') ?>đ</td>
                            <td>
                                <?php if($o['payment_status'] == 'paid'): ?>
                                    <span class="pay-status paid">✅ Đã trả</span>
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
                                

                                    <?php if($o['status'] == 'pending'): ?>
                                        <form method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                            <input type="hidden" name="cancel_id" value="<?= $o['id'] ?>">
                                            <button type="submit" class="btn-cancel">HỦY</button>
                                        </form>
                                    <?php elseif($o['status'] == 'completed'): ?>
                                        <button class="btn-review" 
                                                onclick="openReviewModal('<?= addslashes(htmlspecialchars($o['ds_ten_xe'])) ?>', '<?= $o['id_xe_de_danh_gia'] ?>')">
                                            ĐÁNH GIÁ
                                        </button>
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

<!-- MODAL ĐÁNH GIÁ -->
<div id="reviewModal" class="modal-overlay">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h3 style="color: var(--primary-red); margin-bottom: 5px;">ĐÁNH GIÁ SẢN PHẨM</h3>
        <p id="modalBikeName" style="color: #666; font-weight: 600; font-size: 14px; margin-bottom: 10px;"></p>
        
        <form method="POST">
            <input type="hidden" name="product_id" id="modal_product_id">
            <div class="star-rating">
                <input type="radio" name="rating" id="star5" value="5"><label for="star5" class="fas fa-star"></label>
                <input type="radio" name="rating" id="star4" value="4"><label for="star4" class="fas fa-star"></label>
                <input type="radio" name="rating" id="star3" value="3"><label for="star3" class="fas fa-star"></label>
                <input type="radio" name="rating" id="star2" value="2"><label for="star2" class="fas fa-star"></label>
                <input type="radio" name="rating" id="star1" value="1"><label for="star1" class="fas fa-star"></label>
            </div>
            <textarea name="comment" placeholder="Chia sẻ cảm nhận của bạn về chiếc xe này..." required></textarea>
            <button type="submit" name="submit_review" class="btn-submit-review">GỬI ĐÁNH GIÁ NGAY</button>
        </form>
    </div>
</div>

<script>
function openReviewModal(bikeName, productId) {
    document.getElementById('modalBikeName').innerText = "Sản phẩm: " + bikeName;
    document.getElementById('modal_product_id').value = productId;
    document.getElementById('reviewModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('reviewModal').style.display = 'none';
}
// Đóng modal khi click ra ngoài vùng nội dung
window.onclick = function(e) { 
    if (e.target.className == 'modal-overlay') closeModal(); 
}
</script>

</body>
</html>