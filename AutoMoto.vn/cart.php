<?php
session_start();
include "includes/db_connect.php";
// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php"); exit;
}


$total_price = 0;
$items_to_display = array();

// 1. Lấy dữ liệu từ DB và so khớp với Session
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $id = (int)$id;
        // Lấy thêm cột stock để kiểm tra giới hạn
        $sql = "SELECT id, name, price, images, brand, stock FROM bikes WHERE id = $id";
        $result = $conn->query($sql);
        
        if ($result && $row = $result->fetch_assoc()) {
            $display_img = 'img/products/default.jpg';
            if (!empty($row['images'])) {
                $imgs = explode(',', $row['images']);
                $display_img = trim($imgs[0]);
            }
            
            $row['quantity'] = $quantity;
            $row['display_img'] = $display_img;
            $row['subtotal'] = $row['price'] * $quantity;
            
            $items_to_display[] = $row;
            $total_price += $row['subtotal'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - AutoMoto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700;900&display=swap");

        :root {
            --primary-red: #e31e24;
            --dark-bg: #1a1a1a;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Roboto Condensed", sans-serif; }
        body { background-color: #fff; color: #1a1a1a; }
        .container { max-width: 1300px; margin: 0 auto; padding: 40px 20px; }

        /* Progress Steps */
        .progress-steps { display: flex; justify-content: center; align-items: center; margin-bottom: 50px; gap: 20px; }
        .step { display: flex; align-items: center; gap: 10px; font-weight: 700; color: #ccc; text-transform: uppercase; }
        .step.active { color: var(--primary-red); }
        .step-num { width: 30px; height: 30px; border: 2px solid #ccc; border-radius: 50%; display: grid; place-items: center; font-size: 14px; }
        .step.active .step-num { border-color: var(--primary-red); background: var(--primary-red); color: #fff; }
        .line { width: 50px; height: 2px; background: #eee; }

        .cart-header { border-left: 8px solid var(--primary-red); padding-left: 20px; margin-bottom: 40px; }
        .cart-header h1 { font-size: 48px; font-weight: 900; text-transform: uppercase; letter-spacing: -1px; }

        .cart-wrapper { display: grid; grid-template-columns: 1fr 400px; gap: 40px; }

        .cart-card { display: flex; background: #fff; border: 1px solid #eee; margin-bottom: 20px; position: relative; overflow: hidden; transition: var(--transition); }
        .cart-card:hover { border-color: var(--primary-red); box-shadow: 0 10px 30px rgba(227, 30, 36, 0.1); }
        .cart-card::before { content: ''; position: absolute; top: 0; left: -100%; width: 5px; height: 100%; background: var(--primary-red); transition: var(--transition); }
        .cart-card:hover::before { left: 0; }

        .item-img { width: 200px; height: 200px; background: #f9f9f9; display: flex; align-items: center; justify-content: center; padding: 15px; }
        .item-img img { max-width: 100%; max-height: 100%; object-fit: contain; transition: var(--transition); }
        .cart-card:hover .item-img img { transform: scale(1.1) rotate(-3deg); }

        .item-details { flex: 1; padding: 30px; display: flex; flex-direction: column; justify-content: space-between; }
        .item-details h3 { font-size: 24px; text-transform: uppercase; margin-bottom: 5px; }
        .item-details .brand { color: var(--primary-red); font-weight: 700; font-size: 14px; letter-spacing: 2px; }
        
        .price-qty { display: flex; align-items: center; justify-content: space-between; margin-top: 20px; }
        .current-price { font-size: 22px; font-weight: 700; color: #000; }

        /* QTY Controls & Stock Logic */
        .qty-box { display: flex; align-items: center; border: 1px solid #000; }
        .qty-box a { padding: 8px 15px; color: #000; text-decoration: none; font-size: 14px; transition: 0.2s; display: flex; align-items: center; justify-content: center; }
        .qty-box a:hover { background: #000; color: #fff; }
        .qty-val { padding: 0 20px; font-weight: 700; border-left: 1px solid #eee; border-right: 1px solid #eee; }
        
        /* Trạng thái nút bị khóa khi hết stock */
        .qty-box a.disabled { 
            pointer-events: none; 
            opacity: 0.2; 
            background: #eee;
            color: #999;
        }
        .stock-warning { color: var(--primary-red); font-size: 12px; font-weight: 700; margin-top: 8px; text-transform: uppercase; }

        .summary-box { background: var(--dark-bg); color: #fff; padding: 40px; height: fit-content; border-radius: 4px; position: sticky; top: 20px; }
        .summary-box h2 { text-transform: uppercase; font-size: 22px; margin-bottom: 30px; border-bottom: 1px solid #444; padding-bottom: 15px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; }
        .summary-row.total { margin-top: 30px; border-top: 1px dashed #444; padding-top: 20px; font-size: 24px; font-weight: 900; color: var(--primary-red); }

        .btn-checkout { display: block; width: 100%; background: var(--primary-red); color: #fff; text-align: center; padding: 18px; text-decoration: none; font-weight: 900; text-transform: uppercase; margin-top: 30px; clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%); transition: var(--transition); border: none; cursor: pointer; }
        .btn-checkout:hover { transform: skewX(-5deg); filter: brightness(1.2); }

        .empty-state { text-align: center; padding: 100px 0; width: 100%; }
        .empty-state i { font-size: 80px; color: #eee; margin-bottom: 20px; display: block;}

        @media (max-width: 992px) { .cart-wrapper { grid-template-columns: 1fr; } .cart-card { flex-direction: column; } .item-img { width: 100%; } }
    </style>
</head>
<body>

<div class="container">
    <div class="progress-steps">
        <div class="step active"><span class="step-num">1</span> GIỎ HÀNG</div>
        <div class="line"></div>
        <div class="step"><span class="step-num">2</span> THANH TOÁN</div>
        <div class="line"></div>
        <div class="step"><span class="step-num">3</span> HOÀN TẤT</div>
    </div>

    <header class="cart-header">
        <h1>MY GARAGE</h1>
        <p>Kiểm tra lại những "chiến mã" của bạn trước khi xuất xưởng.</p>
    </header>

    <?php if (!empty($items_to_display)): ?>
    <div class="cart-wrapper">
        <div class="cart-items">
            <?php foreach ($items_to_display as $item): ?>
            <div class="cart-card">
                <div class="item-img">
                    <img src="<?php echo htmlspecialchars($item['display_img']); ?>" 
                         onerror="this.src='img/products/default.jpg';"
                         alt="Product Image">
                </div>
                <div class="item-details">
                    <div>
                        <span class="brand"><?php echo htmlspecialchars($item['brand']); ?></span>
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    </div>
                    <div class="price-qty">
                        <div class="current-price"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</div>
                        
                        <div>
                            <div class="qty-box">
                                <a href="handle_cart.php?action=minus&id=<?php echo $item['id']; ?>"><i class="fas fa-minus"></i></a>
                                <span class="qty-val"><?php echo $item['quantity']; ?></span>
                                
                                <?php 
                                    // Kiểm tra xem số lượng trong giỏ đã đạt giới hạn tồn kho chưa
                                    $is_maxed = ($item['quantity'] >= $item['stock']); 
                                ?>
                                <a href="handle_cart.php?action=add&id=<?php echo $item['id']; ?>" class="<?php echo $is_maxed ? 'disabled' : ''; ?>">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                            <?php if($is_maxed): ?>
                                <p class="stock-warning"><i class="fas fa-exclamation-triangle"></i> Tối đa: <?php echo $item['stock']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <a href="handle_cart.php?action=remove&id=<?php echo $item['id']; ?>" style="position: absolute; top: 15px; right: 15px; color: #ccc;"><i class="fas fa-times"></i></a>
            </div>
            <?php endforeach; ?>
        </div>

        <aside class="summary-box">
            <h2>ĐƠN HÀNG</h2>
            <div class="summary-row"><span>Tạm tính</span><span><?php echo number_format($total_price, 0, ',', '.'); ?>đ</span></div>
            <div class="summary-row"><span>Phí vận chuyển</span><span style="color: #4CAF50;">FREE</span></div>
            <div class="summary-row total"><span>TỔNG CỘNG</span><span><?php echo number_format($total_price, 0, ',', '.'); ?>đ</span></div>
            <a href="checkout.php" class="btn-checkout">TIẾN HÀNH THANH TOÁN <i class="fas fa-chevron-right" style="margin-left: 10px;"></i></a>
            <p style="text-align: center; margin-top: 20px;"><a href="index.php" style="color: #888; text-decoration: none; font-size: 13px; text-transform: uppercase;">Tiếp tục mua sắm</a></p>
        </aside>
    </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-motorcycle"></i>
            <h2>Garage của bạn đang trống!</h2>
            <p style="margin: 20px 0 30px;">Hãy chọn cho mình một chiến mã ưng ý để bắt đầu hành trình.</p>
            <a href="index.php" class="btn-checkout" style="max-width: 300px; margin: 0 auto; display: inline-block;">ĐẾN CỬA HÀNG</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>