<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chi tiết xe | AUTOMOTO</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="style.css" />
    <style>
      body { font-family: "Roboto Condensed", sans-serif; background: #f8f9fa; margin: 0; }
      .hero-banner { position: relative; height: 70vh; min-height: 400px; background-size: cover; background-position: center; }
      .hero-banner .overlay { position: absolute; inset: 0; background: rgba(0, 0, 0, 0.6); }
      .banner-content { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white; }
      .banner-content h1 { font-size: 3.5rem; margin-bottom: 10px; text-transform: uppercase; font-weight: 900; }
      .product-detail { max-width: 1200px; margin: 60px auto; padding: 0 30px; }
      .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
      .main-image { position: relative; width: 100%; height: 500px; background: #fff; display: flex; align-items: center; justify-content: center; border-radius: 12px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); overflow: hidden; }
      .main-image img { max-width: 90%; max-height: 90%; object-fit: contain; transition: opacity 0.3s ease; }
      .nav-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 45px; height: 45px; border-radius: 50%; border: none; background: rgba(0, 0, 0, 0.5); color: white; cursor: pointer; transition: 0.3s; z-index: 10; }
      .nav-btn:hover { background: #d32f2f; }
      .prev { left: 15px; } .next { right: 15px; }
      h2 { color: #d32f2f; text-transform: uppercase; margin-bottom: 20px; }
      .features-list { padding-left: 20px; line-height: 1.8; }
      .spec-table { width: 100%; border-collapse: collapse; margin-top: 30px; background: #fff; }
      .spec-table th, .spec-table td { border: 1px solid #ddd; padding: 15px; text-align: left; }
      .spec-table th { background: #f1f1f1; width: 35%; font-weight: 700; text-transform: uppercase; font-size: 14px; }
      .price-buy-section { margin-top: 25px; padding: 30px; background: #fff; border-radius: 20px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); text-align: center; }
      .main-price { font-size: 3rem; font-weight: 900; color: #d32f2f; display: block; margin-bottom: 5px; }
      .action-buttons { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
      .buy-now-btn, .consult-btn { padding: 15px 45px; font-size: 1.2rem; border-radius: 50px; text-decoration: none; transition: 0.3s; font-weight: 900; text-transform: uppercase; }
      .buy-now-btn { background: #d32f2f; color: white; box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3); }
      .buy-now-btn:hover { background: #b71c1c; transform: translateY(-3deg); }
      .consult-btn { background: #333; color: white; }
      @media (max-width: 992px) { .grid-2 { grid-template-columns: 1fr; } .main-image { height: 350px; } .banner-content h1 { font-size: 2.5rem; } }
    </style>
  </head>

  <body>
    <section id="header">
      <div class="header-container">
        <a href="index.php" class="logo"><img src="img/logo.jpg" alt="Logo" /></a>
        <ul id="navbar">
          <li><a href="index.php">Trang chủ</a></li>
          <li><a class="active" href="sanphamxe.php">Sản phẩm</a></li>
          <li><a  href="tuvan.php">Tư vấn</a></li>
          <li><a href="gioithieu.php">Giới thiệu</a></li>
          
        </ul>
        <div class="header-icons">
          <?php include "includes/user_box.php"; ?>
          <a href="cart.php" class="cart"><i class="fa-solid fa-cart-shopping"></i></a>
        </div>
      </div>
    </section>

    <!-- HERO BANNER -->
    <div class="hero-banner" id="heroBanner">
      <div class="overlay"></div>
      <div class="banner-content">
        <h1 id="productName">Đang tải...</h1>
      </div>
    </div>

    <section class="product-detail">
      <div class="grid-2">
        <!-- SLIDER ẢNH (Dùng images_arr) -->
        <div class="main-image">
          <button class="nav-btn prev" onclick="changeImage(-1)"><i class="fa-solid fa-chevron-left"></i></button>
          <img id="mainImage" src="" alt="Xe" />
          <button class="nav-btn next" onclick="changeImage(1)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <div>
          <h2>Mô tả sản phẩm</h2>
          <p id="description" style="color: #555; font-size: 17px;"></p>
          
          <h3 style="margin-top: 30px; text-transform: uppercase; font-size: 18px;">Tính năng nổi bật</h3>
          <ul class="features-list" id="featuresList"></ul>
          
          <div class="price-buy-section">
            <div class="price-main">
              <span id="productPrice" class="main-price">0 ₫</span>
              <small style="color: #888;">Giá bán lẻ đề xuất (đã bao gồm VAT)</small>
            </div>
            <div class="action-buttons">
              <a href="#" id="buyNowBtn" class="buy-now-btn">
                <i class="fa-solid fa-bolt" style="margin-right: 8px;"></i> Mua ngay
              </a>
              <a href="tuvan.php" class="consult-btn">Tư vấn ngay</a>
            </div>
          </div>
        </div>
      </div>

      <h2 style="margin-top: 60px;">Thông số kỹ thuật chi tiết</h2>
      <table class="spec-table"><tbody id="specTable"></tbody></table>
    </section>

    <script>
      const urlParams = new URLSearchParams(window.location.search);
      const bikeId = urlParams.get("id");

      let imageList = [];
      let currentIndex = 0;

      // Hàm xử lý đường dẫn ảnh (loại bỏ backslash)
      function formatPath(path) {
        if (!path) return "";
        return path.replace(/\\/g, "/").trim();
      }

      function changeImage(direction) {
        if (imageList.length === 0) return;
        currentIndex = (currentIndex + direction + imageList.length) % imageList.length;
        const imgElement = document.getElementById("mainImage");
        imgElement.style.opacity = 0.5;
        setTimeout(() => {
            imgElement.src = imageList[currentIndex];
            imgElement.style.opacity = 1;
        }, 150);
      }

      if (bikeId) {
        fetch(`chitiet_api.php?id=${bikeId}`)
          .then(res => res.json())
          .then(data => {
            // 1. Cập nhật thông tin chữ
            document.getElementById("productName").textContent = data.name;
            document.getElementById("productPrice").textContent = data.price_formatted;
            document.getElementById("description").textContent = data.description || data.short_desc;

            // 2. LOGIC ẢNH HERO BANNER (Ưu tiên lấy main_image)
            let heroPath = "";
            if (data.main_image) {
                heroPath = formatPath(data.main_image);
            } else if (data.images_arr && data.images_arr.length > 0) {
                heroPath = formatPath(data.images_arr[0]);
            }
            
            if (heroPath) {
                document.getElementById("heroBanner").style.backgroundImage = `url('${heroPath}')`;
            }

            // 3. LOGIC GALLERY/SLIDER (Lấy từ images_arr)
            if (data.images_arr && data.images_arr.length > 0) {
              imageList = data.images_arr.map(img => formatPath(img));
              document.getElementById("mainImage").src = imageList[0];
            }

            // 4. LOGIC NÚT MUA NGAY (Gán ID thực từ DB)
            if (data.id) {
                document.getElementById("buyNowBtn").href = `handle_cart.php?action=add&id=${data.id}`;
            }

            // 5. Đổ tính năng
            const fList = document.getElementById("featuresList");
            if (data.features_arr) {
                fList.innerHTML = "";
                data.features_arr.forEach(f => {
                    const li = document.createElement("li");
                    li.textContent = f;
                    fList.appendChild(li);
                });
            }

            // 6. Đổ thông số
            const sTable = document.getElementById("specTable");
            if (data.specs_arr) {
                sTable.innerHTML = "";
                for (const [key, value] of Object.entries(data.specs_arr)) {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `<th>${key}</th><td>${value}</td>`;
                    sTable.appendChild(tr);
                }
            }
          })
          .catch(err => console.error("Lỗi tải dữ liệu:", err));
      }
    </script>
     <?php include 'includes/footer.php'; ?>
  </body>
</html>