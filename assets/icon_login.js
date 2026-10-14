// Toggle dropdown khi click icon user
document.addEventListener("DOMContentLoaded", function () {
  const userIcon = document.getElementById("user-icon");
  const dropdown = document.getElementById("user-dropdown");

  if (userIcon && dropdown) {
    userIcon.addEventListener("click", function (e) {
      e.stopPropagation(); // ngăn click lan ra ngoài
      dropdown.classList.toggle("show");
    });

    // Click ra ngoài → đóng dropdown
    document.addEventListener("click", function (e) {
      if (!userIcon.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove("show");
      }
    });
  }
});
