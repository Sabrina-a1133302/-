<?php
// 設定除錯模式
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 載入 PDO 設定
require_once 'db_config.php';

// ✅ 加入寄信功能
require_once 'send_mail.php';

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
        // 1. 寫入訂單主檔
        // ✅ 假設目前登入使用者
        $user_id = 1; // 之後可以改成 $_SESSION['user_id']

        $sql_order = "INSERT INTO orders 
        (name, phone, email, address, delivery_date, payment_method, total_amount, user_id)  
        VALUES (:name, :phone, :email, :address, :delivery_date, :payment_method, :total, :user_id)";
                
        $stmt = $pdo->prepare($sql_order);
        $stmt->execute([
        ':name' => $_POST['name'],
        ':phone' => $_POST['phone'],
        ':email' => $_POST['email'],
        ':address' => $_POST['address'],
        ':delivery_date' => $_POST['delivery_date'],
        ':payment_method' => $_POST['payment_method'],
        ':total' => $final_total,
        ':user_id' => $user_id  
    ]);

        $order_id = $pdo->lastInsertId();

        if (!$order_id) {
            die("訂單建立失敗（沒有取得 order_id）");
        }

        // ✅ 補上：建立 stmt_item（你原本缺的）
        $sql_item = "INSERT INTO order_items 
        (order_id, item_name, price, quantity, discount_value, is_used)
        VALUES (:order_id, :item_name, :price, :quantity, :discount, 0)";

        $stmt_item = $pdo->prepare($sql_item);

        // ✅ 寫入明細
        foreach ($_SESSION['cart']['cakes'] as $item) {
            $stmt_item->execute([
                ':order_id' => $order_id,
                ':item_name' => $item['name'],
                ':price' => $item['price'],
                ':quantity' => $item['quantity'],
                ':discount' => $discount_amount
            ]);
        }

        // ✅ ✅ ✅ 加上寄信（關鍵）
        $mail_result = send_order_notification(
            $_POST['email'],
            $_POST['name'],
            $order_id,
            'pending'
        );

        // ✅ 除錯（寄信失敗會顯示）
        if (!$mail_result) {
            echo "⚠️ 訂單成立，但寄信失敗";
        }

        // ✅ 修正變數名稱錯誤
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
