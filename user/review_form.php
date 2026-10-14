<?php
session_start();
include "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }

$product_id = intval($_GET['product_id']);
$user_id = $_SESSION['user_id'];

// Lấy thông tin xe để hiển thị
$sql_bike = "SELECT name FROM bikes WHERE id = ?";
$stmt = $conn->prepare($sql_bike);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$bike = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $comment = $_POST['comment'];

    $sql_ins = "INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt_ins = $conn->prepare($sql_ins);
    $stmt_ins->bind_param("iiis", $product_id, $user_id, $rating, $comment);
    
    if ($stmt_ins->execute()) {
        echo "<script>alert('Cảm ơn bạn đã đánh giá!'); window.location.href='orders.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đánh giá sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Roboto Condensed', sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding: 50px; }
        .review-box { background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 400px; }
        h2 { color: #d62828; margin-bottom: 20px; }
        .star-rating { font-size: 30px; color: #ddd; cursor: pointer; display: flex; flex-direction: row-reverse; justify-content: flex-end; }
        .star-rating input { display: none; }
        .star-rating label:hover, .star-rating label:hover ~ label, .star-rating input:checked ~ label { color: #ffcc00; }
        textarea { width: 100%; height: 100px; margin-top: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 8px; }
        .btn-submit { background: #d62828; color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="review-box">
        <h2>Đánh giá: <?= htmlspecialchars($bike['name']) ?></h2>
        <form method="POST">
            <div class="star-rating">
                <input type="radio" name="rating" id="star5" value="5"><label for="star5" class="fas fa-star"></label>
                <input type="radio" name="rating" id="star4" value="4"><label for="star4" class="fas fa-star"></label>
                <input type="radio" name="rating" id="star3" value="3"><label for="star3" class="fas fa-star"></label>
                <input type="radio" name="rating" id="star2" value="2"><label for="star2" class="fas fa-star"></label>
                <input type="radio" name="rating" id="star1" value="1"><label for="star1" class="fas fa-star"></label>
            </div>
            <textarea name="comment" placeholder="Nhập cảm nhận của bạn về chiếc xe này..." ></textarea>
            <button type="submit" class="btn-submit">GỬI ĐÁNH GIÁ</button>
        </form>
    </div>

</body>
</html>