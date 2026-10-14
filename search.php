<?php
include "includes/db_connect.php";

if (!isset($_GET['keyword']) || empty($_GET['keyword'])) {
    die("Không có từ khóa tìm kiếm!");
}

$keyword = $_GET['keyword'];

// tìm theo tên sản phẩm
$stmt = $conn->prepare("SELECT * FROM bikes WHERE name LIKE ? LIMIT 1");
$searchTerm = "%" . $keyword . "%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();

    // chuyển sang trang chi tiết
    header("Location: chitietsp.php?id=" . $product['slug']);
    exit();
} else {
    echo "Không tìm thấy sản phẩm!";
}
?>