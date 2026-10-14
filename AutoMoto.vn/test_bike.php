<?php
// test_bike.php - File kiểm tra kết nối DB và hiển thị danh sách xe

// Bật hiển thị lỗi để dễ debug (xóa dòng này khi site lên production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include file kết nối DB
require_once 'includes/db_connect.php';

// Lấy tất cả xe từ bảng bikes (có thể LIMIT nếu nhiều dữ liệu)
$sql = "SELECT * FROM bikes ORDER BY id ASC";  // ORDER BY id để hiển thị theo thứ tự thêm

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Database - Danh sách xe</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        h1, h2 { color: #333; }
        .bike-item { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 8px; background: #f9f9f9; }
        .bike-item h3 { margin-top: 0; color: #0066cc; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h1>Test Kết nối Database & Danh sách xe</h1>

<?php if (isset($conn) && !$conn->connect_error): ?>
    <p class="success">Kết nối database thành công! (Database: <?php echo htmlspecialchars($dbname); ?>)</p>
<?php else: ?>
    <p class="error">Lỗi kết nối database. Kiểm tra file db_connect.php</p>
<?php endif; ?>

<?php
if ($result && $result->num_rows > 0) {
    echo "<h2>Danh sách xe trong database (" . $result->num_rows . " xe):</h2>";

    while ($bike = $result->fetch_assoc()) {
        echo '<div class="bike-item">';
        echo '<h3>' . htmlspecialchars($bike['name']) . ' (' . htmlspecialchars($bike['brand']) . ')</h3>';
        echo '<p><strong>Slug (dùng cho URL):</strong> ' . htmlspecialchars($bike['slug']) . '</p>';
        echo '<p><strong>Giá:</strong> ' . htmlspecialchars($bike['price_formatted']) . ' (giá gốc: ' . number_format($bike['price']) . ' VNĐ)</p>';
        echo '<p><strong>Loại xe:</strong> ' . htmlspecialchars($bike['category']) . '</p>';
        echo '<p><strong>Mô tả ngắn:</strong> ' . htmlspecialchars($bike['short_desc']) . '</p>';

        // Hiển thị một phần specs để kiểm tra JSON
        if (!empty($bike['specs'])) {
            $specs_short = substr($bike['specs'], 0, 300) . (strlen($bike['specs']) > 300 ? '...' : '');
            echo '<p><strong>Thông số kỹ thuật (mẫu):</strong></p>';
            echo '<pre>' . htmlspecialchars($specs_short) . '</pre>';
        }

        // Nếu muốn xem full specs, uncomment dòng dưới
        // echo '<pre>' . htmlspecialchars($bike['specs']) . '</pre>';

        echo '</div>';
    }
} else {
    echo '<p class="error">Chưa có xe nào trong bảng bikes, hoặc có lỗi query.</p>';
}

$conn->close();
?>

<p style="margin-top: 40px; font-size: 0.9em; color: #666;">
    File này dùng để test. Khi OK, bạn có thể xóa hoặc comment phần debug.
</p>

</body>
</html>