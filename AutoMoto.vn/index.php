<?php
session_start();

// Kiểm tra an toàn để tránh lỗi Undefined index
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['name'] ?? 'Bạn'; // Nếu không có 'name', sẽ mặc định là 'Khách'
$userRole = $_SESSION['role'] ?? 'customer';
?>


<?php 
// 1. Gọi file cấu hình
require_once 'config/config.php'; 
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    />
    <title>AUTOMOTO.VN</title>
    <link rel="stylesheet" href="style.css" />
  </head>

  <body>
     <section id="header">
      <div class="header-container">
        <!-- Logo -->
        <a href="index.php" class="logo">
          <img src="img/logo.jpg" alt="Logo" />
        </a>

        <!-- Menu -->
        <ul id="navbar">
          <li><a class="active" href="index.php">Trang chủ</a></li>
          <li><a href="sanphamxe.php">Sản phẩm</a></li>
          <li><a  href="tuvan.php">Tư vấn</a></li>
          <li><a href="gioithieu.php">Giới thiệu</a></li>
          
        </ul>

        <!-- Icons -->
        <div class="header-icons">

  <!-- Search -->
<div class="search-wrapper">
  <div class="search-container" id="search-container">
    <input type="text" id="search-field" placeholder="Tìm sản phẩm..." />
    <i class="fa-solid fa-magnifying-glass" id="search-icon"></i>
  </div>
</div>

  <!-- Login -->
<?php include "includes/user_box.php"; ?>

  <!-- Cart -->
  <a href="cart.php" class="cart">
    <i class="fa-solid fa-cart-shopping"></i>
  </a>

</div>
      </div>
    </section>
    <!-- Home page -->
   <section class="hero-banner">
  <video autoplay muted loop playsinline>
    <source src="img/video_sp/BMW_S1000RR_CINEMATIC_4K_1440p.mp4" type="video/mp4" />
    Trình duyệt của bạn không hỗ trợ video.
  </video>

  <!-- lớp phủ tối -->
  <div class="overlay"></div>

  <!-- nội dung -->
  <div class="banner-content">
    <h1>AUTO MOTO</h1>
    <h3>Khởi động đam mê, bứt phá mọi cung đường</h3>
    <a href="sanphamxe.php" class="btn-cta">Xem chi tiết</a>
    <a href="#ads-banner" class="scroll-down right">
    <i class="fas fa-arrow-down"></i>
  </a>
  
  </div>
</section>

<section id="ads-banner" class="section-p1">
    <div class="ads-container">
        <!-- Ảnh lớn bên trái -->
        <div class="ads-box box-large">
            <img src="img\gioithieu\ad1.png" alt="Khuyến mãi lớn">
        
        </div>

        <!-- Cột bên phải chứa 2 ảnh nhỏ -->
        <div class="ads-small-col">
            <div class="ads-box box-small">
                <img src="img\gioithieu\ad2.png" alt="Phụ kiện">
              
            </div>
            <div class="ads-box box-small">
                <img src="img\gioithieu\ad3.webp" alt="Bảo dưỡng">
               
            </div>
        </div>
    </div>
</section>
 
    <!-- Feature -->
    <section id="Feature" class="section-p1">
      <h2 class="feature-title">LÝ DO KHÁCH HÀNG CHỌN CHÚNG TÔI</h2>
      <a href="tuvan.php" class="feature-link">
      <div class="fe-box">
        <img src="img/Feature/customer-service.png" />
        <h6>Tư vấn chọn xe đúng nhu cầu</h6>
      </div>
    </a>
      <div class="fe-box">
        <img src="img/Feature/delivery.png" />
        <h6>Giao xe tận nhà – toàn quốc</h6>
      </div>
      <div class="fe-box">
        <img src="img/Feature/mechanic.png" />
        <h6>Bảo dưỡng chính hãng định kỳ</h6>
      </div>
      <div class="fe-box">
        <img src="img/Feature/payment-method.png" />
        <h6>Trả góp 0% – duyệt nhanh</h6>
      </div>
      <div class="fe-box">
        <img src="img/Feature/scooter.png" />
        <h6>Xe chính hãng 100%</h6>
      </div>
      <div class="fe-box">
        <img src="img/Feature/shield.png" />
        <h6>Bảo hành chính hãng dài hạn</h6>
      </div>
    </section>
    <!-- Explore -->
    <section id="explore" class="section-p1">
      <h2 class="explore-Title">Khám phá các hãng xe</h2>

      <div class="brand-tabs">
        <button class="brand-tab active" data-brand="honda">
          <img src="img/Brand/Honda.svg" alt="Honda" />
        </button>

        <button class="brand-tab" data-brand="yamaha">
          <img src="img/Brand/Yamaha.jpg" alt="Yamaha" />
        </button>

        <button class="brand-tab" data-brand="suzuki">
          <img src="img/Brand/Suzuki.png" alt="Suzuki" />
        </button>

        <button class="brand-tab" data-brand="vinfast">
          <img src="img/Brand/Vinfast.png" alt="VinFast" />
        </button>
      </div>
      <div class="brand-slides">

  <!-- HONDA -->
  <div class="slide active" data-brand="honda">
    <img src="img/slide_brand/honda_moto.jpg" />
    <div class="slide-text">
      <h2>Honda – Bền bỉ theo thời gian</h2>
      <p>Tiết kiệm nhiên liệu, vận hành ổn định, giữ giá cao</p>

    </div>
  </div>

  <!-- YAMAHA -->
  <div class="slide" data-brand="yamaha">
    <img src="img/slide_brand/yamaha_moto.jpg" />
    <div class="slide-text">
      <h2>Yamaha – Bứt phá phong cách</h2>
      <p>Thiết kế thể thao, động cơ mạnh mẽ</p>

    </div>
  </div>

  <!-- SUZUKI -->
  <div class="slide" data-brand="suzuki">
    <img src="img/slide_brand/suzuki_moto.webp" />
    <div class="slide-text">
      <h2>Suzuki – Mạnh mẽ & thực dụng</h2>
      <p>Động cơ bền bỉ, giá tốt trong phân khúc</p>

    </div>
  </div>

  <!-- VINFAST -->
  <div class="slide" data-brand="vinfast">
    <img src="img/slide_brand/vinfast_moto.webp" />
    <div class="slide-text">
      <h2>VinFast – Công nghệ & tương lai</h2>
      <p>Xe điện thông minh, vận hành êm ái</p>

    </div>
  </div>

</div>


      </div>
    </section>


    <!-- Product -->
     <section id="Product1" class="section-p1">
      <h2>Mẫu Xe Nổi Bật</h2>
      <p>Những mẫu xe được khách hàng quan tâm nhất</p>
      <div class="product-container">
        <div class="product-card hot-card">
          <span class="badge-new">New</span>
          <img  class="brand-logo" src="img/Brand/Suzuki.png" />
          <img src="img/products/suzuki/V-Strom 250SX/image1.jpg" class="product-img" />
          <h3>Suzuki V-Strom 250SX</h3>
          <p class="price">132.900.000 VND</p>
          <div class="action">
            <a href="cart_process.php?id=1" class="btn buy">Mua ngay</a>
            <a href="chitietsp.php?id=vstrom250sx" class="btn detail">Xem chi tiết</a>
          </div>
        </div>
         <div class="product-card">
      <span class="badge-new">NEW</span>
      <img class="brand-logo" src="img/Brand/Honda.svg" />
      <img src="img/products/honda/honda future.png" class="product-img" />
      <h3>Honda Future 125FI</h3>
      <p class="price">32.193.818 VND</p>
      <div class="actions">
        <a href="cart_process.php?id=2" class="btn buy">Mua ngay</a>
        <a href="chitietsp.php?id=honda-future-125-fi" class="btn detail">Xem chi tiết</a>
      </div>
    </div>
     <div class="product-card">
      <span class="badge-new">NEW</span>
      <img class="brand-logo" src="img/Brand/Yamaha.jpg" />
      <img src="img/products/yamaha/NVX/Xanh-Duong-G8-1024x684.webp" class="product-img" />
      <h3>NVX</h3>
      <p class="price"> 69.000.000 VNĐ</p>
      <div class="actions">
        <a href="cart_process.php?id=3" class="btn buy">Mua ngay</a>
        <a href="chitietsp.php?id=yamaha-nvx-155-vva" class="btn detail">Xem chi tiết</a>
      </div>
    </div>
     <div class="product-card">
      <span class="badge-new">NEW</span>
      <img class="brand-logo" src="img/Brand/Vinfast.png" />
      <img src="img/products/vinfast/VERO/z7469557256462_b4283ba328ddd6a2168ba92262dde8fc.jpg" class="product-img" />
      <h3>VERO X</h3>
      <p class="price">34.900.000 VNĐ</p>
      <div class="actions">
        <a href="cart_process.php?id=8" class="btn buy">Mua ngay</a>
        <a href="chitietsp.php?id=vinfast-vero-x" class="btn detail">Xem chi tiết</a>
      </div>
    </div>

      </div>
     </section>
     <!-- Motobikes catalog -->
     <section class="category">
      <div class="category-grid">
        <a href="xetayga.php" class="category-item">
          <img src="img/category/morbidelli-sc125re-1-17268038190861970089437-139-0-809-1280-crop-1726804329551349199278.webp" alt="">
          <span class="arrow">→</span>
          <h3>Xe Tay Ga</h3>
        </a>
        <a href="xeso.php" class="category-item">
          <img src="img/category/A91A6597.jpg" alt="">
          <span class="arrow">→</span>
          <h3>Xe số</h3>
        </a>
           <a href="xetaycon.php" class="category-item">
          <img src="img/category/tốc-độ-và-mạnh-mẽ.png" alt="">
          <span class="arrow">→</span>
          <h3>Xe côn tay</h3>
        </a>
           <a href="xephaphoilon.php" class="category-item">
          <img src="img/category/kawasaki-2a-1656348376694.webp" alt="">
          <span class="arrow">→</span>
          <h3>Xe phân khối lớn</h3>
        </a>
           <a href="xedien.php" class="category-item">
          <img src="img/category/d-30.webp" alt="">
          <span class="arrow">→</span>
          <h3>Xe điện</h3>
        </a>
        
    <a href="sanphamxe.php" class="category-item all">
      <span class="plus">+</span>
      <h3>XEM TẤT CẢ</h3>
    </a>

      </div>
     </section>
     <!-- Promotion Ads Section -->


    <script src="js/brand-slider.js"></script>
    <script src="script.js"></script>
    
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
          "sanpham.php?keyword="+ encodeURIComponent(keyword);
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
