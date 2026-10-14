const form = document.getElementById("tuvanForm");
const ketQuaSection = document.getElementById("ketQua");
const grid = document.getElementById("gridKetQua");

form.addEventListener("submit", (e) => {
  e.preventDefault();

  const gia = document.getElementById("gia").value;
  const nhuCau = document.getElementById("nhuCau").value;
  const uuTien = document.getElementById("uuTien").value; // Dùng để tinh chỉnh nếu cần

  let goiY = [];

  // ──────────────────────────────────────────────
  // LOGIC CHÍNH: Dưới 30 triệu
  // ──────────────────────────────────────────────
  if (gia === "Dưới 30 triệu") {
    if (nhuCau === "Đi học / đi làm") {
      // Phù hợp sinh viên, đi lại hàng ngày: tiết kiệm, gọn nhẹ, cốp rộng nếu ga
      goiY = [
        {
          ten: "Honda Wave Alpha 110",
          gia: "Khoảng 18-20 triệu",
          lyDo: "Xe số quốc dân, bền bỉ, tiết kiệm xăng, dễ sửa.",
          anh: "img/products/honda/wave alpha.png",
        },
        {
          ten: "Yamaha Sirius",
          gia: "Khoảng 19-24 triệu",
          lyDo: "Gọn nhẹ, tiết kiệm, phù hợp nam/nữ đi học/làm.",
          anh: "img/products/yamaha/sirus.webp",
        },
        {
          ten: "Yamaha Janus 125",
          gia: "Khoảng 28-33 triệu (ưu đãi dưới 30tr)",
          lyDo: "Xe ga thời trang, cốp rộng, nhẹ, hợp nữ sinh viên.",
          anh: "img/products/yamaha/Janus.webp",
        },
        {
          ten: "Honda Vision",
          gia: "Khoảng 31-35 triệu (ưu đãi dưới 30tr)",
          lyDo: "Xe ga phổ biến, cốp rộng, tiết kiệm, dễ lái.",
          anh: "img/products/honda/vison.jpg",
        },
        {
          ten: "Yamaha Jupiter Finn",
          gia: "Khoảng 27-29 triệu",
          lyDo: "Tiết kiệm xăng, thiết kế trẻ trung, phù hợp đi học.",
          anh: "img/products/yamaha/Jupiter-Finn.webp",
        },
      ];
    } else if (nhuCau === "Gia đình") {
      // Cốp rộng, bền, chở được người/thứ
      goiY = [
        {
          ten: "Honda Wave Alpha 110",
          gia: "Khoảng 18-20 triệu",
          lyDo: "Bền bỉ, dễ chở người, chi phí bảo dưỡng thấp.",
          anh: "img/products/honda/wave alpha.png",
        },
        {
          ten: "Yamaha Sirius",
          gia: "Khoảng 19-24 triệu",
          lyDo: "Ổn định, tiết kiệm, phù hợp chở gia đình ngắn.",
          anh: "img/products/yamaha/sirus.webp",
        },
        {
          ten: "Honda Vision",
          gia: "Khoảng 31-35 triệu (ưu đãi)",
          lyDo: "Cốp rộng, tiện lợi cho gia đình nhỏ.",
          anh: "img/products/honda/vison.jpg",
        },
        {
          ten: "Yamaha Grande",
          gia: "Khoảng 46-52 triệu (ưu đãi dưới 50tr)",
          lyDo: "Cốp siêu rộng, hybrid tiết kiệm, phù hợp gia đình.",
          anh: "img/products/yamaha/Grande.webp",
        },
      ];
    } else if (nhuCau === "Đi phượt") {
      // Mạnh mẽ hơn, nhưng dưới 30tr thì hạn chế → gợi ý xe số bền
      goiY = [
        {
          ten: "Honda Blade 110 - Phiên bản Thể Thao",
          gia: "Khoảng 20-25 triệu",
          lyDo: "Thiết kế thể thao, bền bỉ, phù hợp đường dài ngắn.",
          anh: "img/products/honda/Blade.webp",
        },
        {
          ten: "Yamaha Sirius",
          gia: "Khoảng 19-24 triệu",
          lyDo: "Tiết kiệm xăng, động cơ ổn định cho đường trường.",
          anh: "img/products/yamaha/sirus.webp",
        },
        {
          ten: "Suzuki Impulse 125 FI",
          gia: "Khoảng 28-32 triệu",
          lyDo: "Ga tiết kiệm, động cơ mạnh, phù hợp đi xa nhẹ.",
          anh: "img/products/suzuki/impulse 125.webp",
        },
      ];
    }
  }

  // ──────────────────────────────────────────────
  // LOGIC: 30 – 50 triệu
  // ──────────────────────────────────────────────
  else if (gia === "30 – 50 triệu") {
    if (nhuCau === "Đi học / đi làm") {
      goiY = [
        {
          ten: "Honda Air Blade 125/160",
          gia: "Khoảng 42-58 triệu",
          lyDo: "Nam tính, mạnh mẽ, cốp rộng, phù hợp đi làm.",
          anh: "img/products/honda/airblade.png",
        },
        {
          ten: "Yamaha NVX",
          gia: "Khoảng 55 triệu",
          lyDo: "Thiết kế thể thao, cốp rộng, tiện lợi hàng ngày.",
          anh: "img/products/yamaha/NVX/Xanh-Duong-G8-1024x684.webp",
        },
        {
          ten: "Yamaha Exciter",
          gia: "Khoảng 44-54 triệu",
          lyDo: "Côn tay, mạnh mẽ, phong cách trẻ trung.",
          anh: "img/products/yamaha/Exciter155.webp",
        },
        {
          ten: "Yamaha Grande",
          gia: "Khoảng 46-52 triệu",
          lyDo: "Hybrid tiết kiệm, cốp rộng, thời trang.",
          anh: "img/products/yamaha/Grande.webp",
        },
        {
          ten: "Yamaha Janus 125",
          gia: "Khoảng 29-34 triệu",
          lyDo: "Nhẹ, thời trang, phù hợp nữ đi làm.",
          anh: "img/products/yamaha/Janus.webp",
        },
      ];
    } else if (nhuCau === "Đi phượt") {
      goiY = [
        {
          ten: "Yamaha Exciter",
          gia: "Khoảng 44-54 triệu",
          lyDo: "Côn tay mạnh mẽ, VVA, phù hợp đường trường/phượt.",
          anh: "img/products/yamaha/Exciter155.webp",
        },
        {
          ten: "Honda Blade 110 - Phiên bản Thể Thao",
          gia: "Khoảng 20-25 triệu (nâng cấp)",
          lyDo: "Thể thao, bền, giá tốt cho phượt nhẹ.",
          anh: "img/products/honda/Blade.webp",
        },
        {
          ten: "Suzuki Satria F150",
          gia: "Khoảng 48-53 triệu",
          lyDo: "Thể thao, mạnh, phù hợp đường dài.",
          anh: "img/products/suzuki/Satria-MATBLACK.jpg",
        },
        {
          ten: "Suzuki Raider 150",
          gia: "Khoảng 45-51 triệu",
          lyDo: "Underbone mạnh mẽ, tốc độ tốt.",
          anh: "img/products/suzuki/Raider-R150.jpg",
        },
      ];
    } else if (nhuCau === "Gia đình") {
      goiY = [
        {
          ten: "Yamaha Grande",
          gia: "Khoảng 46-52 triệu",
          lyDo: "Cốp rộng, hybrid tiết kiệm, phù hợp chở gia đình.",
          anh: "img/products/yamaha/Grande.webp",
        },
        {
          ten: "Honda Vision",
          gia: "Khoảng 31-37 triệu",
          lyDo: "Cốp lớn, tiện lợi, bền bỉ.",
          anh: "img/products/honda/vison.jpg",
        },
        {
          ten: "Yamaha Lexi",
          gia: "Khoảng 48-50 triệu",
          lyDo: "Ga cao cấp, cốp rộng, êm ái.",
          anh: "img/products/yamaha/lexi.jpg",
        },
        {
          ten: "VinFast Feliz 2025",
          gia: "Khoảng 25-35 triệu (xe điện)",
          lyDo: "Xe điện tiết kiệm, cốp rộng, thân thiện môi trường.",
          anh: "img/products/vinfast/felix.webp",
        },
      ];
    }
  }

  // ──────────────────────────────────────────────
  // LOGIC: Trên 50 triệu → Cao cấp, thể thao, điện cao cấp
  // ──────────────────────────────────────────────
  else if (gia === "Trên 50 triệu") {
    goiY = [
      {
        ten: "Honda Super Cub C125",
        gia: "Khoảng 86-94 triệu",
        lyDo: "Cổ điển cao cấp, bền bỉ, phong cách riêng.",
        anh: "img/products/honda/super cup.png",
      },
      {
        ten: "Honda CB1000 Hornet",
        gia: "Trên 100 triệu (nhập khẩu)",
        lyDo: "Mô tô naked mạnh mẽ, phù hợp đam mê tốc độ.",
        anh: "img/products/honda/C1000.jpg",
      },
      {
        ten: "Honda Rebel 500 2025",
        gia: "Trên 100 triệu",
        lyDo: "Cruiser phong cách, thoải mái đường dài.",
        anh: "img/products/honda/rebel.avif",
      },
      {
        ten: "Honda CBR650R 2024",
        gia: "Trên 200 triệu",
        lyDo: "Sportbike cao cấp, tốc độ cao.",
        anh: "img/products/honda/cbr650r.jpg",
      },
      {
        ten: "Yamaha MT-15 / YZF-R15",
        gia: "Khoảng 69-87 triệu",
        lyDo: "Mô tô thể thao, VVA mạnh mẽ.",
        anh: "img/products/yamaha/mt 15.webp",
      },
      {
        ten: "Suzuki V-Strom 250SX",
        gia: "Khoảng 130 triệu",
        lyDo: "Adventure, phù hợp phượt xa.",
        anh: "img/products/suzuki/V-Strom 250SX/image (1).jpg",
      },
      {
        ten: "Suzuki GSX-R1000",
        gia: "Trên 200 triệu",
        lyDo: "Superbike đỉnh cao.",
        anh: "img/products/suzuki/GSX.jpg",
      },
      {
        ten: "VinFast Vero X",
        gia: "Khoảng 34-35 triệu (xe điện cao cấp)",
        lyDo: "Xe điện mạnh mẽ, quãng đường xa.",
        anh: "img/products/vinfast/VERO/img-banner-info.webp",
      },
      {
        ten: "VinFast Vento Neo",
        gia: "Khoảng 32 triệu (xe điện)",
        lyDo: "Xe điện thời thượng, tốc độ cao.",
        anh: "img/products/vinfast/vento neo.webp",
      },
    ];
  }

  // ──────────────────────────────────────────────
  // FALLBACK nếu không khớp hoặc hãng khác
  // ──────────────────────────────────────────────
  if (goiY.length === 0) {
    goiY = [
      {
        ten: "Cần tư vấn chi tiết hơn",
        gia: "",
        lyDo: "Với nhu cầu của bạn, chúng tôi sẽ gợi ý mẫu xe phù hợp nhất. Vui lòng liên hệ hotline hoặc Zalo để được hỗ trợ!",
        anh: "",
      },
    ];
  }

  // Render kết quả
  grid.innerHTML = "";
  goiY.forEach((xe) => {
    const html = `
      <div class="bike-card">
        ${xe.anh ? `<img src="${xe.anh}" alt="${xe.ten}" />` : ""}
        <div class="bike-info">
          <h3>${xe.ten}</h3>
          <p><strong>Giá tham khảo:</strong> ${xe.gia || "Liên hệ"}</p>
          <p>${xe.lyDo}</p>
        </div>
      </div>
    `;
    grid.innerHTML += html;
  });

  ketQuaSection.style.display = "block";
  ketQuaSection.scrollIntoView({ behavior: "smooth" });
});
