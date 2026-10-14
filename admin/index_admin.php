<?php
session_start();
require_once '../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$admin_name = $_SESSION['user_name'] ?? 'Admin';

// Lấy dữ liệu cơ bản
$totalProducts = $pdo->query("SELECT COUNT(*) FROM bikes")->fetchColumn() ?? 0;

$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM orders 
    WHERE DATE(created_at) = CURDATE()
    AND status IN ('confirmed','completed')
");
$stmt->execute();
$todayOrders = $stmt->fetchColumn() ?? 0;

$stmt = $pdo->prepare("
    SELECT SUM(total_price) as revenue 
    FROM orders 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
    AND YEAR(created_at) = YEAR(CURRENT_DATE())
    AND status IN ('confirmed','completed')
");
$stmt->execute();
$monthlyRevenue = $stmt->fetchColumn() ?? 0;

$stmt = $pdo->query("SELECT AVG(rating) FROM reviews");
$avgRating = round($stmt->fetchColumn() ?? 0, 1);

$stmt = $pdo->query("SELECT COUNT(*) FROM reviews");
$totalReviews = $stmt->fetchColumn() ?? 0;

// Dữ liệu cho biểu đồ: Doanh thu theo ngày trong 30 ngày gần nhất (mẫu line chart)
$stmt = $pdo->prepare("
    SELECT DATE(created_at) as date, SUM(total_price) as revenue
    FROM orders
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    AND status IN ('confirmed','completed')
    GROUP BY DATE(created_at)
    ORDER BY date ASC
");
$stmt->execute();
$salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Chuẩn bị mảng cho Chart.js
$labels = [];
$revenues = [];
foreach ($salesData as $row) {
    $labels[] = date('d/m', strtotime($row['date']));
    $revenues[] = (float)$row['revenue'];
}

// Chuyển sang JSON
$salesJson = json_encode([
    'labels' => $labels,
    'revenues' => $revenues
]);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AutoMoto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>


</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">AUTOMOTO ADMIN</div>
    <a href="index_admin.php" class="active"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="admin_bikes.php"><i class="fas fa-motorcycle"></i> Sản phẩm</a>
    <a href="admin_users.php"><i class="fas fa-users"></i> Người dùng</a> 
    <a href="order.php"><i class="fas fa-shopping-cart"></i> Đơn hàng</a>
    <a href="admin_reviews.php"><i class="fas fa-star"></i> Đánh giá</a>
    <a href="../auth/logout.php" style="margin-top: auto; color: var(--primary);">
        <i class="fas fa-sign-out-alt"></i> Đăng xuất
    </a>
</div>

<div class="main">
   <div class="admin-header">
    
    <div class="left">
        <div class="logo-box">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Admin Panel</span>
        </div>
    </div>



    <div class="right">
        <div class="notify">
            <i class="fa-regular fa-bell"></i>
            <span class="badge">3</span>
        </div>

        <div class="profile">
            <img src="https://i.pravatar.cc/40" alt="admin">
            <span>Quang Nhật</span>
            <i class="fa-solid fa-chevron-down"></i>
        </div>
    </div>

</div>

    <div class="cards">
        <div class="card">
            <i class="fas fa-boxes-stacked card-icon"></i>
            <h3>Tổng sản phẩm</h3>
            <div class="number"><?= number_format($totalProducts) ?></div>
            <div class="change up"><i class="fas fa-arrow-up"></i> Đang hoạt động</div>
        </div>

        <div class="card">
            <i class="fas fa-bag-shopping card-icon"></i>
            <h3>Đơn hàng hôm nay</h3>
            <div class="number"><?= number_format($todayOrders) ?></div>
            <div class="change up"><i class="fas fa-arrow-up"></i> Đã xác nhận/completed</div>
        </div>

        <div class="card">
            <i class="fas fa-coins card-icon"></i>
            <h3>Doanh thu tháng này</h3>
            <div class="number">₫ <?= number_format($monthlyRevenue, 0, ',', '.') ?></div>
            <div class="change up"><i class="fas fa-arrow-up"></i> Từ đơn hàng hợp lệ</div>
        </div>

        <div class="card">
            <i class="fas fa-star card-icon"></i>
            <h3>Đánh giá trung bình</h3>
            <div class="number"><?= $avgRating ?> / 5.0</div>
            <div class="change"><i class="fas fa-users"></i> Từ <?= number_format($totalReviews) ?> đánh giá</div>
        </div>
    </div>

    <!-- Sales Report Section -->
    <!-- Sales Report Section -->
<div class="sales-report">
    <div class="sales-header">
        <h2>Báo cáo doanh thu</h2>
        <div class="time-filters">
            <button class="time-btn" data-days="7">7 ngày</button>
            <button class="time-btn active" data-days="30">30 ngày</button>
            <button class="time-btn" data-days="90">90 ngày</button>
        </div>
    </div>
    
    <div class="chart-container">
        <canvas id="salesChart"></canvas>
    </div>
</div>
</div>

<script>
const salesCtx = document.getElementById('salesChart').getContext('2d');

// Dữ liệu từ PHP
const salesData = <?php echo $salesJson; ?>;

const salesChart = new Chart(salesCtx, {
    type: 'line',  // Có thể đổi sang 'bar' nếu muốn biểu đồ cột
    data: {
        labels: salesData.labels,
        datasets: [{
            label: 'Doanh thu (₫)',
            data: salesData.revenues,
            borderColor: 'rgba(225, 6, 0, 1)',
            backgroundColor: 'rgba(225, 6, 0, 0.2)',
            fill: true,
            tension: 0.4,
            borderWidth: 3,
            pointBackgroundColor: '#fff',
            pointBorderColor: 'rgba(225, 6, 0, 1)',
            pointHoverRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleFont: { size: 14 },
                bodyFont: { size: 14 },
                callbacks: {
                    label: (context) => `₫ ${context.parsed.y.toLocaleString('vi-VN')}`
                }
            }
        },
        scales: {
            x: { grid: { color: '#222' } },
            y: {
                grid: { color: '#222' },
                ticks: {
                    callback: (value) => '₫ ' + value.toLocaleString('vi-VN')
                }
            }
        }
    }
});

// Xử lý filter thời gian (mẫu, sau này thêm AJAX)
document.querySelectorAll('.time-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        // TODO: Fetch data mới qua AJAX dựa trên data-days
    });
});
</script>

</body>
</html>