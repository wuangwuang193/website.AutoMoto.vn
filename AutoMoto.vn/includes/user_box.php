<?php
$root = "/AutoMoto.vn/"; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? 'Khách';
$userRole = $_SESSION['role'] ?? 'customer';
?>

<!-- 1. THÊM LINK NÀY VÀO FILE INDEX.PHP HOẶC HEADER.PHP CỦA BẠN (TRONG THẺ <HEAD>) -->
<!-- Nếu không thể sửa file đó, giữ nguyên ở đây nhưng tốt nhất là cho vào HEAD -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="user-box" id="user-box-container">
  <!-- Icon người dùng chính -->
  <i class="fa-solid fa-circle-user" id="user-icon-trigger" style="cursor: pointer; font-size: 24px; color: #fff;"></i>
  
  <div class="user-dropdown" id="user-dropdown-menu">
    <?php if ($isLoggedIn): ?>
        <div class="user-info-header">
            Chào, <?php echo htmlspecialchars($userName); ?>
        </div>
        
        <a href="<?php echo $root; ?>user/profile.php" class="dropdown-item">
            <i class="fa-solid fa-user-gear"></i> Hồ sơ của tôi
        </a>
        <a href="<?php echo $root; ?>user/orders.php" class="dropdown-item">
            <i class="fa-solid fa-clock-rotate-left"></i> Đơn hàng
        </a>
        
        <?php if ($userRole === 'admin'): ?>
            <a href="<?php echo $root; ?>admin/index_admin.php" class="dropdown-item" style="color: #ffcc00; border-top: 1px solid #333;">
                <i class="fa-solid fa-shield-halved"></i> Quản trị hệ thống
            </a>
        <?php endif; ?>
        
        <div class="dropdown-divider"></div>
        <a href="<?php echo $root; ?>auth/logout.php" class="dropdown-item logout-link">
            <i class="fa-solid fa-power-off"></i> Đăng xuất
        </a>

    <?php else: ?>
        <a href="<?php echo $root; ?>auth/login.php" class="dropdown-item">
            <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
        </a>
        <a href="<?php echo $root; ?>auth/register.php" class="dropdown-item">
            <i class="fa-solid fa-user-plus"></i> Đăng ký
        </a>
    <?php endif; ?>
  </div>
</div>

<style>
/* 2. FIX LỖI ĐÈ FONT (QUAN TRỌNG NHẤT) */
.user-box i {
    /* Ép buộc các thẻ i phải dùng font của FontAwesome, không được dùng Roboto */
    font-family: "Font Awesome 6 Free" !important; 
    font-weight: 900 !important; 
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
}

.user-box { 
    position: relative; 
    display: flex; 
    align-items: center; 
    font-family: "Roboto Condensed", sans-serif;
}

/* Các thuộc tính CSS khác */
.user-dropdown {
    position: absolute;
    top: 50px;
    right: 0;
    background: #000;
    border: 1px solid #d62828;
    border-radius: 8px;
    min-width: 200px;
    display: none;
    z-index: 99999;
    box-shadow: 0 10px 30px rgba(0,0,0,0.8);
}

.user-dropdown.show { display: block; }

.user-info-header {
    padding: 15px;
    background: #111;
    color: #ffcc00;
    font-weight: 700;
    border-bottom: 1px solid #333;
    font-size: 14px;
    text-transform: uppercase;
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    color: #fff;
    text-decoration: none;
    font-size: 15px;
    transition: 0.2s;
}

.dropdown-item i {
    width: 25px; /* Giữ khoảng cách cố định cho icon */
    font-size: 16px;
    color: inherit;
}

.dropdown-item:hover {
    background: #d62828;
    color: #fff;
}

.dropdown-divider { height: 1px; background: #333; margin: 5px 0; }
.logout-link { color: #ff4d4d !important; }
</style>

<script>
(function() {
    const trigger = document.getElementById('user-icon-trigger');
    const menu = document.getElementById('user-dropdown-menu');

    if (trigger && menu) {
        trigger.onclick = function(e) {
            e.stopPropagation();
            menu.classList.toggle('show');
        };

        window.onclick = function(e) {
            if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove('show');
            }
        };
    }
})();
</script>