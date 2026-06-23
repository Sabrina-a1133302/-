<?php
// 假設您已經有一個 db_config.php 檔案內容如下：
// $pdo = new PDO('mysql:host=localhost;dbname=layermaster;charset=utf8', 'root', '');
require_once 'db_config.php'; 

$user_id = 1; // 假設目前登入會員 ID

// 2. 處理表單提交邏輯
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // A. 修改個人資料
    if (isset($_POST['update_profile'])) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$_POST['name'], $_POST['email'], $user_id]);
    }

    // B. 兌換點數 (扣 100 點)
    if (isset($_POST['redeem_points'])) {
        $stmt = $pdo->prepare("SELECT points, order_history FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['points'] >= 100) {
            $new_points = $user['points'] - 100;
            $history = json_decode($user['order_history'] ?? '[]', true);
            // 新增一筆歷史訂單
            $history[] = ["item" => "小蛋糕", "date" => date("Y-m-d H:i:s")];
            $json_history = json_encode($history);
            
            $stmt = $pdo->prepare("UPDATE users SET points = ?, order_history = ? WHERE id = ?");
            $stmt->execute([$new_points, $json_history, $user_id]);
        }
    }

    // C. 上傳檔案
    if (isset($_FILES['cake_model']) && $_FILES['cake_model']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . time() . "_" . basename($_FILES["cake_model"]["name"]);
        
        if (move_uploaded_file($_FILES["cake_model"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("SELECT uploaded_models FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $models = json_decode($user['uploaded_models'] ?? '[]', true);
            $models[] = $target_file;
            $json_models = json_encode($models);
            
            $stmt = $pdo->prepare("UPDATE users SET uploaded_models = ? WHERE id = ?");
            $stmt->execute([$json_models, $user_id]);
        }
    }
}

// 3. 讀取最新資料
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$history = json_decode($user['order_history'] ?? '[]', true);

// 獲取隨機推薦口味
$recommendations = $pdo->query("SELECT name, crust FROM cake_models ORDER BY RAND() LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>會員管理中心</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; padding: 20px; }
        .container { max-width: 800px; margin: auto; }
        .section { background: white; padding: 25px; margin-bottom: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h2 { border-left: 4px solid #5c6bc0; padding-left: 15px; color: #3949ab; }
        input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { background-color: #5c6bc0; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; }
        button:hover { background-color: #3949ab; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 10px; }
        .card { border: 1px solid #eee; padding: 15px; border-radius: 8px; background: #fafafa; }
        li { background: #f9f9f9; padding: 10px; margin-bottom: 5px; border-radius: 4px; font-size: 0.9em; border-left: 4px solid #a5d6a7; }
        .back-btn { display: inline-block; padding: 10px 20px; background-color: #546e7a; color: white; border-radius: 6px; text-decoration: none; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_dashboard.php" class="back-btn">⬅ 返回後台管理</a>
        
        <h1>會員管理中心</h1>
        
        <div class="section">
            <h2>今日推薦口味</h2>
            <div class="grid">
                <?php 
                if ($recommendations) {
                    foreach ($recommendations as $row) { ?>
                        <div class="card">
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                            <p style="font-size: 0.85em; color: #666;"><?php echo htmlspecialchars($row['crust']); ?></p>
                        </div>
                <?php } 
                } else {
                    echo "<p>暫無推薦資訊。</p>";
                }
                ?>
            </div>
        </div>

        <div class="section">
            <h2>個人資料修改</h2>
            <form method="POST">
                姓名: <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
                Email: <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                <button type="submit" name="update_profile">儲存變更</button>
            </form>
        </div>

        <div class="section">
            <h2>點數兌換 (目前點數: <?php echo (int)($user['points'] ?? 0); ?>)</h2>
            <form method="POST">
                <button type="submit" name="redeem_points" <?php echo ($user['points'] ?? 0) < 100 ? 'disabled' : ''; ?>>
                    兌換小蛋糕 (扣 100 點)
                </button>
            </form>

            <h3>歷史訂單記錄</h3>
            <ul>
                <?php 
                if (empty($history)) { 
                    echo "<li>尚無訂單紀錄</li>"; 
                } else { 
                    foreach (array_reverse($history) as $item) {
                        echo "<li>🕒 {$item['date']} - 🍰 {$item['item']}</li>";
                    }
                }
                ?>
            </ul>
        </div>

        <div class="section">
            <h2>上架蛋糕模型</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="cake_model" required>
                <button type="submit">上傳檔案</button>
            </form>
        </div>
    </div>
</body>
</html>