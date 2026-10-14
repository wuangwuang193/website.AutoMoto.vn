document.addEventListener("DOMContentLoaded", function () {
  const container = document.getElementById("search-container");
  const icon = document.getElementById("search-icon");
  const input = document.getElementById("search-field");

  if (icon) {
    icon.addEventListener("click", function () {
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
  }

  if (input) {
    input.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        const keyword = this.value.trim();
        if (keyword !== "") {
          window.location.href =
            "search.php?keyword=" + encodeURIComponent(keyword);
        }
      }
    });
  }
});
