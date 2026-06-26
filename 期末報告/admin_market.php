<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'db_config.php'; // 請確認此檔案內定義的是 $pdo 物件

// 權限防護
if (($_SESSION['role'] ?? '') !== 'admin') {
    die("您沒有管理員權限。");
}

// 1. 新增口味邏輯 (使用 PDO 預備語句)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO top_flavors (flavor_name, rank) VALUES (?, ?)");
    $stmt->execute([$_POST['flavor_name'], (int)$_POST['rank']]);
    header("Location: admin_market.php");
    exit();
}

// 2. 刪除口味邏輯
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM top_flavors WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    header("Location: admin_market.php");
    exit();
}

// 3. 取得排行榜資料 (使用 PDO)
$stmt = $pdo->query("SELECT * FROM top_flavors ORDER BY rank ASC");
$flavors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>熱門口味維護</title>
    <style>
        body { font-family: 'PingFang TC', sans-serif; background: #fdfaf7; padding: 40px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        input { padding: 8px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #cd6143; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
        .delete-btn { color: #d9534f; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍰 熱門口味排行管理</h1>
        
        <form method="POST" style="margin-bottom: 30px;">
            <input type="number" name="rank" placeholder="排名" style="width: 50px;" required>
            <input type="text" name="flavor_name" placeholder="請輸入口味名稱" required>
            <button type="submit" name="add">新增口味</button>
        </form>

        <table>
            <tr><th>排名</th><th>口味名稱</th><th>動作</th></tr>
            <?php foreach ($flavors as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['rank']); ?></td>
                <td><?php echo htmlspecialchars($row['flavor_name']); ?></td>
                <td>
                    <a href="admin_market.php?delete=<?php echo $row['id']; ?>" 
                       class="delete-btn" onclick="return confirm('確定刪除？')">刪除</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="admin_panel.php" style="display:block; margin-top:20px; color:#887466;">← 返回後台</a>
    </div>
</body>
</html>