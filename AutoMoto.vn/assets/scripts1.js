document.addEventListener("DOMContentLoaded", function () {
  const userBox = document.getElementById("user-box");
  const userIcon = document.getElementById("user-icon");
  const userDropdown = document.getElementById("user-dropdown");

  // Khi click vào icon user
  userIcon.addEventListener("click", function (e) {
    e.stopPropagation(); // Ngăn sự kiện nổi bọt
    userDropdown.classList.toggle("active");
  });

  // Khi click vào bất kỳ đâu trên màn hình
  document.addEventListener("click", function (e) {
    // Nếu click bên ngoài userBox thì đóng menu
    if (!userBox.contains(e.target)) {
      userDropdown.classList.remove("active");
    }
  });
});
