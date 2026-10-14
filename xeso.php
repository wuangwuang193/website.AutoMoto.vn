<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sản phẩm | AUTOMOTO</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="xetayga.css" />
  </head>
  <body>
    <!-- HEADER (giữ nguyên như bạn) -->
     <section id="header">
      <div class="header-container">
        <!-- Logo -->
        <a href="index.php" class="logo">
          <img src="img/logo.jpg" alt="Logo" />
        </a>

        <!-- Menu -->
        <ul id="navbar">
          <li><a href="index.php">Trang chủ</a></li>
          <li><a href="sanphamxe.php">Sản phẩm</a></li>
          <li><a href="tuvan.php">Tư vấn</a></li>
          <li><a href="gioithieu.php">Giới thiệu</a></li>
          
        </ul>

        <!-- Icons -->
        <div class="header-icons">
          <!-- Search -->
          <div class="search-wrapper">
            <div class="search-container" id="search-container">
              <input
                type="text"
                id="search-field"
                placeholder="Tìm sản phẩm..."
              />
              <i class="fa-solid fa-magnifying-glass" id="search-icon"></i>
            </div>
          </div>

          <!-- Login -->
<?php include "includes/user_box.php"; ?>

          <!-- Cart -->
          <a href="cart.html" class="cart">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="cart-count">1</span>
          </a>
        </div>
      </div>
    </section>
    
    <section class="hero-scooter">
      <div class="hero-content">
        <img
          src="img/xeso/JupiterFinnBPC1_Gallery_05.webp"
          alt="Xe tay ga"
        />

        <div class="hero-text">
          <h1>Xe số bền bỉ – Tiết kiệm nhiên liệu</h1>
          <p>
            Xe số (Underbone) là dòng xe máy phổ biến tại Việt Nam, nổi bật với thiết kế
            gọn nhẹ, động cơ bền bỉ và khả năng tiết kiệm nhiên liệu vượt trội.
            Xe sử dụng hộp số bán tự động hoặc côn tay, phù hợp cho học sinh, sinh viên,
            người lao động và những ai cần phương tiện di chuyển linh hoạt hằng ngày.
          </p>
          <a href="#Product1" class="btn-scroll">Xem sản phẩm</a>
        </div>
      </div>
    </section>
    <!-- Phần slide thông tin + ảnh khi scroll xuống -->
    <section class="features-section">
      <!-- Slide 1: Ảnh bên trái, text bên phải -->
      <div class="feature reveal-from-left">
        <div class="feature-image">
          <img
            src="img/xeso/dong-ho-xe-wave-alpha-110.jpg"
            alt="Phanh ABS hiện đại"
          />
        </div>
        <div class="feature-text">
          <h2>Tiết kiệm nhiên liệu vượt trội</h2>
          <p>
            Xe số được trang bị động cơ dung tích nhỏ nhưng hiệu suất cao,
            giúp tiết kiệm xăng tối đa khi di chuyển hằng ngày.
          </p>
          <ul>
            <li>Mức tiêu hao thấp</li>
            <li>Phù hợp di chuyển đường dài</li>
            <li>Chi phí vận hành thấp</li>
          </ul>
        </div>
      </div>

      <!-- Slide 2: Ảnh bên phải, text bên trái (đổi chiều) -->
      <div class="feature reveal-from-right">
        <div class="feature-image">
          <img
            src="img/xeso/dong-co-wave-alpha-110.jpg"
            alt="Smartkey tiện lợi"
          />
        </div>
        <div class="feature-text">
          <h2>Động cơ bền bỉ</h2>
        <p>
         Xe số nổi tiếng với độ bền cao, ít hư hỏng vặt và dễ sửa chữa.
        Phù hợp với nhiều điều kiện đường sá khác nhau.
        </p>
    <ul>
        <li>Hoạt động ổn định</li>
        <li>Dễ bảo trì</li>
        <li>Tuổi thọ cao</li>
</ul>
          </ul>
        </div>
      </div>

      <!-- Bạn có thể thêm slide 3, 4... tương tự -->
    
    <section id="Product1" class="section-p1 product-page">
      <h2>Danh mục xe số</h2>
      <div class="product-container">
        <div class="product-card" 
          data-brand="Honda" 
          data-type="petrol" 
          data-price="18900000" 
          data-category="xe so">
          
          
          <img  class="brand-logo" src="img/Brand/Honda.svg" />
          <img src="img/products/honda/wave alpha.png" class="product-img" />
          <h3>Honda Wave Alpha 110</h3>
          <p class="price">18.900.000 VND</p>
          <div class="action">
            <a href="cart_process.php?id=4" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card" 
          data-brand="Honda" 
          data-type="petrol" 
          data-price="31000000" 
          data-category="xe so">
          
          
          <img  class="brand-logo" src="img/Brand/Honda.svg" />
          <img src="img/products/honda/honda future.png" class="product-img" />
          <h3>Honda Future 125 FI</h3>
          <p class="price">31.000.000 VND</p>
          <div class="action">
            <a href="cart_process.php?id=2" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card" 
          data-brand="Honda" 
          data-type="petrol" 
          data-price="21943637" 
          data-category="xe so">
          
          
          <img  class="brand-logo" src="img/Brand/Honda.svg" />
          <img src="img/products/honda/Blade.webp" class="product-img" />
          <h3>Honda Blade 110</h3>
          <p class="price">21.943.637 VNĐ</p>
          <div class="action">
            <a href="cart_process.php?id=6" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card" 
          data-brand="Yamaha" 
          data-type="petrol" 
          data-price="22190000" 
          data-category="xe so">
          
          <img  class="brand-logo" src="img/Brand/Yamaha.jpg" />
          <img src="img/products/yamaha/sirus.webp" class="product-img" />
          <h3>Yamaha Sirius</h3>
          <p class="price">22.190.000 VNĐ</p>
          <div class="action">
            <a href="cart_process.php?id=20" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card" 
          data-brand="Honda" 
          data-type="petrol" 
          data-price="86292000" 
          data-category="xe so">
          
          
          <img  class="brand-logo" src="img/Brand/Honda.svg" />
          <img src="img/products/honda/super cup.png" class="product-img" />
          <h3>Honda Super Cub</h3>
          <p class="price">86.292.000 VNĐ</p>
          <div class="action">
            <a href="cart_process.php?id=21" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

      </div>
    </section>
    <script>
  function revealOnScroll() {
    const reveals = document.querySelectorAll(".feature");
    const windowHeight = window.innerHeight;
    const revealPoint = 150; // hiện sớm hơn 1 chút

    reveals.forEach((reveal) => {
      const revealTop = reveal.getBoundingClientRect().top;

      if (revealTop < windowHeight - revealPoint) {
        reveal.classList.add("active");
      } else {
        // Muốn ẩn lại khi scroll lên thì giữ dòng này
        reveal.classList.remove("active");
      }
    });
  }

  window.addEventListener("scroll", revealOnScroll);
  revealOnScroll(); // chạy lần đầu
</script>
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

  </body>
</html>
