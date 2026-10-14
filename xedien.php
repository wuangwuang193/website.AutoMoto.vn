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
          src="img/xemaydien/osakar-rova-so-huu-thiet.webp"
          alt="Xe tay ga"
        />

        <div class="hero-text">
          <h1>Xe máy điện – Xu hướng di chuyển xanh</h1>
          <p>
            Xe máy điện là giải pháp giao thông hiện đại,
            thân thiện với môi trường và tiết kiệm chi phí vận hành.
            Không tiếng ồn, không khí thải, phù hợp cho đô thị.
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
            src="img/xemaydien/cac-loai-dong-co-xe-dien-3_1710919502.jpg"
            alt="Phanh ABS hiện đại"
          />
        </div>
        <div class="feature-text">
          <h2>Vận hành êm ái – Không khí thải</h2>
          <p>
            Động cơ điện giúp xe vận hành mượt mà,
            không gây tiếng ồn và bảo vệ môi trường.
          </p>
          <ul>
            <li>Không xăng dầu</li>
            <li>Không khói thải</li>
            <li>Thân thiện đô thị</li>
          </ul>
        </div>
      </div>

      <!-- Slide 2: Ảnh bên phải, text bên trái (đổi chiều) -->
      <div class="feature reveal-from-right">
        <div class="feature-image">
          <img
            src="img/xemaydien/xe-may-dien-gogoro-osakar-11-afa0718a-85f4-4da1-bcc8-72d9c4ab2ba8.webp"
            alt="Smartkey tiện lợi"
          />
        </div>
        <div class="feature-text">
          <h2>Tiết kiệm chi phí</h2>
        <p>
         Chi phí sạc điện thấp hơn nhiều so với xăng,
            bảo dưỡng đơn giản và ít hỏng vặt.
        </p>
    <ul>
        <li>Chi phí vận hành thấp</li>
        <li>Ít bảo trì</li>
        <li>Tuổi thọ pin cao</li>
</ul>
          </ul>
        </div>
      </div>

     
    
    <section id="Product1" class="section-p1 product-page">
      <h2>Danh mục xe máy điện</h2>
      <div class="product-container">
        <div class="product-card"
          data-brand="VinFast"
          data-type="electric"
          data-price="29700000"
          data-category="xe-dien">
          
          
          <img  class="brand-logo" src="img/Brand/Vinfast.png" />
          <img src="img/products/vinfast/felix.webp" class="product-img" />
          <h3>VinFast Feliz</h3>
          <p class="price">29.700.000 VNĐ</p>
          <div class="action">
            <a href="cart_process.php?id=1" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card"
          data-brand="VinFast"
          data-type="electric"
          data-price="32000000"
          data-category="xe-dien">
          
          
          <img  class="brand-logo" src="img/Brand/Vinfast.png" />
          <img src="img/products/vinfast/vento neo.webp" class="product-img" />
          <h3>VinFast Vento Neo</h3>
          <p class="price">32.000.000 VNĐ</p>
          <div class="action">
            <a href="cart_process.php?id=7" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card"
          data-brand="VinFast"
          data-type="electric"
          data-price="34900000"
          data-category="xe-dien">
          
          
          <img  class="brand-logo" src="img/Brand/Vinfast.png" />
          <img src="img/products/vinfast/VERO/img-banner-info.webp" class="product-img" />
          <h3>VinFast VERO X</h3>
          <p class="price">34.900.000 VNĐ</p>
          <div class="action">
            <a href="cart_process.php?id=8" class="btn buy">Mua ngay</a>
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
