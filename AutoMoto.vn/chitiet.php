<?php
session_start(); // Cần thiết để kiểm tra giỏ hàng/user
include "includes/db_connect.php";

// 1. XỬ LÝ LOGIC PHP
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    die("Không tìm thấy sản phẩm!");
}

$slug = trim($_GET['slug']);

// Lấy thông tin sản phẩm chính - Đảm bảo lấy đủ cột stock và id
$stmt = $conn->prepare("SELECT * FROM bikes WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Sản phẩm không tồn tại!");
}

$product = $result->fetch_assoc();

/* ====== LẤY SẢN PHẨM GỢI Ý ====== */
$relatedStmt = $conn->prepare("
    SELECT * FROM bikes 
    WHERE category = ? AND slug != ?
    LIMIT 3
");
$relatedStmt->bind_param("ss", $product['category'], $slug);
$relatedStmt->execute();
$relatedResult = $relatedStmt->get_result();

$relatedProducts = [];
while ($row = $relatedResult->fetch_assoc()) {
    $relatedProducts[] = $row;
}

// Xử lý ảnh đầu tiên của sản phẩm chính
$main_images = explode(",", $product['images'] ?? '');
$display_main_img = !empty(trim($main_images[0])) ? trim($main_images[0]) : $product['main_image'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']); ?> - Chi tiết</title>
    <!-- Thêm Font Awesome để giống sanphamxe -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    
    <style>
        /* Giữ nguyên CSS cũ của bạn và thêm/sửa một số chỗ dưới đây */
        body { font-family: Arial, sans-serif; background: #f5f6fa; margin: 0; color: #333; }
        .container { max-width: 1200px; margin: auto; padding: 40px 20px; }
        .product-wrapper { display: flex; gap: 50px; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); flex-wrap: wrap; position: relative; }
        .product-image img { max-width: 450px; width: 100%; border-radius: 10px; display: block; }
        .product-info { flex: 1; min-width: 300px; }
        .price { font-size: 28px; color: #e74c3c; font-weight: bold; margin: 20px 0; }
        
        /* Đồng bộ Style Button */
        .btn-group { display: flex; gap: 15px; margin-top: 30px; }
        .btn { padding: 12px 30px; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: bold; transition: 0.3s; text-align: center; border: none; }
        .buy { background: #e74c3c; color: white; }
        .buy:hover { background: #c0392b; }
        .out-of-stock { background: #999 !important; cursor: not-allowed; color: #fff !important; }

        /* Style cho phần Related Item Action */
        .related-item { background: #fff; padding: 15px; border-radius: 10px; width: calc(33.333% - 20px); min-width: 250px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); box-sizing: border-box; }
        .related-item .actions { display: flex; gap: 10px; margin-top: 15px; }
        .related-item .btn { padding: 8px 12px; font-size: 13px; flex: 1; }
        .related-item .detail { background: #f1f1f1; color: #333; }
        .related-item .detail:hover { background: #ddd; }
        
        .stock-tag { position: absolute; top: 20px; left: 20px; background: #e74c3c; color: white; padding: 5px 15px; border-radius: 5px; font-weight: bold; font-size: 12px; }

        @media (max-width: 768px) {
            .product-wrapper { flex-direction: column; }
            .related-item { width: 100%; }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- HIỂN THỊ CHI TIẾT SẢN PHẨM -->
    <div class="product-wrapper">
        <?php if ($product['stock'] <= 0): ?>
            <div class="stock-tag">TẠM HẾT HÀNG</div>
        <?php endif; ?>

        <div class="product-image">
            <img src="<?= htmlspecialchars($display_main_img); ?>" alt="<?= htmlspecialchars($product['name']); ?>" onerror="this.src='img/no-image.png'">
        </div>

        <div class="product-info">
            <h1><?= htmlspecialchars($product['name']); ?></h1>

            <div class="rating">
                ⭐⭐⭐⭐⭐ <span>(4.8 đánh giá)</span>
            </div>

            <p class="price">
                <?= $product['price_formatted'] ?: number_format($product['price'], 0, ',', '.') . ' VNĐ'; ?>
            </p>

            <div class="quick-info">
                <p><strong>Hãng:</strong> <?= htmlspecialchars($product['brand']); ?></p>
                <p><strong>Phân loại:</strong> <?= htmlspecialchars($product['category']); ?></p>
                <p><strong>Tình trạng:</strong> <?= ($product['stock'] > 0) ? '<span style="color:green">Còn hàng ('.$product['stock'].')</span>' : '<span style="color:red">Hết hàng</span>'; ?></p>
            </div>

            <div class="desc" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                <strong>Mô tả chi tiết:</strong><br>
                <?= nl2br(htmlspecialchars($product['description'])); ?>
            </div>

            <div class="btn-group">
                <!-- LOGIC MUA NGAY GIỐNG SANPHAMXE -->
                <?php if ($product['stock'] > 0): ?>
                    <a href="cart_process.php?id=<?= $product['id'] ?>" class="btn buy">MUA NGAY</a>
                <?php else: ?>
                    <a href="javascript:void(0)" class="btn buy out-of-stock">HẾT HÀNG</a>
                <?php endif; ?>
                
                <a href="index.php" class="btn" style="background: #eee; color: #333;">QUAY LẠI</a>
            </div>
        </div>
    </div>

    <!-- ================= GỢI Ý SẢN PHẨM ================= -->
    <div class="related-section" style="margin-top: 50px;">
        <h2 style="border-left: 5px solid #e74c3c; padding-left: 15px;">Sản phẩm liên quan</h2>

        <div class="related-list" style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
            <?php if (empty($relatedProducts)): ?>
                <p>Không có sản phẩm liên quan nào.</p>
            <?php else: ?>
                <?php foreach ($relatedProducts as $item): ?>
                    <?php 
                    $rel_images = explode(",", $item['images'] ?? '');
                    $rel_firstImg = !empty(trim($rel_images[0])) ? trim($rel_images[0]) : $item['main_image'];
                    ?>
                    <div class="related-item">
                        <img src="<?= htmlspecialchars($rel_firstImg); ?>" alt="<?= htmlspecialchars($item['name']); ?>" style="width:100%; height:180px; object-fit:cover; border-radius:6px;">
                        <h4 style="margin: 15px 0 5px;"><?= htmlspecialchars($item['name']); ?></h4>
                        <p style="color: #e74c3c; font-weight: bold;"><?= number_format($item['price'], 0, ',', '.'); ?> VNĐ</p>
                        
                        <!-- BỘ NÚT GIỐNG SANPHAMXE -->
                        <div class="actions">
                            <?php if ($item['stock'] > 0): ?>
                                <a href="cart_process.php?id=<?= $item['id'] ?>" class="btn buy">Mua ngay</a>
                            <?php else: ?>
                                <a href="javascript:void(0)" class="btn buy out-of-stock">Hết hàng</a>
                            <?php endif; ?>
                            
                            <a href="chitiet.php?slug=<?= htmlspecialchars($item['slug']) ?>" class="btn detail">Chi tiết</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

</body>
</html>