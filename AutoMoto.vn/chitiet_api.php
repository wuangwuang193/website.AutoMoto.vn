<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once 'includes/db_connect.php';

$slug = $_GET['id'] ?? '';

if (empty($slug)) {
    http_response_code(400);
    echo json_encode(['error' => 'Thiếu slug']);
    exit;
}

$sql = "SELECT * FROM bikes WHERE slug = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Không tìm thấy xe']);
    exit;
}

$bike = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Hàm fix đường dẫn ảnh: thêm dấu / nếu thiếu giữa các phần
function fix_image_path($path) {
    $path = trim($path);
    $path = str_replace('\\', '/', $path); // thống nhất dùng /

    // Nếu thiếu dấu / giữa img-products-suzuki-V-Strom... thì thêm
    $path = preg_replace('/imgproducts/', 'img/products/', $path);
    $path = preg_replace('/suzukiV-Strom/', 'suzuki/V-Strom/', $path);
    $path = preg_replace('/V-Strom250SX/', 'V-Strom 250SX/', $path); // thêm khoảng trắng nếu cần, và / trước tên file

    // Nếu tên folder có khoảng trắng (V-Strom 250SX), thêm / trước tên file ảnh
    if (strpos($path, 'V-Strom 250SX') !== false && strpos($path, 'V-Strom 250SX/') === false) {
        $path = str_replace('V-Strom 250SX', 'V-Strom 250SX/', $path);
    }

    return $path;
}

// Sửa main_image
$bike['main_image'] = fix_image_path($bike['main_image'] ?? '');

// Sửa mảng images
$images_raw = $bike['images'] ?? '';
$images_arr = explode(',', $images_raw);
$bike['images_arr'] = array_filter(array_map('fix_image_path', array_map('trim', $images_arr)));

// Parse JSON khác
$bike['features_arr'] = json_decode($bike['features'] ?? '[]', true);
$bike['specs_arr']   = json_decode($bike['specs'] ?? '{}', true);
$bike['colors_arr']  = json_decode($bike['colors'] ?? '[]', true);

// Xóa trường gốc để gọn
unset($bike['images'], $bike['features'], $bike['specs'], $bike['colors']);

echo json_encode($bike, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>

