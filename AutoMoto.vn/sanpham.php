<?php
include "includes/db_connect.php";

$keyword = trim($_GET['keyword'] ?? '');
$products = [];

if ($keyword !== '') {
    $searchTerm = "%" . $keyword . "%";

    $stmt = $conn->prepare("
        SELECT * FROM bikes 
        WHERE LOWER(name) LIKE LOWER(?) 
           OR LOWER(slug) LIKE LOWER(?)
        ORDER BY name ASC
    ");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    $result = $conn->query("SELECT * FROM bikes ORDER BY name ASC");
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Sản phẩm | AutoMoto</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
}

/* HEADER */
.header {
    background: linear-gradient(135deg, #111, #333);
    color: white;
    padding: 30px;
    text-align: center;
}

.header h1 {
    margin: 0;
    font-size: 32px;
    letter-spacing: 1px;
}

.keyword {
    margin-top: 10px;
    font-size: 16px;
    color: #ccc;
}

/* GRID */
.product-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 30px;
}

/* CARD */
.product-card {
    background: white;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    transition: 0.3s ease;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.product-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.product-info {
    padding: 20px;
}

.product-info h3 {
    margin: 0 0 10px;
    font-size: 18px;
    color: #222;
}

.product-info p {
    margin: 0;
    font-weight: bold;
    color: #e60023;
    font-size: 16px;
}

/* EMPTY */
.empty {
    text-align: center;
    padding: 60px;
    color: #777;
    font-size: 18px;
}
</style>
</head>
<body>

<div class="header">
    <h1>Danh sách sản phẩm</h1>
    <?php if ($keyword !== ''): ?>
        <div class="keyword">
            Kết quả tìm kiếm cho: "<strong><?= htmlspecialchars($keyword) ?></strong>"
        </div>
    <?php endif; ?>
</div>

<div class="product-container">
    <?php if (empty($products)): ?>
        <div class="empty">
            Không tìm thấy sản phẩm nào phù hợp.
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $row): ?>
                <div class="product-card">
                    <a href="chitiet.php?slug=<?= htmlspecialchars($row['slug']) ?>" style="text-decoration:none;">
                        <img src="<?= htmlspecialchars($row['main_image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        <div class="product-info">
                            <h3><?= htmlspecialchars($row['name']) ?></h3>
                            <p><?= number_format($row['price']) ?> VNĐ</p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>