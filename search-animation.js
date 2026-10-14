document.addEventListener("DOMContentLoaded", function () {
  const searchContainer = document.getElementById("search-container");
  const searchIcon = document.getElementById("search-icon");
  const searchField = document.getElementById("search-field");

  searchIcon.addEventListener("click", function (e) {
    e.stopPropagation();
    searchContainer.classList.toggle("active");

    if (searchContainer.classList.contains("active")) {
      searchField.focus();
    }
  });

  // click ra ngoài thì đóng lại
  document.addEventListener("click", function (e) {
    if (!searchContainer.contains(e.target)) {
      searchContainer.classList.remove("active");
    }
  });
});
searchField.addEventListener("keyup", function () {
  let keyword = searchField.value;

  if (keyword.length > 1) {
    fetch("search.php?keyword=" + keyword)
      .then((response) => response.text())
      .then((data) => {
        document.getElementById("search-result").innerHTML = data;
      });
  }
});
