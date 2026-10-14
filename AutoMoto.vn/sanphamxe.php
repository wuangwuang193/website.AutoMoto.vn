<?php
// 1. Kết nối cơ sở dữ liệu
include "includes/db_connect.php"; 

// 2. Lấy danh sách xe từ Database
$sql = "SELECT * FROM bikes ORDER BY id DESC";
$result = $conn->query($sql);
$bikes = $result->fetch_all(MYSQLI_ASSOC);
// Tạo một danh sách ánh xạ tên thương hiệu và tên file thực tế
$brand_logos = [
    'Honda'   => 'Honda.svg',
    'Suzuki'  => 'Suzuki.png',
    'VinFast' => 'Vinfast.png',
    'Yamaha'  => 'Yamaha.jpg'
];
?>



<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sản phẩm | AUTOMOTO</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="sanpham.css" />
  </head>
  <body>
    <!-- HEADER -->
    <section id="header">
      <div class="header-container">
        <a href="index.php" class="logo"><img src="img/logo.jpg" alt="Logo" /></a>
        <ul id="navbar">
          <li><a href="index.php">Trang chủ</a></li>
          <li><a class="active" href="sanphamxe.php">Sản phẩm</a></li>
          <li><a href="tuvan.php">Tư vấn</a></li>
          <li><a href="gioithieu.php">Giới thiệu</a></li>
          
        </ul>
        <div class="header-icons">
          <div class="search-wrapper">
            <div class="search-container" id="search-container">
              <input type="text" id="search-field" placeholder="Tìm sản phẩm..." />
              <i class="fa-solid fa-magnifying-glass" id="search-icon"></i>
            </div>
          </div>
          <?php include "includes/user_box.php"; ?>
          <a href="cart.php" class="cart">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="cart-count">0</span>
          </a>
        </div>
      </div>
    </section>

    <!-- BANNER VIDEO -->
    <section class="hero-banner">
      <video autoplay muted loop playsinline><source src="img/video_sp/banner_video.mp4" type="video/mp4" /></video>
      <div class="overlay"></div>
      <div class="banner-content">
        <h1>Khám Phá Hành Trình</h1>
        <h3>AUTO MOTO – Chọn xe chất, vững mọi hành trình</h3>
        <a href="#Product1" class="btn-cta">Khám phá ngay</a>
      </div>
    </section>

    <!-- FILTER BAR -->
    <div class="filter-bar compact">
      <div class="filter-container">
        <select id="filter-brand">
          <option value="">Tất cả hãng</option>
          <option value="Honda">Honda</option>
          <option value="Yamaha">Yamaha</option>
          <option value="Suzuki">Suzuki</option>
          <option value="Vinfast">VinFast</option>
        </select>
        <select id="filter-type">
          <option value="">Tất cả loại xe</option>
          <option value="xe số">Xe số</option>
          <option value="xe tay ga">Xe tay ga</option>
          <option value="xe tay côn">Xe tay côn</option>
          <option value="xe điện">Xe điện</option>
          <option value="xe phân khối lớn">Xe phân khối lớn</option>
          <option value="xe mô tô">Xe mô tô</option>
        </select>
        <select id="filter-price">
          <option value="">Tất cả mức giá</option>
          <option value="low">Dưới 30 triệu</option>
          <option value="mid">30–100 triệu</option>
          <option value="high">Trên 100 triệu</option>
        </select>
      </div>
    </div>

    <!-- DANH MỤC SẢN PHẨM -->
    <section id="Product1" class="section-p1 product-page">
      <h2>Danh mục xe</h2>
      <div class="product-container" id="product-list">
        
        <?php if (count($bikes) > 0): ?>
          <?php foreach ($bikes as $b): ?>
            <?php 
                // XỬ LÝ LẤY ẢNH ĐẦU TIÊN
                $img_list = explode(',', $b['images'] ?? ''); // Tách chuỗi ảnh
                $first_image = !empty(trim($img_list[0])) ? trim($img_list[0]) : $b['main_image'];
                // Nếu đường dẫn trong DB không có sẵn "img/products/", bạn có thể nối thêm vào ở đây
            ?>
            <div class="product-card" 
                 data-brand="<?= htmlspecialchars($b['brand']) ?>" 
                 data-category="<?= htmlspecialchars($b['category']) ?>" 
                 data-price="<?= $b['price'] ?>">
              
             <?php 
    // Lấy tên thương hiệu từ database
    $brand_name = $b['brand']; 
    
    // Kiểm tra xem thương hiệu này có trong danh sách mapping không
    // Nếu có thì lấy tên file tương ứng, nếu không thì hiện default.png
    $logo_file = isset($brand_logos[$brand_name]) ? $brand_logos[$brand_name] : 'default.png';
?>

<img class="brand-logo" 
     src="img/Brand/<?= $logo_file ?>" 
     onerror="this.src='img/Brand/default.png'" 
     alt="Logo <?= $brand_name ?>" />
              
              <!-- Hiển thị ảnh đầu tiên -->
              <img src="<?= htmlspecialchars($first_image) ?>" class="product-img" alt="<?= $b['name'] ?>" onerror="this.src='img/no-image.png'"/>
              
              <h3><?= htmlspecialchars($b['name']) ?></h3>
              <p class="price"><?= number_format($b['price'], 0, ',', '.') ?> VND</p>
              
              <div class="actions">
                <?php if ($b['stock'] > 0): ?>
                    <a href="cart_process.php?id=<?= $b['id'] ?>" class="btn buy">Mua ngay</a>
                <?php else: ?>
                    <a href="javascript:void(0)" class="btn buy" style="background: #999; cursor: not-allowed; border-color: #999;">Hết hàng</a>
                <?php endif; ?>
                
                <a href="chitietsp.php?id=<?= $b['slug'] ?>" class="btn detail">Xem chi tiết</a>
              </div>

              <?php if ($b['stock'] <= 0): ?>
                <span style="position: absolute; top: 10px; right: 10px; background: rgba(214, 40, 40, 0.8); color: white; padding: 3px 10px; border-radius: 4px; font-size: 11px; font-weight: bold;">TẠM HẾT</span>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p id="no-products" style="text-align: center; width: 100%; padding: 50px;">Hiện chưa có sản phẩm nào.</p>
        <?php endif; ?>

      </div>
    </section>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
    const filterBrand = document.getElementById('filter-brand');
    const filterType = document.getElementById('filter-type');
    const filterPrice = document.getElementById('filter-price');
    const cards = document.querySelectorAll('.product-card');

    if (!filterBrand || !filterType || !filterPrice) return;

    function filterProducts() {
        const brandVal = filterBrand.value.toLowerCase();
        const typeVal = filterType.value.toLowerCase();
        const priceVal = filterPrice.value;

        cards.forEach(card => {
            const cardBrand = card.dataset.brand.toLowerCase();
            const cardType = card.dataset.category.toLowerCase();
            const cardPrice = parseFloat(card.dataset.price);

            let matchBrand = !brandVal || cardBrand === brandVal;
            let matchType = !typeVal || cardType === typeVal;
            let matchPrice = true;

            if (priceVal === "low") matchPrice = cardPrice < 30000000;
            else if (priceVal === "mid") matchPrice = cardPrice <= 100000000;
            else if (priceVal === "high") matchPrice = cardPrice > 100000000;

            card.style.display = (matchBrand && matchType && matchPrice) ? "block" : "none";
        });
    }

    filterBrand.addEventListener('change', filterProducts);
    filterType.addEventListener('change', filterProducts);
    filterPrice.addEventListener('change', filterProducts);
});

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