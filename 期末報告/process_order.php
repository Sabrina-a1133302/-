<?php
// 設定除錯模式
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 載入 PDO 設定
require_once 'db_config.php';

if (!isset($pdo)) {
    die("錯誤：資料庫連線變數 \$pdo 未定義。");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart']['cakes'])) {
    
    // 計算金額
    $subtotal = 0;
    foreach ($_SESSION['cart']['cakes'] as $item) {
        $subtotal += (($item['price'] ?? 1000) * ($item['quantity'] ?? 1));
    }
    $role = $_SESSION['role'] ?? 'guest';
    $discount_rate = ($role === 'vip') ? 0.15 : (($role === 'member') ? 0.10 : 0);
    $discount_amount = $subtotal * $discount_rate;
    $shipping_fee = 100;
    $final_total = ($subtotal - $discount_amount) + $shipping_fee;

    try {
        // 1. 寫入訂單主檔 (orders) - 移除不存在的 discount_value
        $sql_order = "INSERT INTO orders (name, phone, email, address, delivery_date, payment_method, total_amount) 
                      VALUES (:name, :phone, :email, :address, :delivery_date, :payment_method, :total)";
        
        $stmt = $pdo->prepare($sql_order);
        $stmt->execute([
            ':name' => $_POST['name'],
            ':phone' => $_POST['phone'],
            ':email' => $_POST['email'],
            ':address' => $_POST['address'],
            ':delivery_date' => $_POST['delivery_date'],
            ':payment_method' => $_POST['payment_method'],
            ':total' => $final_total
        ]);
        
        $order_id = $pdo->lastInsertId();

        // 2. 寫入訂單明細 (order_items) - 加入 discount_value 與 is_used
        foreach ($_SESSION['cart']['cakes'] as $item) {
            $sql_item = "INSERT INTO order_items (order_id, item_name, price, quantity, discount_value, is_used) 
                         VALUES (:order_id, :item_name, :price, :quantity, :discount, 0)";
            $stmt_item = $pdo->prepare($sql_item);
            $stmt_item->execute([
                ':order_id' => $order_id,
                ':item_name' => $item['name'] ?? 'Custom Cake',
                ':price' => (int)($item['price'] ?? 0),
                ':quantity' => (int)($item['quantity'] ?? 1),
                ':discount' => (int)$discount_amount
            ]);
        }

        $_SESSION['last_order'] = [
            'name' => $_POST['name'],
            'total' => $final_total,
            'date' => $_POST['delivery_date']
        ];

        unset($_SESSION['cart']['cakes']);
        header("Location: order_success.php");
        exit();
        
    } catch (PDOException $e) {
        die("訂單寫入失敗: " . $e->getMessage());
    }
} else {
    header("Location: cart.php");
    exit();
}
?>