<?php
// db_config.php (修正版)
$host = 'localhost';
$db   = 'layermaster'; 
$user = 'root';
$pass = '123456'; 

try {
    // 只使用 PDO 連線，避免混用造成的衝突
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 如果發生錯誤，顯示錯誤訊息並停止執行
    die("資料庫連線失敗: " . $e->getMessage());
}
?>