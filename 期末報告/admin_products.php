<?php
// 1. 強制顯示錯誤，幫助排查問題
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// 2. 直接建立連線，避免依賴外部檔案可能的路徑錯誤
$link = mysqli_connect("localhost", "root", "123456", "layermaster");

if (!$link) {
    die("資料庫連線失敗: " . mysqli_connect_error());
}

// 權限檢查
if (($_SESSION['role'] ?? '') !== 'admin') {
    die("存取被拒：您沒有管理員權限。");
}

// 處理 cake_models 更新
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cake'])) {
    $id = intval($_POST['id']);
    $price = intval($_POST['price']);
    $crust = mysqli_real_escape_string($link, $_POST['crust']);
    mysqli_query($link, "UPDATE cake_models SET price = $price, crust = '$crust' WHERE id = $id");
}

// 處理 addons 更新
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_addon'])) {
    $id = intval($_POST['id']);
    $price = intval($_POST['price']);
    $name = mysqli_real_escape_string($link, $_POST['name']);
    mysqli_query($link, "UPDATE addons SET name = '$name', price = $price WHERE id = $id");
}

// 3. 取得資料
$cakes = mysqli_query($link, "SELECT * FROM cake_models ORDER BY id ASC");
$addons = mysqli_query($link, "SELECT * FROM addons ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>食材與加購管理</title>
    <style>
        body { background: #fdfaf7; font-family: 'PingFang TC', sans-serif; padding: 40px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 50px; }
        th { background: #ffe4e1; padding: 15px; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #eee; }
        input { padding: 8px; border: 1px solid #ddd; border-radius: 5px; width: 90%; }
        button { background: #cd6143; color: white; border: none; padding: 8px 15px; border-radius: 8px; cursor: pointer; }
        h2 { color: #887466; border-bottom: 2px solid #ffe4e1; padding-bottom: 10px; }
        .back-link { display: inline-block; margin-top: 20px; color: #607d8b; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚙️ 食材與加購管理</h1>
        
        <h2>🍰 千層蛋糕系列</h2>
        <table>
            <tr><th>名稱</th><th>餅皮敘述</th><th>價格 (NT$)</th><th>操作</th></tr>
            <?php while ($row = mysqli_fetch_assoc($cakes)): ?>
            <tr>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><input type="text" name="crust" value="<?php echo htmlspecialchars($row['crust']); ?>"></td>
                    <td><input type="number" name="price" value="<?php echo $row['price']; ?>"></td>
                    <td><button type="submit" name="update_cake">儲存</button></td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>

        <h2>🍯 加購項目管理</h2>
        <table>
            <tr><th>項目名稱</th><th>價格 (NT$)</th><th>操作</th></tr>
            <?php while ($row = mysqli_fetch_assoc($addons)): ?>
            <tr>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"></td>
                    <td><input type="number" name="price" value="<?php echo $row['price']; ?>"></td>
                    <td><button type="submit" name="update_addon">儲存</button></td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>

        <a href="admin_panel.php" class="back-link">← 返回後台管理系統</a>
    </div>
</body>
</html>