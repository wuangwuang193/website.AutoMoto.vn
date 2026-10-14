<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    />
    <title>Tư vấn chọn xe | AUTOMOTO</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="tuvan.css" />
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
          <li><a href="index.php">Trang chủ</a></li>
          <li><a href="sanphamxe.php">Sản phẩm</a></li>
          <li><a class="active" href="tuvan.php">Tư vấn</a></li>
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
          <a href="cart.php" class="cart">
            <i class="fa-solid fa-cart-shopping"></i>
            
          </a>
        </div>
      </div>
    </section>
    <!-- HERO -->
    <section class="tuvan-hero">
      <h1>Tư vấn chọn xe phù hợp</h1>
      <p>Chọn xe đúng nhu cầu – đúng ngân sách – đúng phong cách</p>
    </section>

    <!-- FORM -->
    <section class="tuvan-form">
      <div class="card">
        <h2>Thông tin của bạn</h2>

        <form id="tuvanForm">
          <label>Mức giá</label>
          <select id="gia">
            <option>Dưới 30 triệu</option>
            <option>30 – 50 triệu</option>
            <option>Trên 50 triệu</option>
          </select>

          <label>Nhu cầu sử dụng</label>
          <select id="nhuCau">
            <option>Đi học / đi làm</option>
            <option>Đi phượt</option>
            <option>Gia đình</option>
          </select>

          <!-- Câu hỏi bổ sung (tùy chọn) -->
          <label>Giới tính</label>
          <select id="gioiTinh">
            <option>Nam</option>
            <option>Nữ</option>
          </select>

          <label>Bạn ưu tiên điều gì nhất?</label>
          <select id="uuTien">
            <option>Tiết kiệm xăng</option>
            <option>Thiết kế đẹp, thời trang</option>
            <option>Bền bỉ, ít hỏng</option>
            <option>Cốp rộng, tiện lợi</option>
          </select>

          <button type="submit">Nhận tư vấn ngay</button>
        </form>
      </div>
    </section>

    <!-- KẾT QUẢ TƯ VẤN -->
    <section class="tuvan-result" id="ketQua" style="display: none">
      <div class="card">
        <h2>Gợi ý xe phù hợp cho bạn</h2>
        <div class="result-grid" id="gridKetQua"></div>
      </div>
    </section>

    <!-- MODULE SO SÁNH NHANH -->
    <section class="compare-section">
      <div class="card">
        <h2>So sánh nhanh xe phổ biến dưới 30 triệu</h2>
        <div class="carousel">
          <img src="img/products/honda/vison.jpg" alt="Honda Vision" />
          <img src="img/products/yamaha/Janus.webp" alt="Yamaha Janus" />
          <img src="img/products/honda/wave alpha.png" alt="Honda Wave Alpha" />
          <img src="img/products/yamaha/sirus.webp" alt="Yamaha Sirius" />
        </div>
        <p style="text-align: center; margin-top: 1rem">
          Các mẫu hot: Honda Vision, Yamaha Janus, Honda Wave Alpha, Yamaha
          Sirius...
        </p>
      </div>
    </section>

    <!-- LỢI ÍCH -->
    <section class="benefits">
      <div class="card">
        <h2>Lợi ích khi chọn xe đi học/đi làm</h2>
        <ul>
          <li><strong>Tiết kiệm xăng</strong>: Chỉ 1.6-2L/100km</li>
          <li><strong>Dễ lái, gọn nhẹ</strong>: Phù hợp đường đô thị</li>
          <li><strong>Thiết kế trẻ trung</strong>: Nhiều màu sắc thời trang</li>
          <li><strong>Bảo dưỡng rẻ</strong>: Phụ tùng dễ tìm</li>
        </ul>
      </div>
    </section>


    <script src="tuvan.js"></script>
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
