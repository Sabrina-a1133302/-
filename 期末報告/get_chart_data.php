<?php
require_once 'db_config.php'; // 確保這會產生 $pdo 物件

// 查詢最近 7 天的營收資料 (改用 PDO 語法)
try {
    $query = "SELECT DATE(created_at) as date, SUM(total_amount) as daily_revenue 
              FROM orders 
              GROUP BY DATE(created_at) 
              ORDER BY date ASC LIMIT 7";
    
    $stmt = $pdo->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 設定標頭為 JSON
    header('Content-Type: application/json');
    echo json_encode($data);
    
} catch (PDOException $e) {
    // 若出錯回傳空陣列或錯誤訊息
    echo json_encode([]);
}
?>