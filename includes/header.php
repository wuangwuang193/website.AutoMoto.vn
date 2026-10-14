<div class="user-box" id="user-box">
    <!-- Icon User -->
    <i class="fa-regular fa-user" id="user-icon"></i>

    <!-- Menu thả xuống -->
    <div class="user-dropdown" id="user-dropdown">
        <?php if(isset($_SESSION['user_id'])): ?>
            <!-- Giao diện khi ĐÃ đăng nhập -->
            <div class="user-info-top">
                <p>Chào, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></p>
            </div>
            <ul>
                <li><a href="profile.php"><i class="fa-solid fa-address-card"></i> Tài khoản của tôi</a></li>
                <li><a href="orders.php"><i class="fa-solid fa-bag-shopping"></i> Đơn hàng đã mua</a></li>
                <hr>
                <li><a href="auth/logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
            </ul>
        <?php else: ?>
            <!-- Giao diện khi CHƯA đăng nhập -->
            <ul>
                <li><a href="auth/login.php"><i class="fa-solid fa-key"></i> Đăng nhập</a></li>
                <li><a href="auth/register.php"><i class="fa-solid fa-user-plus"></i> Đăng ký</a></li>
            </ul>
        <?php endif; ?>
    </div>
</div>