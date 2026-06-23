<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db_config.php';

// 如果購物車是空的，導回購物車頁面
if (empty($_SESSION['cart']['cakes'])) {
    header("Location: cart.php");
    exit();
}

// 取得購物車資料與折扣計算 (邏輯與 cart.php 保持一致)
$cart = $_SESSION['cart']['cakes'] ?? [];
$subtotal = 0;
foreach ($cart as $item) { 
    $subtotal += (($item['price'] ?? 1000) * ($item['quantity'] ?? 1)); 
}

$role = $_SESSION['role'] ?? 'guest';
$discount_rate = ($role === 'vip') ? 0.15 : (($role === 'member') ? 0.10 : 0);
$discount_amount = $subtotal * $discount_rate;
$shipping_fee = ($subtotal >= 2000) ? 0 : 100; // 滿2000免運
$final_total = ($subtotal - $discount_amount) + $shipping_fee;
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>填寫結帳資訊 - Layer Master</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #fff9f7; padding: 40px 20px; }
        .container { max-width: 800px; margin: auto; background: #ffffff; padding: 40px; border-radius: 40px; border: 3px solid #ffe4e1; }
        h1, h2 { color: #e9967a; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #7d6b62; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border-radius: 10px; border: 1px solid #ffb6c1; }
        .order-summary { background: #fffaf0; padding: 20px; border-radius: 20px; margin-bottom: 20px; }
        .checkout-btn { background: #ff69b4; color: white; padding: 15px 30px; border-radius: 20px; border: none; cursor: pointer; font-size: 1.2rem; width: 100%; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h1>📝 結帳確認</h1>
    <form action="process_order.php" method="POST">
        
        <div class="order-summary">
            <h2>個人資料</h2>
            <div class="form-group"><label>姓名</label><input type="text" name="name" required></div>
            <div class="form-group"><label>電話</label><input type="text" name="phone" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>寄送地址</label><input type="text" name="address" required></div>
            <div class="form-group"><label>希望送達日期</label><input type="date" name="delivery_date" required></div>
        </div>

        <div class="order-summary">
            <h2>訂單內容</h2>
            <ul>
                <?php foreach ($cart as $item) {
                    // 修正：使用 'name' 取代原本錯誤的 'item_name'，並加上預設值
                    $display_name = $item['name'] ?? '客製化蛋糕';
                    $qty = $item['quantity'] ?? 1;
                    $price = $item['price'] ?? 1000;
                    echo "<li>" . htmlspecialchars($display_name) . " x " . $qty . " - NT$ " . number_format($price * $qty) . "</li>";
                } ?>
            </ul>
        </div>

        <div class="order-summary">
            <h2>金額計算</h2>
            <p>小計：NT$ <?php echo number_format($subtotal); ?></p>
            <p>折扣金額：- NT$ <?php echo number_format($discount_amount); ?></p>
            <p>運費：<?php echo ($shipping_fee == 0) ? '免運費' : 'NT$ ' . number_format($shipping_fee); ?></p>
            <h3>最終總金額：NT$ <?php echo number_format($final_total); ?></h3>
        </div>

        <div class="order-summary">
            <h2>付款方式</h2>
            <select name="payment_method">
                <option value="cash">現金</option>
                <option value="credit_card">信用卡</option>
            </select>
        </div>

        <button type="submit" class="checkout-btn">確認送出訂單</button>
    </form>
</div>
</body>
</html>