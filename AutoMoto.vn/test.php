<script src="sanpham.js"></script>
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
