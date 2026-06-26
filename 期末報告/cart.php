<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db_config.php';

// ==========================================
// 新增：處理加購品加入購物車邏輯
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_POST['action'] ?? '') == 'add_addon') {
    $addon_id = intval($_POST['addon_id']);
    $stmt = $pdo->prepare("SELECT * FROM `addons` WHERE `id` = ?");
    $stmt->execute([$addon_id]);
    $addon = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($addon) {
        if (!isset($_SESSION['cart']['cakes'])) { $_SESSION['cart']['cakes'] = []; }
        $_SESSION['cart']['cakes'][] = [
            'name' => '加購：' . $addon['name'], 
            'price' => intval($addon['price']), 
            'quantity' => 1,
            'details' => []
        ];
    }
    header("Location: cart.php"); exit();
}

// (以下保持您原本的程式碼邏輯)
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM `cake_models` WHERE `id` = ?");
    $stmt->execute([$product_id]);
    $cake = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($cake) {
        if (!isset($_SESSION['cart']['cakes'])) { $_SESSION['cart']['cakes'] = []; }
        $found = false;
        foreach ($_SESSION['cart']['cakes'] as $index => $item) {
            if ($item['name'] === $cake['name'] && strpos($item['name'], '客製化') === false) {
                $_SESSION['cart']['cakes'][$index]['quantity'] += 1;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart']['cakes'][] = [
                'name' => $cake['name'], 
                'price' => intval($cake['price']), 
                'quantity' => 1,
                'details' => []
            ];
        }
    }
    header("Location: cart.php"); exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['index'])) {
    unset($_SESSION['cart']['cakes'][intval($_GET['index'])]);
    $_SESSION['cart']['cakes'] = array_values($_SESSION['cart']['cakes']);
    header("Location: cart.php"); exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_POST['action'] ?? '') == 'add_custom_cake') {
    $layers = $_POST['layers'] ?? '15層';
    $filling_input = $_POST['filling'] ?? '鮮奶油';
    $filling = ($filling_input == 'strawberry' || $filling_input == '草莓') ? '草莓' : '鮮奶油';
    $topping = $_POST['topping'] ?? '不加裝飾';
    $shape_input = $_POST['shape'] ?? '圓形';
    $shape = ($shape_input == 'heart' || $shape_input == '心形') ? '心形' : '圓形';
    
    $name = "客製化千層蛋糕 ({$layers} | 內餡: {$filling} | 裝飾: {$topping} | 造型: {$shape})";
    
    if (!isset($_SESSION['cart']['cakes'])) $_SESSION['cart']['cakes'] = [];
    $_SESSION['cart']['cakes'][] = [
        'name' => $name, 'price' => 1000, 'quantity' => 1,
        'details' => ['layers'=>$layers, 'filling'=>$filling, 'topping'=>$topping, 'shape'=>$shape]
    ];
    header("Location: cart.php"); exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_POST['action'] ?? '') == 'update_qty') {
    foreach ($_POST['quantity'] ?? [] as $index => $qty) {
        if (isset($_SESSION['cart']['cakes'][$index])) {
            $_SESSION['cart']['cakes'][$index]['quantity'] = max(1, intval($qty));
        }
    }
    header("Location: cart.php"); exit();
}

$coupon_discount = $_SESSION['coupon_discount'] ?? 0;
$coupon_msg = $_SESSION['coupon_msg'] ?? '';
unset($_SESSION['coupon_msg']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_coupon'])) {
    global $pdo;
    $code = trim($_POST['coupon_code']);
    $stmt = $pdo->prepare("SELECT * FROM coupons WHERE coupon_name = ? AND expiry_date >= CURDATE()");
    $stmt->execute([$code]);
    $coupon = $stmt->fetch();

    if ($coupon) {
        $coupon_discount = 50;
        $_SESSION['coupon_msg'] = "🎉 兌換成功！已折抵 NT$ 50";
    } else {
        $coupon_discount = 0;
        $_SESSION['coupon_msg'] = "❌ 查無此兌換券或已過期";
    }
    $_SESSION['coupon_discount'] = $coupon_discount;
    header("Location: cart.php"); exit();
}

function calculateCakePrice($details) {
    $price = 1000;
    if (strpos($details['layers'] ?? '', '20層') !== false) $price += 200;
    if (strpos($details['layers'] ?? '', '25層') !== false) $price += 300;
    if (strpos($details['filling'] ?? '', '草莓') !== false) $price += 150;
    if (strpos($details['filling'] ?? '', '芋泥') !== false) $price += 100;
    if (strpos($details['topping'] ?? '', '海鹽起司') !== false) $price += 80;
    if (strpos($details['topping'] ?? '', '水果') !== false) $price += 120;
    if (strpos($details['shape'] ?? '', '心形') !== false) $price += 200;
    return $price;
}

$cart = $_SESSION['cart']['cakes'] ?? [];
$subtotal = 0;
foreach ($cart as $item) {
    $is_custom = strpos($item['name'], '客製化') !== false;
    $current_price = $is_custom ? calculateCakePrice($item['details'] ?? []) : ($item['price'] ?? 1000);
    $subtotal += ($current_price * ($item['quantity'] ?? 1));
}

$role = $_SESSION['role'] ?? 'guest';
$discount_rate = ($role === 'vip') ? 0.15 : (($role === 'member') ? 0.10 : 0);
$discount_amount = $subtotal * $discount_rate;
$shipping_fee = ($subtotal >= 2000) ? 0 : 100;
$final_total = max(0, ($subtotal - $discount_amount - $coupon_discount) + $shipping_fee);
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>購物車 - Layer Master</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #fff9f7; color: #7d6b62; padding: 40px 20px; }
        .container { max-width: 950px; margin: auto; background: #ffffff; padding: 40px; border-radius: 40px; border: 3px solid #ffe4e1; }
        h1 { color: #e9967a; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 20px; border-bottom: 1px solid #ffe4e1; }
        .nav-btn { display: inline-block; padding: 12px 25px; background: #ffe4e1; border-radius: 20px; color: #7d6b62; text-decoration: none; font-weight: bold; }
        .total-wrapper { background: #fffaf0; padding: 40px; border-radius: 30px; border: 2px dashed #ffb6c1; margin-top: 30px; }
        input[type="number"] { width: 80px; padding: 10px; border-radius: 10px; border: 2px solid #ffb6c1; font-size: 1.1rem; }
        .checkout-btn { background: #ff69b4; color: white; padding: 20px 40px; border-radius: 25px; text-decoration: none; font-size: 1.3rem; font-weight: bold; }
        
        /* 加購區塊樣式 (卡片風格) */
        .addon-section { margin: 30px 0; padding: 20px; border: 2px solid #ffe4e1; border-radius: 20px; background: #fffaf0; }
        .addon-container { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 15px; }
        .addon-card { background: white; padding: 15px; border-radius: 15px; border: 1px solid #ffe4e1; width: 180px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .addon-card:hover { transform: translateY(-5px); }
        .add-btn { width: 100%; padding: 8px; background: #3f9ab3; color: white; border: none; border-radius: 10px; cursor: pointer; font-size: 0.9rem; }
        .add-btn:hover { background: #337a8d; }
    </style>
</head>
<body>
<div class="container">
    <a href="index.php" class="nav-btn">← 回首頁</a>
    <h1>🛒 我的購物車</h1>
    <form id="qtyForm" action="cart.php" method="POST">
        <input type="hidden" name="action" value="update_qty">
        <table>
            <tr><th>項目</th><th>數量</th><th>價格</th><th>修改規格</th><th>操作</th></tr>
            <?php foreach ($cart as $index => $item) { 
                $is_custom = strpos($item['name'], '客製化') !== false;
                $current_price = $is_custom ? calculateCakePrice($item['details'] ?? []) : ($item['price'] ?? 1000);
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><input type="number" name="quantity[<?php echo $index; ?>]" value="<?php echo $item['quantity'] ?? 1; ?>" min="1" class="qty-input"></td>
                <td>NT$ <?php echo number_format($current_price * ($item['quantity'] ?? 1)); ?></td>
                <td><?php echo $is_custom ? '<a href="edit_custom_cake.php?index='.$index.'" style="color:#87ceeb;">修改</a>' : '-'; ?></td>
                <td><a href="cart.php?action=remove&index=<?php echo $index; ?>" style="color: #e9967a; font-weight: bold;">刪除</a></td>
            </tr>
            <?php } ?>
        </table>
    </form>

    <div class="addon-section">
        <h3>✨ 加購精選商品</h3>
        <div class="addon-container">
            <?php
            $addons = $pdo->query("SELECT * FROM `addons`")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($addons as $addon): ?>
                <div class="addon-card">
                    <div class="addon-info">
                        <strong><?php echo htmlspecialchars($addon['name']); ?></strong>
                        <p style="color: #ff69b4; margin: 5px 0;">NT$ <?php echo $addon['price']; ?></p>
                    </div>
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="action" value="add_addon">
                        <input type="hidden" name="addon_id" value="<?php echo $addon['id']; ?>">
                        <button type="submit" class="add-btn">+ 加入購物車</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="total-wrapper">
        <p>顧客身分：<?php echo ucfirst($role); ?></p>
        <p>小計：NT$ <?php echo number_format($subtotal); ?></p>
        <p style="color: #ff69b4;">折扣：- NT$ <?php echo number_format($discount_amount); ?></p>
        
        <?php if ($role === 'member' || $role === 'vip'): ?>
            <form action="cart.php" method="POST" style="margin: 15px 0;">
                <input type="text" name="coupon_code" placeholder="輸入優惠代碼" style="padding: 10px; border-radius: 10px; border: 1px solid #ffb6c1;">
                <button type="submit" name="apply_coupon" style="padding: 10px 20px; border-radius: 10px; border:none; background:#ffb6c1; color:white; cursor:pointer;">使用優惠</button>
                <?php if (!empty($coupon_msg)): ?>
                    <span style="margin-left: 10px; font-weight: bold; color: <?php echo ($coupon_discount > 0) ? 'green' : 'red'; ?>;"><?php echo $coupon_msg; ?></span>
                <?php endif; ?>
            </form>
            <?php if ($coupon_discount > 0): ?> <p style="color: red;">優惠券折抵：- NT$ <?php echo number_format($coupon_discount); ?></p> <?php endif; ?>
        <?php endif; ?>

        <p>運費：<?php echo ($shipping_fee == 0) ? '<span style="color: green;">免運費！</span>' : 'NT$ 100'; ?></p>
        <h2>總金額：NT$ <?php echo number_format($final_total); ?></h2>
        <div style="text-align: right;"><a href="checkout.php" class="checkout-btn">前往結帳 →</a></div>
    </div>
</div>

<script>
    document.querySelectorAll('.qty-input').forEach(function(input) {
        input.addEventListener('change', function() {
            document.getElementById('qtyForm').submit();
        });
    });
</script>
</body>
</html>