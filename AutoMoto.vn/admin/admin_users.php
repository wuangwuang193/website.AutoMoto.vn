<?php
session_start();
require_once '../config/config.php';

// 1. Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// 2. Xử lý Cập nhật người dùng (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, role = ? WHERE id = ?");
    $stmt->execute([$name, $email, $phone, $role, $id]);
    
    $search_param = isset($_GET['search']) ? "&search=" . urlencode($_GET['search']) : "";
    header("Location: admin_users.php?msg=updated" . $search_param);
    exit;
}

// 3. Xử lý Tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 4. Xử lý Thay đổi Trạng thái
if (isset($_GET['toggle_status'])) {
    $target_id = $_GET['toggle_status'];
    $current_status = $_GET['current'];
    $new_status = ($current_status == 'active') ? 'locked' : 'active';
    
    if ($target_id != $_SESSION['user_id']) {
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $target_id]);
        $search_param = !empty($search) ? "&search=" . urlencode($search) : "";
        header("Location: admin_users.php?msg=status_updated" . $search_param);
        exit;
    }
}

// 5. Truy vấn dữ liệu
$sql = "SELECT u.id, u.name, u.email, u.phone, u.role, u.status, u.created_at,
        COUNT(o.id) as total_orders, 
        IFNULL(SUM(o.total_price), 0) as total_spent 
        FROM users u 
        LEFT JOIN orders o ON u.id = o.user_id ";

if ($search !== '') {
    $sql .= " WHERE u.name LIKE :search OR u.email LIKE :search OR u.phone LIKE :search ";
}

$sql .= " GROUP BY u.id ORDER BY u.created_at DESC";

$stmt = $pdo->prepare($sql);
if ($search !== '') {
    $stmt->bindValue(':search', "%$search%");
}
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng - AutoMoto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <style>


        /* MAIN CONTENT */
        .main {
            margin-left: 260px;
            flex: 1;
            padding: 25px 35px;
        }

        /* HEADER BAR - Đúng theo ảnh */
        .admin-panel-header {
            background: var(--white);
            padding: 12px 25px;
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .header-title { color: var(--primary); font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-info img { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; }
        .user-info span { font-weight: 600; font-size: 14px; }

        /* SEARCH & CARDS */
        .content-card {
            background: var(--white);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }
        .search-input {
            flex: 1;
            max-width: 400px;
            padding: 12px 20px;
            border: 1px solid #eee;
            border-radius: 10px;
            background: #f9f9f9;
            outline: none;
        }
        .btn-search {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0 25px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; }
        th { 
            text-align: left; padding: 15px; 
            font-size: 12px; text-transform: uppercase; 
            color: var(--text-muted); border-bottom: 1px solid #eee;
        }
        td { padding: 18px 15px; border-bottom: 1px solid #f9f9f9; font-size: 14px; }
        
        .status-badge {
            padding: 5px 12px; border-radius: 8px; font-size: 11px; font-weight: 700;
        }
        .status-active { background: #e8f5e9; color: #2e7d32; }
        .status-locked { background: #ffebee; color: #c62828; }

        .btn-action {
            width: 32px; height: 32px; border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center;
            border: none; cursor: pointer; text-decoration: none; font-size: 13px;
        }
        .btn-edit { background: #f0f0f0; color: #555; }
        .btn-lock { background: #fff0f0; color: var(--primary); }
        .btn-unlock { background: #f0fff0; color: #2e7d32; }

        /* MODAL OVERLAY - Sạch sẽ hơn */
        .modal-overlay {
            display: none; position: fixed; top:0; left:0; width:100%; height:100%;
            background: rgba(0,0,0,0.4); z-index: 1000; justify-content: center; align-items: center;
        }
        .modal-content {
            background: white; width: 400px; padding: 30px; border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .modal-content h3 { margin-top: 0; color: var(--text-main); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 12px; color: var(--text-muted); margin-bottom: 5px; }
        .form-control {
            width: 100%; padding: 10px; border: 1px solid #eee; border-radius: 8px; box-sizing: border-box;
        }
        .btn-submit {
            background: var(--primary); color: white; border: none; width: 100%;
            padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer; margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">AUTOMOTO ADMIN</div>
    <a href="index_admin.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="admin_bikes.php"><i class="fas fa-motorcycle"></i> Sản phẩm</a>
    <a href="admin_users.php" class="active"><i class="fas fa-users"></i> Người dùng</a>
    <a href="order.php"><i class="fas fa-shopping-cart"></i> Đơn hàng</a>
    <a href="admin_reviews.php"><i class="fas fa-star"></i> Đánh giá</a>
    <a href="../auth/logout.php" style="margin-top: auto; color: var(--primary);"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
</div>

<div class="main">
    <!-- Header Panel -->
    <div class="admin-panel-header">
        <div class="header-title">
            <i class="fas fa-shopping-cart"></i> Admin Panel
        </div>
        <div class="user-info">
            <i class="far fa-bell" style="margin-right: 15px; color: #888;"></i>
            <img src="https://i.pravatar.cc/150?u=<?= $_SESSION['user_id'] ?>" alt="avatar">
            <span>Quang Nhật <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px;"></i></span>
        </div>
    </div>

    <div class="content-card">
        <h2 style="margin-top: 0; margin-bottom: 20px; font-size: 18px;">Quản lý khách hàng</h2>
        
        <!-- Tìm kiếm -->
        <form class="search-container" method="GET">

        </form>

        <table>
            <thead>
                <tr>
                    <th>Khách hàng</th>
                    <th>Liên hệ</th>
                    <th>Đơn hàng</th>
                    <th>Tổng chi tiêu</th>
                    <th>Trạng thái</th>
                    <th style="text-align: center;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): $status = $user['status'] ?? 'active'; ?>
                <tr>
                    <td>
                        <div style="font-weight: 600;"><?= htmlspecialchars($user['name']) ?></div>
                        <div style="font-size: 11px; color: var(--text-muted);">ID: #<?= $user['id'] ?></div>
                    </td>
                    <td>
                        <div><?= htmlspecialchars($user['email']) ?></div>
                        <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($user['phone'] ?: 'N/A') ?></div>
                    </td>
                    <td><?= $user['total_orders'] ?> đơn</td>
                    <td style="font-weight: 700; color: #2e7d32;">₫<?= number_format($user['total_spent'], 0, ',', '.') ?></td>
                    <td>
                        <span class="status-badge <?= ($status == 'active') ? 'status-active' : 'status-locked' ?>">
                            <?= ($status == 'active') ? 'Đang hoạt động' : 'Đã khóa' ?>
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 8px;">
                            <?php if($user['role'] !== 'admin'): ?>
                                <a href="admin_users.php?toggle_status=<?= $user['id'] ?>&current=<?= $status ?>&search=<?= urlencode($search) ?>" 
                                   class="btn-action <?= ($status == 'active') ? 'btn-lock' : 'btn-unlock' ?>">
                                    <i class="fas <?= ($status == 'active') ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                                </a>
                            <?php endif; ?>
                            <button class="btn-action btn-edit" onclick='openEditModal(<?= json_encode($user) ?>)'>
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal Overlay -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <h3>Sửa thông tin</h3>
        <form method="POST">
            <input type="hidden" name="user_id" id="m_id">
            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" name="name" id="m_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="m_email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Điện thoại</label>
                <input type="text" name="phone" id="m_phone" class="form-control">
            </div>
            <div class="form-group">
                <label>Vai trò</label>
                <select name="role" id="m_role" class="form-control">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" name="update_user" class="btn-submit">Lưu cập nhật</button>
            <button type="button" onclick="closeModal()" style="background: none; border: none; width: 100%; margin-top: 10px; color: #888; cursor: pointer;">Hủy bỏ</button>
        </form>
    </div>
</div>

<script>
    function openEditModal(user) {
        document.getElementById('m_id').value = user.id;
        document.getElementById('m_name').value = user.name;
        document.getElementById('m_email').value = user.email;
        document.getElementById('m_phone').value = user.phone || '';
        document.getElementById('m_role').value = user.role;
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeModal() { document.getElementById('editModal').style.display = 'none'; }
    window.onclick = function(e) { if(e.target == document.getElementById('editModal')) closeModal(); }
</script>

</body>
</html>