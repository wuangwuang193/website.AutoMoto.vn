document.addEventListener("DOMContentLoaded", () => {
  // Lấy các phần tử filter
  const filterBrand = document.getElementById("filter-brand");
  const filterCategory = document.getElementById("filter-type"); // đây là lọc loại xe (xe số, tay ga, ...)
  const filterPrice = document.getElementById("filter-price");

  // Lấy tất cả các card sản phẩm
  const products = document.querySelectorAll(".product-card");

  // Hàm chính: lọc sản phẩm
  function applyFilters() {
    // Lấy giá trị đã chọn ("" nghĩa là "tất cả")
    const brandValue = filterBrand.value.trim().toLowerCase();
    const categoryValue = filterCategory.value.trim();
    const priceValue = filterPrice.value.trim();

    products.forEach((card) => {
      // Lấy dữ liệu từ thuộc tính data-*
      const cardBrand = card.getAttribute("data-brand")?.toLowerCase() || "";
      const cardCategory = card.getAttribute("data-category") || "";
      let cardPriceStr = card.getAttribute("data-price") || "0";

      // Chuẩn hóa giá: bỏ dấu chấm, khoảng trắng, chữ VND,... → thành số
      cardPriceStr = cardPriceStr.replace(/[^0-9]/g, "");
      const cardPrice = parseInt(cardPriceStr) || 0;

      // Kiểm tra từng điều kiện
      let matchBrand = true;
      let matchCategory = true;
      let matchPrice = true;

      // 1. Lọc hãng
      if (brandValue !== "") {
        matchBrand = cardBrand === brandValue;
      }

      // 2. Lọc loại xe (category)
      if (categoryValue !== "") {
        matchCategory = cardCategory === categoryValue;
      }

      // 3. Lọc giá
      if (priceValue !== "") {
        if (priceValue === "low") {
          matchPrice = cardPrice < 30000000;
        } else if (priceValue === "mid") {
          matchPrice = cardPrice >= 30000000 && cardPrice <= 70000000;
        } else if (priceValue === "high") {
          matchPrice = cardPrice > 70000000;
        }
      }

      // Hiển thị nếu THỎA MÃN TẤT CẢ điều kiện
      if (matchBrand && matchCategory && matchPrice) {
        card.style.display = "";
      } else {
        card.style.display = "none";
      }
    });
  }

  // Gắn sự kiện change cho từng bộ lọc
  filterBrand.addEventListener("change", applyFilters);
  filterCategory.addEventListener("change", applyFilters);
  filterPrice.addEventListener("change", applyFilters);

  // Chạy lần đầu khi load trang (để đảm bảo hiển thị đúng nếu có filter mặc định)
  applyFilters();
});
