<?php
// config/config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//Khai báo thông tin database
$host = 'localhost';
$db   = 'automoto'; 
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4';
//Tạo DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
//Cấu hình PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
//Kết nối database (try-catch)
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>