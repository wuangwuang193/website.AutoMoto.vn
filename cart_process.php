<?php
session_start();
include "includes/db_connect.php"; // Kết nối CSDL

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Lấy ID xe từ link truyền sang

    // 1. Tạo giỏ hàng nếu chưa có
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // 2. Bỏ xe vào giỏ hàng (nếu trùng thì tăng số lượng)
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }

    // 3. Chuyển hướng thẳng đến trang Giỏ hàng (cart.php)
    header("Location: cart.php");
    exit();
}
?>