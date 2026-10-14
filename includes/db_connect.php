<?php
// E:\xampp\htdocs\AutoMoto.vn\includes\db_connect.php

$host     = "localhost";
$user     = "root";
$pass     = "";               // để trống nếu root không có mật khẩu
$dbname   = "automoto";       // thay bằng tên database thật của bạn (xem lại ở phpMyAdmin)

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Để kiểm tra nhanh khi test
// echo "Kết nối DB thành công!"; // comment dòng này sau khi test OK
?>