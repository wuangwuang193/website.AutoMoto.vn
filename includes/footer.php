<!-- includes/footer.php -->
<footer class="section-p1">
    <div class="col">
        <a href="index.php" class="logo">
            <img src="img/logo.jpg" alt="Logo" style="width: 150px; margin-bottom: 20px;">
        </a>
        <h4>Liên hệ</h4>
        <p><strong>Địa chỉ: </strong> 123 Đường Nguyễn Văn Cừ, Quận Ninh Kiều, TP. Cần Thơ</p>
        <p><strong>Điện thoại: </strong> +84 123 456 789 / +84 987 654 321</p>
        <p><strong>Giờ làm việc: </strong> 08:00 - 21:00, Thứ 2 - Chủ Nhật</p>
        
        <div class="follow">
            <h4>Theo dõi chúng tôi</h4>
            <div class="icon">
                <i class="fab fa-facebook-f"></i>
                <i class="fab fa-twitter"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-youtube"></i>
                <i class="fab fa-tiktok"></i>
            </div>
        </div>
    </div>

    <div class="col">
        <h4>Thông tin</h4>
        <a href="gioithieu.php">Giới thiệu</a>
        <a href="tuvan.php">Liên hệ tư vấn</a>
    </div>

    <div class="col">
        <h4>Tài khoản</h4>
        <a href="login.php">Đăng nhập</a>
        <a href="cart.php">Xem giỏ hàng</a>
        
    </div>

<div class="col install">
    <h4>Cài đặt ứng dụng</h4>
    <p>Sắp có trên App Store hoặc Google Play</p>

    <div class="row">
        <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" 
             alt="App Store" style="height:50px;">

        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" 
             alt="Google Play" style="height:50px;">
    </div>

    <p>Cổng thanh toán an toàn</p>

    
    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" style="height:30px;">
    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" style="height:30px;">
</div>

    <div class="copyright">
        <p>© 2024, AUTOMOTO.VN - Khởi động đam mê, bứt phá mọi cung đường</p>
    </div>
</footer>

<!-- Các Script dùng chung -->
<script src="js/brand-slider.js"></script>
<script src="script.js"></script>
<script>
    // JS cho Search Bar (giữ lại từ code của bạn)
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("search-container");
        const icon = document.getElementById("search-icon");
        const input = document.getElementById("search-field");

        if(icon) {
            icon.addEventListener("click", function (e) {
                e.stopPropagation();
                if (!container.classList.contains("active")) {
                    container.classList.add("active");
                    input.focus();
                } else {
                    const keyword = input.value.trim();
                    if (keyword !== "") {
                        window.location.href = "sanpham.php?keyword=" + encodeURIComponent(keyword);
                    }
                }
            });
        }

        document.addEventListener("click", function (e) {
            if (container && !container.contains(e.target)) {
                container.classList.remove("active");
            }
        });
    });
</script>