<?php
session_start();
include "includes/db_connect.php";

// 1. Bật chế độ báo lỗi để dễ theo dõi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 2. Kiểm tra tính hợp lệ của yêu cầu
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// 3. Lấy thông tin từ form
$user_id = $_SESSION['user_id'];
$payment_method = $_POST['payment_method'];
$total_price = (float)$_POST['total_price'];

try {
    // BẮT ĐẦU GIAO DỊCH (TRANSACTION)
    $conn->begin_transaction();

    // BƯỚC 1: Lưu thông tin vào bảng orders
    $sql_order = "INSERT INTO orders (user_id, total_price, payment_method, status, created_at) VALUES (?, ?, ?, 'pending', NOW())";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("ids", $user_id, $total_price, $payment_method);
    $stmt_order->execute();
    
    $order_id = $conn->insert_id; // Lấy ID đơn hàng vừa tạo

    if (!$order_id) {
        throw new Exception("Lỗi: Không thể tạo mã đơn hàng.");
    }

    // BƯỚC 2: Tạo bản ghi thanh toán trong bảng payments
    // (Lưu ý: Bảng payments cần có cột amount và transaction_code như lệnh SQL ở trên)
    $sql_pay = "INSERT INTO payments (order_id, payment_status, amount) VALUES (?, 'unpaid', ?)";
    $stmt_pay = $conn->prepare($sql_pay);
    $stmt_pay->bind_param("id", $order_id, $total_price);
    $stmt_pay->execute();

    // BƯỚC 3: Lưu chi tiết từng món hàng & Cập nhật kho hàng
    $sql_item = "INSERT INTO order_items (order_id, price, product_id, quantity) VALUES (?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);

    $sql_update_stock = "UPDATE bikes SET stock = stock - ? WHERE id = ?";
    $stmt_update_stock = $conn->prepare($sql_update_stock);

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Lấy giá và kiểm tra tồn kho thực tế
        $res = $conn->query("SELECT price, stock, name FROM bikes WHERE id = " . (int)$product_id);
        $bike = $res->fetch_assoc();
        
        if (!$bike) {
            throw new Exception("Sản phẩm ID $product_id không tồn tại.");
        }

        if ($bike['stock'] < $quantity) {
            throw new Exception("Sản phẩm '" . $bike['name'] . "' hiện không đủ hàng trong kho.");
        }

        $unit_price = $bike['price'];
        
        // Lưu vào chi tiết đơn hàng
        $stmt_item->bind_param("idii", $order_id, $unit_price, $product_id, $quantity);
        $stmt_item->execute();

        // Trừ số lượng trong kho
        $stmt_update_stock->bind_param("ii", $quantity, $product_id);
        $stmt_update_stock->execute();
    }

    // NẾU MỌI THỨ OK -> XÁC NHẬN LƯU VÀO DATABASE
    $conn->commit();

    // Xóa giỏ hàng sau khi đặt thành công
    unset($_SESSION['cart']);

    // ĐIỀU HƯỚNG THEO PHƯƠNG THỨC THANH TOÁN
    if ($payment_method === 'bank_transfer') {
        // Nếu là chuyển khoản -> Chuyển đến trang mã QR
        header("Location: bank_info.php?order_id=" . $order_id);
    } else {
        // Nếu là COD -> Chuyển đến trang cảm ơn/hoàn tất
        header("Location: thank_you.php?order_id=" . $order_id);
    }
    exit;

} catch (Exception $e) {
    // NẾU CÓ LỖI -> HỦY TOÀN BỘ CÁC THAY ĐỔI TRÊN (Rollback)
    $conn->rollback();
    
    // Hiển thị thông báo lỗi đẹp mắt cho người dùng
    echo "<div style='color:red; font-family:sans-serif; padding:30px; border:2px solid red; background:#fff5f5; border-radius:10px; max-width:600px; margin:50px auto; text-align:center;'>";
    echo "<h2>⚠️ LỖI ĐẶT HÀNG</h2>";
    echo "<p style='font-size:18px;'><strong>" . $e->getMessage() . "</strong></p>";
    echo "<hr style='border:1px solid #ffcccc;'>";
    echo "<p>Vui lòng kiểm tra lại giỏ hàng hoặc liên hệ Admin.</p>";
    echo "<a href='cart.php' style='display:inline-block; padding:10px 20px; background:#e31e24; color:white; text-decoration:none; border-radius:5px; font-weight:bold;'>QUAY LẠI GIỎ HÀNG</a>";
    echo "</div>";
    exit;
}
?>