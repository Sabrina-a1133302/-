<?php
session_start();

// 1. 資料庫直接連線設定
$link = mysqli_connect("localhost", "root", "123456", "layermaster");

// 檢查連線是否成功
if (!$link) {
    die("資料庫連線失敗: " . mysqli_connect_error());
}
mysqli_set_charset($link, "utf8mb4");

// 2. 權限檢查
if (($_SESSION['role'] ?? '') !== 'admin') {
    die("無權限，請返回登入頁面。");
}

// 3. 發布活動 (使用 Prepared Statement 防止 SQL Injection)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = mysqli_prepare($link, "INSERT INTO promotions (title, content) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $title, $content);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    // 重新導向以避免重複送出表單
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// 4. 獲取紀錄
$promos = mysqli_query($link, "SELECT * FROM promotions ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>活動推播管理</title>
    <style>
        body { background: #fdfaf7; padding: 40px; font-family: 'PingFang TC', sans-serif; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .back-link { display: inline-block; margin-bottom: 20px; color: #607d8b; text-decoration: none; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box;}
        button { background: #cd6143; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_panel.php" class="back-link">← 返回後台管理系統</a>
        <h1>📣 活動推播管理</h1>
        
        <form method="POST">
            <input type="text" name="title" placeholder="活動標題" required>
            <textarea name="content" rows="4" placeholder="活動詳情..." required></textarea>
            <button type="submit" name="publish">發布推播</button>
        </form>

        <hr style="margin: 30px 0;">
        <h3>歷史推播紀錄</h3>
        <?php while ($row = mysqli_fetch_assoc($promos)): ?>
            <div style="border-bottom: 1px solid #eee; padding: 10px 0;">
                <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                <small><?php echo htmlspecialchars($row['content']); ?> (發布時間: <?php echo $row['created_at']; ?>)</small>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>