<?php
session_start();
if (!isset($_GET['index']) || !isset($_SESSION['cart']['cakes'][$_GET['index']])) {
    header("Location: cart.php"); 
    exit();
}

$idx = intval($_GET['index']);
$item = $_SESSION['cart']['cakes'][$idx];
$details = $item['details'] ?? [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. 更新規格資料 (確保存入的 value 都是中文名稱)
    $_SESSION['cart']['cakes'][$idx]['details'] = [
        'layers'    => $_POST['layers'],
        'filling'   => $_POST['filling'],
        'topping'   => $_POST['topping'],
        'shape'     => $_POST['shape']
    ];
    
    // 2. 重新計算價格 (與 cart.php 邏輯同步)
    $price = 1000;
    if (strpos($_POST['layers'], '20層') !== false) $price += 200;
    if (strpos($_POST['layers'], '25層') !== false) $price += 300;
    if (strpos($_POST['filling'], '草莓') !== false) $price += 150;
    if (strpos($_POST['filling'], '芋泥') !== false) $price += 100;
    if (strpos($_POST['topping'], '海鹽起司') !== false) $price += 80;
    if (strpos($_POST['topping'], '水果') !== false) $price += 120;
    if (strpos($_POST['shape'], '心形') !== false) $price += 200;
    
    $_SESSION['cart']['cakes'][$idx]['price'] = $price;
    
    // 3. 強制組裝中文名稱，徹底解決變成英文的問題
    $name = "客製化千層蛋糕 ({$_POST['layers']} | 內餡: {$_POST['filling']} | 裝飾: {$_POST['topping']} | 造型: {$_POST['shape']})";
    $_SESSION['cart']['cakes'][$idx]['name'] = $name;
    
    header("Location: cart.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>修改客製化規格</title>
    <style>
        body { font-family: 'PingFang TC', 'Segoe UI', sans-serif; background: #fffaf9; color: #5d4f48; padding: 50px 20px; display: flex; justify-content: center; }
        .container { background: #ffffff; padding: 40px; border-radius: 30px; box-shadow: 0 10px 30px rgba(220, 180, 180, 0.2); width: 100%; max-width: 500px; border: 1px solid #ffe4e1; }
        h1 { color: #e9967a; text-align: center; font-size: 1.8rem; margin-bottom: 30px; }
        label { display: block; margin: 20px 0 8px; font-weight: bold; color: #8c7b75; font-size: 0.95rem; }
        select { width: 100%; padding: 12px; border-radius: 12px; border: 2px solid #ffe4e1; background: #fffdfd; color: #5d4f48; font-size: 1rem; cursor: pointer; transition: 0.3s; }
        select:focus { border-color: #ffb6c1; outline: none; }
        button { width: 100%; padding: 15px; margin-top: 35px; background: #ff69b4; color: white; border: none; border-radius: 15px; font-size: 1.1rem; font-weight: bold; cursor: pointer; transition: 0.3s; }
        button:hover { background: #ff85c1; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 105, 180, 0.3); }
    </style>
</head>
<body>
<div class="container">
    <h1>🍰 修改客製化規格</h1>
    <form method="POST">
        <label>1. 層數選擇</label>
        <select name="layers">
            <option value="15層 (入門級滑順口感)" <?php if(($details['layers']??'') == '15層 (入門級滑順口感)') echo 'selected'; ?>>15 層 (入門級滑順口感)</option>
            <option value="20層 (經典平衡口感)" <?php if(($details['layers']??'') == '20層 (經典平衡口感)') echo 'selected'; ?>>20 層 (經典平衡口感)</option>
            <option value="25層 (匠人豐富層次)" <?php if(($details['layers']??'') == '25層 (匠人豐富層次)') echo 'selected'; ?>>25 層 (匠人豐富層次)</option>
        </select>

        <label>2. 內餡夾層</label>
        <select name="filling">
            <option value="香醇特調鮮奶油(基礎價)" <?php if(($details['filling']??'') == '香醇特調鮮奶油(基礎價)') echo 'selected'; ?>>香醇特調鮮奶油(基礎價)</option>
            <option value="大湖新鮮香草草莓內餡(+NT$150)" <?php if(($details['filling']??'') == '大湖新鮮香草草莓內餡(+NT$150)') echo 'selected'; ?>>大湖新鮮香草草莓內餡(+NT$150)</option>
            <option value="大甲綿密手工芋泥內餡(+NT$100)" <?php if(($details['filling']??'') == '大甲綿密手工芋泥內餡(+NT$100)') echo 'selected'; ?>>大甲綿密手工芋泥內餡(+NT$100)</option>
        </select>

        <label>3. 外層裝飾</label>
        <select name="topping">
            <option value="不加裝飾" <?php if(($details['topping']??'') == '不加裝飾') echo 'selected'; ?>>不加裝飾</option>
            <option value="海鹽起司奶蓋淋醬(+NT$80)" <?php if(($details['topping']??'') == '海鹽起司奶蓋淋醬(+NT$80)') echo 'selected'; ?>>海鹽起司奶蓋淋醬(+NT$80)</option>
            <option value="綜合時令鮮水果擺件(+NT$120)" <?php if(($details['topping']??'') == '綜合時令鮮水果擺件(+NT$120)') echo 'selected'; ?>>綜合時令鮮水果擺件(+NT$120)</option>
        </select>

        <label>4. 蛋糕造型</label>
        <select name="shape">
            <option value="傳統經典圓形(1.0x)" <?php if(($details['shape']??'') == '傳統經典圓形(1.0x)') echo 'selected'; ?>>傳統經典圓形 (1.0x)</option>
            <option value="浪漫告白心形(1.2x造型手工費)" <?php if(($details['shape']??'') == '浪漫告白心形(1.2x造型手工費)') echo 'selected'; ?>>浪漫告白心形 (1.2x 造型費)</option>
        </select>

        <button type="submit">確認修改變更</button>
    </form>
</div>
</body>
</html>