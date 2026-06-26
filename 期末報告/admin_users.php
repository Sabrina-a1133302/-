<?php
session_start();
require_once 'db_config.php'; // 確保此檔案中定義的是 $pdo 物件

// 權限防護
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("您沒有管理員權限。");
}

// 處理權限更新請求 (使用 PDO 預備語句)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $uid = intval($_POST['uid']);
    $new_role = $_POST['role'];
    
    $stmt = $pdo->prepare("UPDATE members SET role = :role WHERE id = :id");
    $stmt->execute(['role' => $new_role, 'id' => $uid]);
}

// 查詢會員資料 (使用 PDO)
$stmt = $pdo->query("SELECT * FROM members");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>會員權限管理</title>
    <style>
        body { font-family: 'PingFang TC', sans-serif; background: #fdfaf7; padding: 40px; color: #4a3b32; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        .role-tag { background: #ffe8d6; padding: 5px 10px; border-radius: 5px; font-size: 0.9rem; }
        button { background: #cd6143; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #a04a32; }
    </style>
</head>
<body>
    <div class="container">
        <h1>👥 會員權限管理</h1>
        <table>
            <tr><th>帳號 (username)</th><th>目前權限</th><th>修改權限</th></tr>
            <?php foreach ($members as $row): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                <td><span class="role-tag"><?php echo htmlspecialchars($row['role']); ?></span></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="uid" value="<?php echo $row['id']; ?>">
                        <select name="role">
                            <option value="member" <?php if($row['role']=='member') echo 'selected';?>>member</option>
                            <option value="vip" <?php if($row['role']=='vip') echo 'selected';?>>vip</option>
                            <option value="admin" <?php if($row['role']=='admin') echo 'selected';?>>admin</option>
                        </select>
                        <button type="submit" name="update_role">更新權限</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="admin_panel.php" style="display:inline-block; margin-top: 20px; color: #887466;">← 返回後台管理系統</a>
    </div>
</body>
</html>