<?php
session_start();
// PHẢI include file kết nối DB để kiểm tra tồn kho thực tế
include "includes/db_connect.php"; 

// 1. Lấy ID và Action từ URL một cách an toàn
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

// 2. Nếu có ID hợp lệ thì mới xử lý
if ($id > 0) {
    
    // Khởi tạo giỏ hàng nếu chưa tồn tại
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    switch ($action) {
        case 'add':
            // --- LOGIC FIX: KIỂM TRA TỒN KHO ---
            // Truy vấn số lượng tồn kho (stock) từ database
            $sql_check = "SELECT stock FROM bikes WHERE id = $id";
            $result_check = $conn->query($sql_check);
            
            if ($result_check && $row = $result_check->fetch_assoc()) {
                $stock_limit = (int)$row['stock'];
                $current_qty = isset($_SESSION['cart'][$id]) ? (int)$_SESSION['cart'][$id] : 0;

                // Chỉ cho phép tăng nếu số lượng trong giỏ hiện tại < tồn kho
                if ($current_qty < $stock_limit) {
                    if (isset($_SESSION['cart'][$id])) {
                        $_SESSION['cart'][$id]++;
                    } else {
                        $_SESSION['cart'][$id] = 1;
                    }
                } else {
                    // Tùy chọn: Có thể tạo thông báo lỗi nếu muốn hiển thị ở cart.php
                    $_SESSION['error_cart'] = "Sản phẩm này đã đạt giới hạn tồn kho.";
                }
            }
            break;

        case 'minus':
            // GIẢM SỐ LƯỢNG: Chỉ xử lý nếu sản phẩm đã có trong giỏ
            if (isset($_SESSION['cart'][$id])) {
                if ($_SESSION['cart'][$id] > 1) {
                    $_SESSION['cart'][$id]--;
                } else {
                    // Nếu số lượng là 1 mà trừ tiếp thì xóa luôn
                    unset($_SESSION['cart'][$id]);
                }
            }
            break;

        case 'remove': 
        case 'delete':
            // XÓA SẢN PHẨM KHỎI GIỎ
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
            break;
            
        default:
            break;
    }
}

// 3. Sau khi xử lý xong, chuyển hướng về trang giỏ hàng
header("Location: cart.php");
exit();
?>