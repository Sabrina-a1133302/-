<?php
session_start();
// 權限檢查：確保只有管理員可以進入
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// 功能清單設定
$admin_functions = [
    ['title' => '數據看板', 'icon' => '📊', 'link' => 'admin_dashboard.php', 'desc' => '營收與訂單概況'],
    ['title' => '會員管理', 'icon' => '👥', 'link' => 'admin_users.php', 'desc' => '權限與身分調整'],
    ['title' => '價目控制', 'icon' => '🍰', 'link' => 'admin_products.php', 'desc' => '修改食材與價格'],
    ['title' => '市場匯入', 'icon' => '🌐', 'link' => 'admin_market.php', 'desc' => '更新熱門口味'],
    ['title' => '訂單通知', 'icon' => '✉️', 'link' => 'admin_orders.php', 'desc' => '發送郵件與追蹤'],
    ['title' => '活動推播', 'icon' => '📢', 'link' => 'admin_promo.php', 'desc' => '寄送優惠通知']
];
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <div class="home-btn"><a href="index.php">⬅ 返回網站首頁</a></div>
    <title>Layer Master 後台管理系統</title>
    <style>
        .admin-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 40px; }
        .admin-card { background: white; padding: 25px; border-radius: 20px; border: 2px solid #ffe4e1; text-align: center; transition: 0.3s; }
        .admin-card:hover { transform: translateY(-10px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .icon { font-size: 3rem; margin-bottom: 15px; }
        .btn { display: inline-block; padding: 10px 20px; background: #e9967a; color: white; text-decoration: none; border-radius: 10px; margin-top: 15px; }
    </style>
</head>
<body>
    <h1>⚙️ 後台管理系統</h1>
    <div class="admin-grid">
        <?php foreach ($admin_functions as $func): ?>
            <div class="admin-card">
                <div class="icon"><?php echo $func['icon']; ?></div>
                <h3><?php echo $func['title']; ?></h3>
                <p><?php echo $func['desc']; ?></p>
                <a href="<?php echo $func['link']; ?>" class="btn">進入管理</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>