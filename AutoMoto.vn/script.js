document.addEventListener("DOMContentLoaded", () => {
  /* ===============================
     BANNER SLIDER (TRÊN)
  =============================== */
  const bannerSlider = document.querySelector(".slider");
  if (bannerSlider) {
    const bannerSlides = bannerSlider.querySelectorAll(".slides .slide");
    const nextBtn = bannerSlider.querySelector(".next");
    const prevBtn = bannerSlider.querySelector(".prev");

    let bannerIndex = 0;

    function showBannerSlide(i) {
      bannerSlides.forEach((slide) => slide.classList.remove("active"));
      bannerSlides[i].classList.add("active");
    }

    nextBtn.addEventListener("click", () => {
      bannerIndex = (bannerIndex + 1) % bannerSlides.length;
      showBannerSlide(bannerIndex);
    });

    prevBtn.addEventListener("click", () => {
      bannerIndex =
        (bannerIndex - 1 + bannerSlides.length) % bannerSlides.length;
      showBannerSlide(bannerIndex);
    });

    setInterval(() => {
      bannerIndex = (bannerIndex + 1) % bannerSlides.length;
      showBannerSlide(bannerIndex);
    }, 5000);
  }

  /* ===============================
     BRAND SLIDER (DƯỚI)
  =============================== */
  const brandTabs = document.querySelectorAll(".brand-tab");
  const brandSlides = document.querySelectorAll(".brand-slides .slide");

  brandTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const brand = tab.dataset.brand;

      brandTabs.forEach((t) => t.classList.remove("active"));
      tab.classList.add("active");

      brandSlides.forEach((slide) => slide.classList.remove("active"));

      const activeSlide = document.querySelector(
        `.brand-slides .slide[data-brand="${brand}"]`,
      );

      if (activeSlide) activeSlide.classList.add("active");
    });
  });
});
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
