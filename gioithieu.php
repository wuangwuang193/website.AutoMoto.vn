<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu & Liên hệ | AUTOMOTO</title>
    
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css" />
    <style>
        
        /* === LAYOUT TRANG GIỚI THIỆU === */
        .new-about-hero {
            height: 450px;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('img/gioithieu/baner1gioithieu.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }
        .split-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .split-item {
            display: flex;
            align-items: center;
            gap: 60px;
            margin-bottom: 100px;
            flex-wrap: wrap;
        }

        .split-item:nth-child(even) { flex-direction: row-reverse; }

        .split-img, .split-text { flex: 1; min-width: 300px; }

        .split-img img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 15px 15px 0 var(--primary-red);
        }

        .split-text h2 { font-size: 2.5rem; margin-bottom: 20px; color: #222; }
        .split-text p { font-size: 1.1rem; line-height: 1.6; color: #555; }

        /* Stats */
        .stats-bar {
            background: #111;
            color: white;
            padding: 80px 0;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .stat-box i { font-size: 3rem; color: var(--primary-red); margin-bottom: 15px; }
        .stat-box h3 { font-size: 2.5rem; margin: 0; }

        /* Contact Section */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
        }

        .contact-card {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .form-input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #eee;
            background: #f9f9f9;
            border-radius: 8px;
            font-size: 1rem;
        }

        .btn-red {
            background: var(--primary-red);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            text-transform: uppercase;
        }

        .btn-red:hover { background: var(--primary-red-dark); transform: translateY(-3px); }

        @media (max-width: 768px) {
            #header { padding: 0 20px; }
            .split-item { gap: 30px; text-align: center; }
            .contact-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header id="header">
        <div class="header-container">
            <a href="index.php" class="logo"><img src="img/logo.jpg" alt="Logo"></a>
            
            <ul id="navbar">
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="sanphamxe.php">Sản phẩm</a></li>
                <li><a href="tuvan.php">Tư vấn</a></li>
                <li><a class="active" href="gioithieu.php">Giới thiệu</a></li>
                
            </ul>

            <div class="header-icons">
                <div class="search-wrapper">
                    <div class="search-container" id="search-container">
                        <input type="text" id="search-field" placeholder="Tìm kiếm xe...">
                        <i class="fa-solid fa-magnifying-glass" id="search-icon"></i>
                    </div>
                </div>

                <?php include "includes/user_box.php"; ?>
                
                <a href="cart.php" class="cart">
                    <i class="fa-solid fa-cart-shopping"></i>
                    
                </a>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->

        <div class="new-about-hero">
            <div>
            
                <h1 style="font-size: 4rem; margin-bottom: 10px; font-weight: 900; color: var(--accent-yellow);">GIỚI THIỆU AUTOMOTO</h1>
                
                
                <p style="font-size: 1.5rem; opacity: 0.9; color: #ffffff;">Đồng hành cùng bạn trên mọi cung đường</p>
            </div>
        </div>
    <!-- NỘI DUNG CHÍNH -->
    <main class="split-container">
        <div class="split-item">
            <div class="split-img">
                <img src="img\gioithieu\anhbanner2.webp" alt="Về chúng tôi">
            </div>
            <div class="split-text">
                <h2>Câu chuyện của chúng tôi</h2>
                <p>AutoMoto không chỉ là một nền tảng bán xe. Chúng tôi là những người đam mê tốc độ, mong muốn mang đến cho người dùng Việt Nam một không gian mua sắm minh bạch, hiện đại và tin cậy tuyệt đối.</p>
            </div>
        </div>

        <div class="split-item">
            <div class="split-img">
                <img src="img\gioithieu\tuvan.png" alt="Công nghệ">
            </div>
            <div class="split-text">
                <h2>Hệ thống tư vấn thông minh</h2>
                <p>Bạn phân vân giữa hàng trăm mẫu xe? Hệ thống lọc dữ liệu thông minh của chúng tôi sẽ dựa trên nhu cầu, chiều cao và ngân sách của bạn để đưa ra gợi ý hoàn hảo nhất.</p>
                <br>
                <a href="tuvan.php" class="btn-red" style="text-decoration: none;">Trải nghiệm tư vấn</a>
            </div>
        </div>
    </main>

    <!-- THỐNG KÊ -->
    <div class="stats-bar">
        <div class="stat-box">
            <i class="fa-solid fa-motorcycle"></i>
            <h3>20+</h3>
            <p>Mẫu xe đa dạng</p>
        </div>
        <div class="stat-box">
            <i class="fa-solid fa-users"></i>
            <h3>500+</h3>
            <p>Khách hàng hài lòng</p>
        </div>
        <div class="stat-box">
            <i class="fa-solid fa-medal"></i>
            <h3>5 Năm</h3>
            <p>Kinh nghiệm thị trường</p>
        </div>
    </div>

    <!-- LIÊN HỆ -->
    <section class="contact-grid">
        <div class="contact-info">
            <h2 style="font-size: 2.5rem; margin-bottom: 20px;">Kết nối với chúng tôi</h2>
            <p><i class="fa-solid fa-location-dot" style="color:red; width: 25px;"></i> 123 Đường 3/2, Quận Ninh Kiều, Cần Thơ</p>
            <p><i class="fa-solid fa-phone" style="color:red; width: 25px;"></i> Hotline: 1900 1234</p>
            <p><i class="fa-solid fa-envelope" style="color:red; width: 25px;"></i> Email: support@automoto.vn</p>
            
            <div style="margin-top: 30px; height: 300px; overflow: hidden; border-radius: 15px;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3928.84!2d105.76!3d10.03!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDAxJzQ4LjkiTiAxMDXCsDQ1JzM2LjAiRQ!5e0!3m2!1svi!2s!4v1625540000000" width="100%" height="100%" style="border:0;" allowfullscreen=""></iframe>
            </div>
        </div>
    

<!-- MODULE THAY THẾ: HỆ THỐNG SHOWROOM -->
        <div class="contact-card">
            <h3 style="margin-bottom: 25px; border-left: 5px solid var(--primary-red); padding-left: 15px;">Hệ Thống Showroom</h3>
            
            <div class="showroom-item" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px dashed #ddd;">
                <h4 style="font-size: 1.2rem; color: var(--primary-red); margin-bottom: 5px;"><i class="fa-solid fa-house-chimney"></i> Chi nhánh Cần Thơ (Trụ sở)</h4>
                <p style="margin: 0; font-size: 0.95rem;">123 Đường 3/2, P. Xuân Khánh, Q. Ninh Kiều</p>
                <p style="margin: 0; font-size: 0.95rem;">Hotline: 0901.234.567</p>
            </div>

            <div class="showroom-item" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px dashed #ddd;">
                <h4 style="font-size: 1.2rem; color: var(--primary-red); margin-bottom: 5px;"><i class="fa-solid fa-house-chimney"></i> Chi nhánh TP. Hồ Chí Minh</h4>
                <p style="margin: 0; font-size: 0.95rem;">456 Lê Trọng Tấn, P. Tây Thạnh, Q. Tân Phú</p>
                <p style="margin: 0; font-size: 0.95rem;">Hotline: 0908.888.999</p>
            </div>

            <div class="work-time" style="background: #fff5f5; padding: 20px; border-radius: 10px; border: 1px solid #ffe0e0;">
                <h4 style="margin-bottom: 10px;"><i class="fa-solid fa-clock"></i> Giờ làm việc</h4>
                <ul style="list-style: none; padding: 0;">
                    <li style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Thứ 2 - Thứ 7:</span>
                        <strong>08:00 - 21:00</strong>
                    </li>
                    <li style="display: flex; justify-content: space-between;">
                        <span>Chủ nhật:</span>
                        <strong>08:30 - 17:30</strong>
                    </li>
                </ul>
            </div>

            <div class="social-connect" style="margin-top: 25px; text-align: center;">
                <p style="margin-bottom: 15px; font-weight: bold;">Kết nối trực tuyến qua:</p>
                <div style="display: flex; justify-content: center; gap: 15px;">
                    <a href="#" style="width: 45px; height: 45px; background: #3b5998; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.2rem;"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" style="width: 45px; height: 45px; background: #0084ff; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.2rem;"><i class="fa-solid fa-comment-dots"></i></a>
                    <a href="#" style="width: 45px; height: 45px; background: #ff0000; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.2rem;"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#" style="width: 45px; height: 45px; background: #000; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.2rem;"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
        </div>
</section>
<!-- CHÍNH SÁCH BẢO MẬT -->
<section class="privacy-section" id="chinh-sach">
    <div class="privacy-container">
        <div class="privacy-title">
            <h2>Chính Sách Bảo Mật</h2>
            <p style="margin-top: 15px; color: #777;">Sự an toàn thông tin của bạn là ưu tiên hàng đầu của AUTOMOTO</p>
        </div>

        <div class="privacy-grid">
            <div class="privacy-item">
                <i class="fa-solid fa-user-shield"></i>
                <h4>Thu thập thông tin</h4>
                <p>Chúng tôi thu thập thông tin tên, số điện thoại và địa chỉ khi bạn đăng ký tư vấn hoặc mua xe để phục vụ mục đích hỗ trợ tốt nhất.</p>
            </div>

            <div class="privacy-item">
                <i class="fa-solid fa-lock"></i>
                <h4>Bảo mật dữ liệu</h4>
                <p>Mọi dữ liệu cá nhân được mã hóa qua giao thức SSL và lưu trữ trong hệ thống máy chủ bảo mật cao, chống lại các truy cập trái phép.</p>
            </div>

            <div class="privacy-item">
                <i class="fa-solid fa-handshake-slash"></i>
                <h4>Cam kết không chia sẻ</h4>
                <p>AUTOMOTO cam kết không bán, trao đổi hoặc chia sẻ thông tin cá nhân của khách hàng cho bất kỳ bên thứ ba nào vì mục đích thương mại.</p>
            </div>

            <div class="privacy-item">
                <i class="fa-solid fa-user-check"></i>
                <h4>Quyền của khách hàng</h4>
                <p>Bạn có toàn quyền yêu cầu chúng tôi kiểm tra, cập nhật hoặc xóa bỏ thông tin cá nhân của mình khỏi hệ thống bất cứ lúc nào.</p>
            </div>
        </div>

        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-style: italic; color: #888; font-size: 0.9rem; text-align: center;">
            * Chính sách này được cập nhật lần cuối vào ngày 01/01/2024. Mọi thay đổi sẽ được thông báo trực tiếp trên website.
        </div>
    </div>
</section>
 
 <script>
      document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("search-container");
        const icon = document.getElementById("search-icon");
        const input = document.getElementById("search-field");

        icon.addEventListener("click", function (e) {
          e.stopPropagation();

          if (!container.classList.contains("active")) {
            container.classList.add("active");
            input.focus();
          } else {
            const keyword = input.value.trim();
            if (keyword !== "") {
              window.location.href =
                "sanpham.php?keyword=" + encodeURIComponent(keyword);
            }
          }
        });

        // Nhấn Enter để tìm
        input.addEventListener("keydown", function (e) {
          if (e.key === "Enter") {
            const keyword = this.value.trim();
            if (keyword !== "") {
              window.location.href =
                "sanpham.php?keyword=" + encodeURIComponent(keyword);
            }
          }
        });

        // Click ngoài → thu lại
        document.addEventListener("click", function (e) {
          if (!container.contains(e.target)) {
            container.classList.remove("active");
            input.value = "";
          }
        });
      });
    </script>
  <?php include 'includes/footer.php'; ?>
</body>
</html>