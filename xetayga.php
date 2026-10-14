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
          <div class="user-box">
            <i class="fa-regular fa-user" id="user-icon"></i>
          </div>

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
          src="img/xetayga html/hinh-anh-air-blade-160-5.jpg"
          alt="Xe tay ga"
        />

        <div class="hero-text">
          <h1>Xe tay ga hiện đại</h1>
          <p>
            Xe tay ga (scooter) là dòng xe máy hiện đại, đặc trưng bởi thiết kế
            sàn để chân phẳng, bánh xe nhỏ, động cơ đặt phía sau và hệ thống
            truyền động tự động vô cấp. Xe được ưa chuộng nhờ sự tiện lợi, dễ
            điều khiển, cốp rộng, cùng các công nghệ hiện đại như khóa thông
            minh (smartkey), phanh ABS và động cơ tiết kiệm nhiên liệu.
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
            src="img/xetayga/xe-tay-ga-co-phanh-ABS-1.webp"
            alt="Phanh ABS hiện đại"
          />
        </div>
        <div class="feature-text">
          <h2>Phanh ABS an toàn</h2>
          <p>
            Hệ thống chống bó cứng phanh ABS giúp xe dừng nhanh, ổn định hơn khi
            phanh gấp, đặc biệt trên đường trơn trượt. Công nghệ này đang trở
            thành tiêu chuẩn trên các dòng xe tay ga cao cấp.
          </p>
          <ul>
            <li>An toàn hơn khi phanh khẩn cấp</li>
            <li>Giảm nguy cơ trượt bánh</li>
            <li>Tăng độ bám đường</li>
          </ul>
        </div>
      </div>

      <!-- Slide 2: Ảnh bên phải, text bên trái (đổi chiều) -->
      <div class="feature reveal-from-right">
        <div class="feature-image">
          <img
            src="img/xetayga/xe-may-smartkey-1.webp"
            alt="Smartkey tiện lợi"
          />
        </div>
        <div class="feature-text">
          <h2>Smartkey thông minh</h2>
          <p>
            Khóa thông minh Smartkey cho phép khởi động xe mà không cần chìa,
            chống trộm hiệu quả với báo động và định vị. Tiện lợi cho cuộc sống
            hiện đại.
          </p>
          <ul>
            <li>Khởi động bằng nút bấm</li>
            <li>Tìm xe trong bãi đỗ</li>
            <li>Chống sao chép chìa khóa</li>
          </ul>
        </div>
      </div>

      <!-- Bạn có thể thêm slide 3, 4... tương tự -->
    
    <section id="Product1" class="section-p1 product-page">
      <h2>Danh mục xe tay ga</h2>
      <div class="product-container">
        <div class="product-card" data-brand="Suzuki" data-type="petrol" data-price="132.900.000" data-category="xe Adventure">
          
          
          <img  class="brand-logo" src="img/Brand/Yamaha.jpg" />
          <img src="img/products/yamaha/NVX/Xanh-Duong-G8-1024x684.webp" class="product-img" />
          <h3>NVX</h3>
          <p class="price">69.000.000 VND</p>
          <div class="action">
            <a href="cart_process.php?id=3" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card" data-brand="Suzuki" data-type="petrol" data-price="132.900.000" data-category="xe Adventure">
          
          
          <img  class="brand-logo" src="img/Brand/Yamaha.jpg" />
          <img src="img/products/yamaha/Grande.webp" class="product-img" />
          <h3>Grande</h3>
          <p class="price">51.251.000 VND</p>
          <div class="action">
            <a href="cart_process.php?id=12" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card" data-brand="Suzuki" data-type="petrol" data-price="132.900.000" data-category="xe Adventure">
          
          
          <img  class="brand-logo" src="img/Brand/Yamaha.jpg" />
          <img src="img/products/yamaha/lexi.jpg" class="product-img" />
          <h3>LEXI</h3>
          <p class="price">48.500.000 VND</p>
          <div class="action">
            <a href="cart_process.php?id=14" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card" data-brand="Suzuki" data-type="petrol" data-price="132.900.000" data-category="xe Adventure">
          
          
          <img  class="brand-logo" src="img/Brand/Yamaha.jpg" />
          <img src="img/products/yamaha/freego.webp" class="product-img" />
          <h3>Freego</h3>
          <p class="price">30.633.000 VND</p>
          <div class="action">
            <a href="cart_process.php?id=16" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card" data-brand="Suzuki" data-type="petrol" data-price="132.900.000" data-category="xe Adventure">
          
          
          <img  class="brand-logo" src="img/Brand/Yamaha.jpg" />
          <img src="img/products/yamaha/Janus.webp" class="product-img" />
          <h3>Janus</h3>
          <p class="price">32.891.000 VND</p>
          <div class="action">
            <a href="cart_process.php?id=17" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card" data-brand="Suzuki" data-type="petrol" data-price="132.900.000" data-category="xe Adventure">
          
          
          <img  class="brand-logo" src="img/Brand/Honda.svg" />
          <img src="img/products/honda/airblade.png" class="product-img" />
          <h3>Air Blade</h3>
          <p class="price">58.590.000 VND</p>
          <div class="action">
            <a href="cart_process.php?id=19" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

         <div class="product-card" data-brand="Suzuki" data-type="petrol" data-price="132.900.000" data-category="xe Adventure">
          
          
          <img  class="brand-logo" src="img/Brand/Suzuki.png" />
          <img src="img/products/suzuki/impulse 125.webp" class="product-img" />
          <h3>Impulse 125</h3>
          <p class="price">24.000.000 VND</p>
          <div class="action">
            <a href="cart_process.php?id=22" class="btn buy">Mua ngay</a>
            <a href="#" class="btn detail">Xem chi tiết</a>
          </div>
        </div>

        <div class="product-card" data-brand="Suzuki" data-type="petrol" data-price="132.900.000" data-category="xe Adventure">
          
          
          <img  class="brand-logo" src="img/Brand/Honda.svg" />
          <img src="img/products/honda/vision.png" class="product-img" />
          <h3>Vision</h3>
          <p class="price">36.612.000 VND</p>
          <div class="action">
            <a href="cart_process.php?id=23" class="btn buy">Mua ngay</a>
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
