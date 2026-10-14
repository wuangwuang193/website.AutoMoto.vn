<?php
session_start();
include "../includes/db_connect.php"; 


 if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
     header("Location: ../auth/login.php"); exit;
 }

$message = '';
$message_type = 'success';

// 2. XỬ LÝ ACTIONS (POST) - THÊM / SỬA / XÓA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    try {
        if ($action === 'add' || $action === 'update') {
            $id            = $_POST['id'] ?? 0;
            $brand         = trim($_POST['brand']);
            $name          = trim($_POST['name']);
            $category      = trim($_POST['category']);
            $price         = floatval($_POST['price']);
            $stock         = intval($_POST['stock'] ?? 0);
            $description   = trim($_POST['description'] ?? '');
            
            // Định dạng giá hiển thị
            $price_formatted = number_format($price, 0, ',', '.') . ' VNĐ';

            // Tạo Slug tự động
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
            $slug = trim($slug, '-');

            // Xử lý ảnh chính
            $main_image = $_POST['current_main_image'] ?? '';
            if (!empty($_FILES['main_image']['name'])) {
                $target_dir = "../img/products/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
                $file_name = time() . "_" . basename($_FILES["main_image"]["name"]);
                if (move_uploaded_file($_FILES["main_image"]["tmp_name"], $target_dir . $file_name)) {
                    $main_image = "img/products/" . $file_name;
                }
            }

            // Xử lý chuỗi ảnh phụ
            $images_str = $_POST['current_images'] ?? '';
            if (!empty($_FILES['additional_images']['name'][0])) {
                $new_images_arr = !empty($images_str) ? explode(',', $images_str) : [];
                foreach ($_FILES['additional_images']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['additional_images']['error'][$key] == 0) {
                        $file_name = time() . "_sub_" . basename($_FILES["additional_images"]["name"][$key]);
                        if (move_uploaded_file($tmp_name, "../img/products/" . $file_name)) {
                            $new_images_arr[] = "img/products/" . $file_name;
                        }
                    }
                }
                $images_str = implode(',', $new_images_arr);
            }

            if ($action === 'add') {
                $sql = "INSERT INTO bikes (brand, name, slug, category, price, price_formatted, stock, description, main_image, images) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssisss", $brand, $name, $slug, $category, $price, $price_formatted, $stock, $description, $main_image, $images_str);
                $message = "Thêm xe mới thành công!";
            } else {
                $sql = "UPDATE bikes SET brand=?, name=?, slug=?, category=?, price=?, price_formatted=?, stock=?, description=?, main_image=?, images=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssisssi", $brand, $name, $slug, $category, $price, $price_formatted, $stock, $description, $main_image, $images_str, $id);
                $message = "Cập nhật sản phẩm thành công!";
            }
            $stmt->execute();

        } elseif ($action === 'delete') {
            $id = intval($_POST['id']);
            $conn->query("DELETE FROM bikes WHERE id = $id");
            $message = "Đã xóa sản phẩm!";
        }
    } catch (Exception $e) {
        $message = "Lỗi: " . $e->getMessage();
        $message_type = 'error';
    }
}

// 3. LOGIC TÌM KIẾM & LẤY DANH SÁCH
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $sql = "SELECT * FROM bikes WHERE name LIKE ? OR brand LIKE ? OR category LIKE ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $search_param = "%$search%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM bikes ORDER BY id DESC");
}
$bikes = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý Xe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">

</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">AUTOMOTO ADMIN</div>
    <a href="index_admin.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="admin_bikes.php" class="active"><i class="fas fa-motorcycle"></i>Sản phẩm</a>
    <a href="admin_users.php"><i class="fas fa-users"></i> Người dùng</a> 
    <a href="order.php"><i class="fas fa-shopping-cart"></i> Đơn hàng</a>
    <a href="#"><i class="fas fa-star"></i> Đánh giá</a>
    <a href="../auth/logout.php" style="margin-top: auto; color: var(--primary);"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
</div>

<div class="main-content">
    <!-- Header -->
    <div class="header-action">
        <h1>Quản lý kho xe</h1>
        <button class="btn btn-add" onclick="openModal('add')"><i class="fas fa-plus"></i> THÊM XE MỚI</button>
    </div>

    <!-- Thanh tìm kiếm -->
    <div class="search-area">
        <form method="GET" action="" class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" placeholder="Tìm tên xe, thương hiệu hoặc phân loại..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" style="display:none"></button>
        </form>
        <?php if($search !== ''): ?>
            <a href="admin_bikes.php" style="color: #888; font-size: 14px; text-decoration: none;"><i class="fas fa-times"></i> Xóa tìm kiếm</a>
        <?php endif; ?>
    </div>

    <!-- Thông báo -->
    <?php if ($message): ?>
        <div class="alert alert-<?= $message_type ?>"><?= $message ?></div>
    <?php endif; ?>

    <!-- Bảng dữ liệu -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 100px;">Ảnh</th>
                    <th>Thông tin xe</th>
                    <th>Thương hiệu</th>
                    <th>Giá bán</th>
                    <th>Kho</th>
                    <th>Phân loại</th>
                    <th style="text-align: right;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bikes as $b): ?>
                <tr>
                    <td>
                        <?php 
                        $img_array = explode(',', $b['images'] ?? '');
                        $first_image = !empty(trim($img_array[0])) ? trim($img_array[0]) : $b['main_image'];
                        $first_image = str_replace(array("\r", "\n"), '', $first_image);
                        ?>
                        <img src="../<?= htmlspecialchars($first_image) ?>" 
                             style="width:70px; height:50px; object-fit:cover; border-radius:8px; border:1px solid #eee;"
                             onerror="this.src='../img/no-image.png'">
                    </td>
                    <td><strong style="display:block;"><?= htmlspecialchars($b['name']) ?></strong><small style="color:#999">ID: #<?= $b['id'] ?></small></td>
                    <td><?= htmlspecialchars($b['brand']) ?></td>
                    <td style="color: var(--primary); font-weight: 700;"><?= $b['price_formatted'] ?></td>
                    <td><?= $b['stock'] ?><td style="font-weight: bold;">
    <?php if($b['stock'] <= 0): ?>
        <span style="color: #d32f2f; background: #fdecea; padding: 2px 8px; border-radius: 4px;">Hết hàng</span>
    <?php elseif($b['stock'] <= 3): ?>
        <span style="color: #ef6c00; background: #fff3e0; padding: 2px 8px; border-radius: 4px;">Sắp hết (<?= $b['stock'] ?>)</span>
    <?php else: ?>
        <span style="color: #2e7d32;"><?= $b['stock'] ?></span>
    <?php endif; ?>
</td></td>
                    <td><span style="background:#f3f4f6; padding:4px 10px; border-radius:15px; font-size:11px;"><?= htmlspecialchars($b['category']) ?></span></td>
                    <td style="text-align: right;">
                        <button class="btn btn-edit" onclick='editBike(<?= json_encode($b, JSON_UNESCAPED_UNICODE) ?>)'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Xóa sản phẩm này?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                            <button type="submit" class="btn btn-delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($bikes)): ?>
                    <tr><td colspan="7" style="text-align:center; padding:50px; color:#999;">Không tìm thấy kết quả nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Thêm/Sửa -->
<div id="bikeModal" class="modal">
    <div class="modal-content">
        <h2 id="modalTitle" style="margin-bottom:20px;">Thêm sản phẩm</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" id="modalAction" value="add">
            <input type="hidden" name="id" id="bikeId">
            <input type="hidden" name="current_main_image" id="current_main_image">
            <input type="hidden" name="current_images" id="current_images">

            <div class="form-group">
                <label>Tên xe *</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Thương hiệu</label>
                    <input type="text" name="brand" id="brand">
                </div>
                <div class="form-group">
                    <label>Phân loại</label>
                    <input type="text" name="category" id="category">
                </div>
                <div class="form-group">
                    <label>Giá bán (VNĐ) *</label>
                    <input type="number" name="price" id="price" required>
                </div>
                <div class="form-group">
                    <label>Số lượng kho</label>
                    <input type="number" name="stock" id="stock" value="0">
                </div>
            </div>

            <div class="form-group">
                <label>Mô tả sản phẩm</label>
                <textarea name="description" id="description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label>Ảnh sản phẩm</label>
                <div id="previewArea" style="margin-bottom:10px;"></div>
                <input type="file" name="main_image" accept="image/*">
                <p style="font-size:12px; color:#888; margin-top:5px;">Ảnh phụ (chọn nhiều file):</p>
                <input type="file" name="additional_images[]" multiple accept="image/*">
            </div>

            <div style="text-align: right; padding-top:20px; border-top: 1px solid #eee;">
                <button type="button" class="btn" style="background:#eee;" onclick="closeModal()">Đóng</button>
                <button type="submit" class="btn btn-add">LƯU DỮ LIỆU</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('bikeModal');

    function openModal(mode) {
        modal.style.display = 'flex';
        if(mode === 'add') {
            document.getElementById('modalTitle').innerText = "Thêm xe mới";
            document.getElementById('modalAction').value = "add";
            document.getElementById('bikeId').value = "";
            document.getElementById('previewArea').innerHTML = "";
            document.querySelector('#bikeModal form').reset();
        }
    }

    function editBike(data) {
        openModal('edit');
        document.getElementById('modalTitle').innerText = "Sửa: " + data.name;
        document.getElementById('modalAction').value = "update";
        document.getElementById('bikeId').value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('brand').value = data.brand;
        document.getElementById('category').value = data.category;
        document.getElementById('price').value = data.price;
        document.getElementById('stock').value = data.stock;
        document.getElementById('description').value = data.description || "";
        document.getElementById('current_main_image').value = data.main_image;
        document.getElementById('current_images').value = data.images;

        let imgToShow = data.main_image;
        if(data.images) imgToShow = data.images.split(',')[0];
        
        if(imgToShow) {
            document.getElementById('previewArea').innerHTML = `<img src="../${imgToShow}" style="width:100px; height:70px; object-fit:cover; border-radius:8px;">`;
        }
    }

    function closeModal() { modal.style.display = 'none'; }
    window.onclick = (e) => { if(e.target == modal) closeModal(); }
</script>

</body>
</html>